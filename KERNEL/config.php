<?php

/**
 * Config
 * 
 * Permet de manipuler le fichier de config (récupération des valeurs)
 *
 * @author Steve Reis
 */

class Config {

    private static $_parameters;

    public static function get($key, $value, $default = null) {
        if (isset(self::getParameter()[$key][$value])) {
            $value = self::getParameter()[$key][$value];
        } else {
            $value = $default;
        }
        return $value;
    }

    private static function getParameter() {
        if (self::$_parameters == null) {
            $path = "Config/config.ini";

            if (!file_exists($path)) {
                throw new Exception("Le fichier de configuration est introuvable");
            } else {
                self::$_parameters = parse_ini_file($path,true);
            }
        }
        return self::$_parameters;
    }

}
