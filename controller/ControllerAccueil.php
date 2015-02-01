<?php

/**
 * ControllerAccueil
 * 
 * Cette page sera appelée losrqu'aucun controlleur ne sera défini.
 * C'est notre page par défaut. Cette page affichera uniquement les articles.
 * 
 * @author Steve Reis
 */
Class ControllerAccueil extends Controller {

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
