<?php
namespace KERNEL;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Outils
 *
 * @author reiss
 */
class Utils {

    public static function serialize_array_values($arr) {
        //http://stackoverflow.com/questions/19010180/array-intersect-throws-errors-when-arrays-have-sub-arrays
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                sort($val);
                $arr[$key] = serialize($val);
            }
        }
        //var_dump($arr);
        return $arr;
    }
       
    public static function FormatDate($date, $in, $out) {
        if (empty($date)) {
            throw new Exception("La date ne peut-être null !");
        }
        try {
            $DateTime = DateTime::createFromFormat($in, $date);
            if (!$DateTime) {
                throw new Exception("La date n'est dans un format adéquat !");
            }
            $newDate = $DateTime->format($out);
            return $newDate;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() . " (" . $date . ")");
        }
    }
    
    public static function checkboxValue(&$value) {
        $value = (!(empty($value))) ? 1 : 0;
    }
    
    public static function getDisabled($bParam) {
        return (($bParam) ? "" : "disabled");
    }
    
    public static function isEmpty() {
        
    }
    
    public static function notEmpty() {
        
    }
    
    public static function getIfNotEmpty($value, $valueIfEmpty = "", $valueIfNotEmpty = "") {
        if (!empty($value)) {
            if (!empty($valueIfNotEmpty)) {
                return $valueIfNotEmpty;
            }
            return $value;
        }
        
        return $valueIfEmpty;
    }
    
    public static function cleanArray(&$array) {
        foreach ($values as &$value) {
            unset($value);
        }
    }

}
