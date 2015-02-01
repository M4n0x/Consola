<?php

/**
 * Pagination
 * 
 * La pagination va nous permettre d'afficher des pages sur un liste d'objet défini.
 *
 * @author Steve Reis
 */
class Pagination {

    private $_nbentry;
    private $_maxpage;
    private $_currentnumpage;
    private $_limit;
    private $_params;
    private $_request;
    private $_basePath;

    /**
     * __construct
     * 
     * Initialisation des paramètres de la classe
     * 
     * @param int $nbentry le nombre d'"item" à afficher au total
     * @param int $currentnumpage la page actuel
     * @param int $limit la limite d'"item" à afficher
     * @param array $params les paramètres pour reconstruire l'url
     */
    Public function __construct($nbentry, $currentnumpage, $limit, $request) {
        $this->_request = $request;
        $params = $this->_request->getURLParams();
        $this->_nbentry = $nbentry;
        $this->_limit = $limit;
        $this->_maxpage = ceil($this->_nbentry / $this->_limit);
        $this->_currentnumpage = filter_var($currentnumpage, FILTER_VALIDATE_INT, array('options' => array('default' => 1, 'min_range' => 1, 'max_range' => ($this->_maxpage))));
        $this->_params = (is_array($params) && !empty($params)) ? http_build_query($params) : "";
        $this->_basePath = $this->_request->getPath();
    }

    /**
     * backPage
     * 
     * Permet de récupérer la précédante page (et de savoir si il y en a une deja)
     * @return string retourne URL si existant, sinon retourne false
     */
    public function backPage() {
        return ($this->_currentnumpage === 1) ? false : $this->BuildURL($this->_currentnumpage - 1);
    }

    /**
     * nextPage
     * 
     * Permet de récupérer la suivante page (et de savoir si il y en a une deja)
     * @return string retourne l'url de la page suivant si nous ne sommes pas sur la dernière page
     */
    public function nextPage() {
        return ($this->_currentnumpage >= (int)$this->_maxpage) ? false : $this->BuildURL($this->_currentnumpage + 1);
    }

    /**
     * getCurrentPage
     * 
     * Permet de récupérer la page courante
     * 
     * @return int
     */
    public function getCurrentPage() {
        return $this->_currentnumpage;
    }

    /**
     * prevPages
     * 
     * Permet de récupérer les pages précédentes (ex. si la page courrante est 7 retourne un tableau avec les pages 2,3,4,5,6)
     * @return array
     */
    public function prevPages() {
        $pages = array();
        for ($x = ($this->_currentnumpage - 5); (($x <= $this->_currentnumpage - 1)); $x++) {
            if ($x <= 0) {
                continue;
            }
            array_push($pages, array("url" => $this->BuildURL($x), "num" => $x));
        }
        return $pages;
    }

    /**
     * BuildURL
     * 
     * Permet de construire l'url de la page
     * 
     * @param int $numPage
     * @return string retourne une URL
     */
    private function BuildURL($numPage) {
        return ($this->_basePath . "page-" . $numPage . ((!empty($this->_params)) ? "?" . $this->_params : ""));
    }

    /**
     * afterPages
     * 
     * Permet de récupérer les pages suivantes.
     * 
     * @return array
     */
    public function afterPages() {
        $pages = array();

        for ($x = ($this->_currentnumpage + 1); (($x <= ($this->_currentnumpage + 5)) && ($x <= $this->_maxpage)); $x++) {
            array_push($pages, array("url" => $this->BuildURL($x), "num" => $x));
        }
        return $pages;
    }

}
