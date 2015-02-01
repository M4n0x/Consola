<?php

/**
 * Session
 * 
 * Permet de gérer la session de l'utilisateur
 *
 * @author Steve Reis
 */
class Session {

    public static function initSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id();
        }
    }

    private static function newSession() {
        self::set("flash", new message());
        self::setToken();
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return false;
    }

    public static function init() {
        self::initSession();
        if (!self::existSession()) {
            self::newSession();
        } else {
            self::checkToken();
        }
    }

    public static function existSession() {
        return self::get("token");
    }

    public static function checkToken() {
        $currToken = sha1(filter_input(INPUT_SERVER, 'HTTP_USER_AGENT') . filter_input(INPUT_SERVER, 'REMOTE_ADDR'));
        if (self::get("token") === $currToken) {
            session_regenerate_id();
            return true;
        } else {
            self::resetSession();
        }
    }

    private static function setToken() {
        self::set("token", sha1(filter_input(INPUT_SERVER, 'HTTP_USER_AGENT') . filter_input(INPUT_SERVER, 'REMOTE_ADDR')));
    }

    public static function resetSession() {
        session_unset();
        session_regenerate_id();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600); # Unset the session id
        }
        
        session_regenerate_id();
        session_destroy();
        unset($_SESSION);
        session_write_close();

        session_regenerate_id();

        self::initSession();
        self::newSession();
    }

}
