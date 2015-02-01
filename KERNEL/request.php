<?php

/**
 * Request
 * 
 * Permet de gérer les requêtes de l'utilisateurs ($_GET et $_POST)
 *
 * @author Steve Reis
 */
class Request {

    private $_parameters;
    private $_GETParams;
    public static $_controller = "";
    public static $_action = "";

    public function __construct($GET, $POST) {
        $this->_parameters = $GET;
        $this->splitdURL();
        $this->_GETParams = $this->_parameters;
        $this->_parameters = array_merge($this->_parameters, $POST);
    }

    public function ExistParam($name) {
        return (isset($this->_parameters[$name]));
    }

    public function getParam($name) {
        if ($this->ExistParam($name)) {
            if (is_array($this->_parameters[$name])) {
                return filter_var($this->_parameters[$name], FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            }
            return filter_var($this->_parameters[$name]);
        } else {
            throw new Exception("Paramètre : " . $name . " n'existe pas");
        }
    }

    public function unsetParam($param) {
        if (isset($this->_parameters[$param])) {
            unset($this->_parameters[$param]);
        }
    }

    private function splitdURL() {
        if ($this->ExistParam("url")) {
            //get and clean
            $url = $this->getParam("url");
            $this->unsetParam("url");


            //Sanitize and split
            $url = rtrim($url, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);


            foreach ($url as $key => $param) {
                if (strpos($param, "-") !== false) {
                    $newParam = explode("-", $param);
                    $this->_parameters[$newParam[0]] = $newParam[1];
                    unset($url[$key]);
                }
            }

            $url = array_values($url);

            if (isset($url[0])) {
                if (!preg_match("#[0-9]#", $url[0])) {
                    self::$_controller = ucfirst(strtolower($url[0]));
                    unset($url[0]);
                }
            }

            if (isset($url[1])) {
                if (!preg_match("#[0-9]#", $url[1])) {
                    self::$_action = $url[1];
                    unset($url[1]);
                }
            }

            $url = array_values($url);

            foreach ($url as $key => $param) {
                $this->_parameters[("param" . ($key + 1))] = $param;
            }
        }
    }

    public function getParams() {
        return $this->_parameters;
    }

    public function getController() {
        if (empty(self::$_controller)) {
            return "Accueil";
        } else {
            return self::$_controller;
        }
    }

    public function getAction() {
        if (empty(self::$_action)) {
            return "index";
        } else {
            return self::$_action;
        }
    }

    public function setAction($action) {
        self::$_action = $action;
    }

    public function setController($controller) {
        self::$_controller = $controller;
    }

    public function getPath() {
        $rootUrl = array();
        if (!empty(self::$_controller)) {
            $rootUrl[] = self::$_controller;
            if (!empty(self::$_action)) {
                $rootUrl[] = self::$_action;
            }
        }

        return str_replace("//", "/", implode("/", $rootUrl));
    }

    public function getURLParams() {
        return array_uintersect($this->_parameters, $this->_GETParams, array($this, "evaluateEqual"));
    }

    private function evaluateEqual($v1, $v2) {
        if (is_array($v1)) {
            $v1 = key($v1);
        }
        if (is_array($v2)) {
            $v2 = key($v2);
        }
        if ($v1 === $v2) {
            return 0;
        }
        if ($v1 > $v2) {
            return 1;
        }
        return -1;
    }

}
