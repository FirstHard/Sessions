<?php

class Session {

    public static function delete() {
        session_regenerate_id();
        setcookie(session_name(), session_id(), time()-3600);
        unlink(session_save_path() . '/sess_' . $_SESSION[session_name()]);
        unset($_COOKIE);
        unset($_SESSION);
        session_unset();
        session_destroy();
        header('Location: ' . HOME);
    }

    public static function emergencyDelete() {
        session_regenerate_id();
        $compromised_cookies = $_COOKIE[session_name()];
        unlink(session_save_path() . '/sess_' . $compromised_cookies);
    }

    public static function writeSession($login) {
        // Writing data to the session and cookie
        $_SESSION['logged_user'] = $login;
        $_SESSION[session_name()] = session_id();
        $_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        setcookie(session_name(), session_id(), 1440, '/', HOME, false, true);
        header('Location: ' . HOME);
    }

    public static function checkUserSession() {
        // Simple XSS attack check, prohibiting the same session from different user agent and from different IP
        if (($_SERVER['REMOTE_ADDR'] === $_SESSION['REMOTE_ADDR']) && ($_SERVER['HTTP_USER_AGENT'] === $_SESSION['USER_AGENT']) && ($_SESSION[session_name()] === $_COOKIE[session_name()])) {
            return $_SESSION["logged_user"];
        }
        self::delete();
        self::emergencyDelete();
        return false;
    }
}