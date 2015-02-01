<?php

/**
 * Page
 * 
 * Allow to generate dynamicaly generate a page to the user.
 *
 * @author Steve Reis
 */
Class Page {

    private $_fichier;
    private $_titre;
    private $_layout;
    private $_js = array();
    private $_css = array();
    private $_welcome = false;
    private $_parseDown;
    private $_request;

    function __construct(Request $request) {
        $this->_parseDown = new Parsedown();
        $this->_request = $request;
        $controller = $request->getController();
        $action = $request->getAction();
                
        $this->_layout = Config::get("Global", "WEBROOT") . "View/Layout.php";
        $file = "View/";
        if ($controller != "") {
            $file = $file . $controller . "/";
        }
        $this->_fichier = $file . $action . ".php";
        
        //Get Welcome message if there one 
        $strWelcomeBase = Config::get("Global", "WEBROOT") . $file;
        $strFileWelcome = $strWelcomeBase . $action . "_welcome.txt";
        $strDefaultWelcome = $strWelcomeBase . "_welcome.txt";
        
        if (file_exists($strFileWelcome)) {
            $this->_welcome = file_get_contents($strFileWelcome);
        } elseif (file_exists($strDefaultWelcome)) {
            $this->_welcome = file_get_contents($strDefaultWelcome);
        }
    }
    
    /**
     * setLayout
     * 
     * If you want to change the layout (not recommended). Your layout must containt the minimum information in it.
     * Only a basic structure HTML must be in the page. All other information/design must be in a specified view !
     * 
     * @param string $layout path to the view
     */
    public function setLayout($layout) {
        $this->_layout = $layout;
    }

    /**
     * addJavascript
     * 
     * Allow to add a javascript to the layout
     * 
     * @param string $path Path to the JS (WEBROOT is implicit so from the root folder -> ex. js/MyFolder/MyJS.js you just have to declare "MyFolder/MyJS.js")
     * @param boolean $top determine if the js have to be added on the top of the JS
     * @return boolean return only false if the JS is already exist or if not containt "*.js"
     */
    public function addJavascript($path, $top = false) {

        if (array_search($path, $this->_js) || !strpos($path, ".js")) {
            return false;
        }

        if ($top) {
            array_unshift($this->_js, $path);
        } else {
            array_push($this->_js, $path);
        }
    }

    /**
     * addCSS
     * 
     * Allow to add a stylesheet to the layout
     * 
     * @param string $path Path to the CSS (WEBROOT is implicit so from the root folder -> ex. css/MyFolder/MyCSS.css)
     * @param boolean $top determine if the css have to be added on the top of the CSS
     * @return boolean return only false if the CSS is already exist or if not containt "*.css"
     */
    public function addCSS($path, $top = false) {

        if (array_search($path, $this->_css) || !strpos($path, ".css")) {
            return false;
        }

        if ($top) {
            array_unshift($this->_css, $path);
        } else {
            array_push($this->_css, $path);
        }
    }

    /**
     * render
     * 
     * When all work is done, with this function you can render the containt of the final page
     * 
     * @param array $data values to inject into the view of the page as array("NameOfValue1InThePage" => $value1)
     */
    public function render($data,$includeLayout = true) {
        
        $contenu = $this->generate($this->_fichier, $data);
        
        if (!$includeLayout) {
            echo $contenu;
            return;
        }

        $WEBROOT = config::get("Global", "webroot");

        $view = $this->generate($this->_layout, array("titre" => $this->_titre,
            "contenu" => $contenu,
            "WEBROOT" => $WEBROOT,
            "javascripts" => $this->_js,
            "stylesheets" => $this->_css));

        echo $view;
    }

    public function genPagination($data) {
        return $this->generate("View/Pagination.php", $data);
    }

    /**
     * generate
     * 
     * Allow to generate a specified page with data (values) needed in it. 
     * 
     * @param string $fichier
     * @param array $data
     * @return string return the page generated
     * @throws Exception if file is unreachable or is not found
     */
    public function generate($fichier, $data = array()) {
        if (file_exists($fichier)) {
            //$data["page"] = &$this;
            extract($data);

            ob_start();

            require $fichier;

            return ob_get_clean();
        } else {
            throw new Exception("Le fichier : " . $fichier . " est introuvable.");
        }
    }

}
