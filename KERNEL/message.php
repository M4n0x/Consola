<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Message {

    private $_Types = Array("info", "error", "valid", "warning", "load");
    private $_Messages;

    function __construct() {
        $this->_messages = array();
    }

    public function add($type, $message) {
        if (!in_array($type, $this->_Types)) {
            return false;
        }
        $this->_Messages[$type][] = $message;
    }

    public function getMessages($type = "all") {
        if ($type == "all") {
            $data = $this->_Messages;
            $this->clear();
        }
        
        $messages = array();
        
        if (in_array($type, $this->_Types)) {
               if (!empty($this->_Messages[$type])) {
                    foreach ($this->_Messages[$type] as $message) {
                        $messages[$type][] = $message;
                    }
                    $data = $messages;
                    $this->clear($type);
                }
        }
        
        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }    
    }

    public function hasMessages($type = "all") {
        if ($type == "all") {
            foreach ($this->_Types as $type) {
                if (!empty($this->_Messages[$type])) {
                    return true;
                }
            }
            return false;
        }
        if (!array_search($type, $this->_Types)) {
            return false;
        }
        return (!empty($this->_Messages[$type]));
    }
    
    public function clear($type="all") {
        if ($type=="all") {
                foreach ($this->_Types as $type) {
                unset($this->_Messages[$type]);
            }
        } else {
            unset($this->_Messages[$type]);
        }
    }
    
}
