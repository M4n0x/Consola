<?php

/**
 * Controller
 * 
 * Class mère pour tous les controlleurs.
 *
 * @author Steve Reis
 */
abstract class Controller {

    protected $_action;
    protected $_request;
    protected $_errors;
    protected $_layout;
    protected $_page;

    public function __construct($request) {
        $this->_request = $request;
        $this->_errors["content"] = array();
        $this->_action = $this->_request->getAction();
    }

    public function execAction($action) {
        if (method_exists($this, $action)) {
            $this->_action = $action;
            //$this->_request->setAction($action);
            $this->_page = new Page($this->_request);
            $this->{$this->_action}();
        } else {
            throw new Exception("L'action -> " . $action . " n'est pas définie dans " . get_class($this),404);
        }
    }

    public function addError($msgError) {
        array_push($this->_errors["content"], $msgError);
    }

    public function setTypeMessage($typeError) {
        $this->_errors["type"] = $typeError;
    }

    public abstract function index();

    protected function loadView($dataView = array(),$includeLayout = true) {
        if (!empty($this->_errors["content"])) {
            $this->_page->setErrors($this->_errors);
        }
        if (!empty($this->_layout) && $includeLayout) {
            $this->_page->setLayout($this->_layout);
        }
        $this->_page->render($dataView, $includeLayout);
    }
    
    protected function loadJson($data = null, $error = -1, $status = -1, $message = "") {
        
        $result['ldata'] = $data;
        $result['error'] = $error;
        $result['status'] = $status;
        $result['msg'] = $message;
        
        print_r(json_encode($result));
    }

}
