<?php

/**
 * Routeur
 * 
 * C'est la colonne vértébral du modèle MVC permet de rediriger sur le bon controller/Action
 *
 * @author Steve Reis
 */
class Router {
    
    private $_request;

    public function RouteRequest() {
        try {
            //$request = new Request(array_merge($_GET,$_POST));
            $request = new Request($_GET, $_POST);
            
            $this->_request = $request;
            
            $controller = $this->setController($this->_request);
            
            $controller->execAction($this->_request->getAction());
            
            
        } catch (Exception $e) {
            $this->setError($e->getMessage(),$e->getCode());
        }
    }

    private function setController(Request $request) {
        $controllerName = "Controller" . $request->getController();
        $filename = "controller/" . $controllerName . ".php";

        if (file_exists($filename)) {
            require $filename;
            $controller = new $controllerName($request);
            return $controller;
        } else {
            throw new Exception("Le controleur ->" . $filename . " n'a pas été trouvé. 404 not found.", "404");
        }
    }

    public function setAction(Request $request) {
        $DefaultAction = "index";
        if ($request->ExistParam('action')) {
            return $request->getParam('action');
        }
        return $DefaultAction;
    }

    public function setError($msgError, $page="Erreur") {
        $page = ($page==0) ? "Erreur" : $page;
        $this->_request->setController("Error");
        $this->_request->setAction($page);
        $view = new Page($this->_request);
        $view->render(array("msgErreur" => $msgError));
    }
}
