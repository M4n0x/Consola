<?php

/**
 * ControllerUser
 *
 * 
 * 
 * @author Steve Reis
 */
Class ControllerUser extends Controller {

    /**
     *
     * @var object Contient l'objet article 
     */
    private $_poste;

    /**
     * __construct
     * 
     * Fonction d'initialisation de la classe
     * 
     * @param object $request
     */
    public function __construct($request) {
        parent::__construct($request);
    }

    public function index() {
        
        $this->loadView();
    }
    
    
    
}
