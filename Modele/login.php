<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class login {

    private $_ldap;
    private $_access = array();
    private $_isAdmin = 0;
    private $_username;

    public function __construct() {
        
    }

    public function login($username, $password) {
        if (empty($username) || empty($password)) {
            throw new Exception("Le nom d'utilisateur ainsi que le mot de passe doivent être renseignés.");
        }

        $username = str_replace("adi\\", '', $username);
        $this->_username = $username;
        
        //initialize ldap on LDAP_Path who is set to adi.adies.lan
        $ldap = ldap_connect("ldap://" . Config::get('Global', 'LDAP_path'));
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        if (!empty($ldap)) {
            if (@ldap_bind($ldap, $this->_username . "@" . Config::get('Global', 'LDAP_path'), $password)) {
                $this->_ldap = $ldap;
                $this->_setRights();
                return true;
            }
        }
        return false;
    }

    private function getDN($samaccountname) {
        $attributes = array('dn');
        $result = ldap_search($this->_ldap, Config::get('Global', 'LDAP_basedn'), "(samaccountname={$samaccountname})", $attributes);
        if ($result === FALSE) {
            return '';
        }

        $entries = ldap_get_entries($this->_ldap, $result);

        if ($entries['count'] > 0) {
            return $entries[0]['dn'];
        } else {
            return '';
        }
    }

    private function _setRights() {
        $groupes = group::findAll();
        $listGroupes = array();

        foreach ($groupes as $groupe) {
            if ($this->checkGroup($this->_username, $groupe->GROUP_NAME)) {
                array_push($listGroupes, $groupe->GROUP_ID);
                if ($groupe->IS_ADMIN && ($groupe->IS_ADMIN > $this->_isAdmin)) {
                    $this->_isAdmin = $groupe->IS_ADMIN;
                }
            }
        }

        if (!empty($listGroupes)) {
            $listAccess = access::findDetailed(array("GROUP_ID" => array("value" => $listGroupes, "operator" => "IN")));

            foreach ($listAccess as $access) {
                if (!array_search($access->ETAB_ID->ETAB_ID, $this->_access)) {
                    array_push($this->_access, $access->ETAB_ID->ETAB_ID);
                }
            }
        }
    }

    private function checkGroup($username, $groupname) {
        $attributes = array('members');
        $result = ldap_read($this->_ldap, $this->getDN($username), "(memberof={$this->getDN($groupname)})", $attributes);
        if ($result === FALSE) {
            return FALSE;
        }
        $entries = ldap_get_entries($this->_ldap, $result);

        return ($entries['count'] > 0);
    }

    public function getProperty($attribute, $samaccountname = NULL) {
        $samaccountname = (is_null($samaccountname)) ? $this->_username : $samaccountname;
        $attributes = array($attribute);
        $result = ldap_search($this->_ldap, $this->getDN($samaccountname), "(samaccountname={$samaccountname})", $attributes);
        if ($result === FALSE) {
            return '';
        }

        $entries = ldap_get_entries($this->_ldap, $result);

        if ($entries['count'] > 0) {
            return $entries[0][$attribute];
        } else {
            return '';
        }
    }

    public function __destruct() {
        if (!empty($this->_ldap)) {
            ldap_close($this->_ldap);
        }
    }

    public function getAccess() {
        return $this->_access;
    }

    public function getAdmin() {
        return $this->_isAdmin;
    }
    
    public function getDisplayName() {
        $cn = $this->getProperty("cn");
        if (is_array($cn)) {
            return $cn[0];
        } else {
            return $this->_username;
        }
    }

}
