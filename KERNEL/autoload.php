<?php

/**
 * Routeur
 * 
 * Cette function va permettre d'automatiquement include des fichiers php lorsque la classe est appelée.
 *
 * @author Steve Reis
 * adapté depuis http://www.php.net/manual/fr/language.oop5.autoload.php
 */
class autoloader {

    public static $loader;

    public static function init()
    {
        if (self::$loader == NULL){
            self::$loader = new self();
        }
        return self::$loader;

    }

    public function __construct()
    {
        spl_autoload_register(array($this,'model'));
        spl_autoload_register(array($this,'controller'));
        spl_autoload_register(array($this,'library'));
        spl_autoload_register(array($this,'kernel'));
        spl_autoload_register(array($this,'kernel_v2'));
    }

    public function library($class)
    {
        // a vérifier config::get("Global", "webroot")
        set_include_path('Library/');
        spl_autoload_extensions('.library.php');
        spl_autoload($class);
    }

    public function controller($class)
    {
        set_include_path('Controller/');
        spl_autoload_extensions('.php');
        spl_autoload($class);
    }

    public function model($class)
    {
        set_include_path('Modele/');
        spl_autoload_extensions('.php');
        spl_autoload($class);
    }
    
    public function kernel($class)
    {
        set_include_path('KERNEL/');
        spl_autoload_extensions('.php');
        spl_autoload($class);
        
    }
    
    public function kernel_v2($class)
    {
        set_include_path('KERNEL_v2/');
        spl_autoload_extensions('.php');
        spl_autoload($class);
    }
}