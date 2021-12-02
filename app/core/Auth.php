<?php

    class Auth {

        public $logged_user = false;
        public $errors = false;

        public function __construct() {
            $this->isAuth();
            if (isset($_GET['do']) && $_GET['do'] == 'login') $this->doLogin();
            if (isset($_GET['do']) && $_GET['do'] == 'logout') $this->doLogout();
        }

        public function doLogin() {
            $message = false;
            if ($data = filter_input_array(INPUT_POST)) {
                if ($user = $this->getUserByLogin($data['login'])) {
                    if (password_verify($data['password'], $user['pass_hash'])) {
                        // That's right, let's authorize the user and redirecting him to Home page.
                        $this->logged_user = $user;
                        $this->writeSession();
                        header('Location: ' . HOME);
                    } else {
                        $message['error']['message_heading'] = 'You enter a wrong password!';
                        $message['error']['message_description'] = 'Please, try again!';
                    }
                } else {
                    $message['error']['message_heading'] = 'You enter a wrong login!';
                    $message['error']['message_description'] = 'Please, try again!';
                }
            } else {
                $message['error']['message_heading'] = 'Something went wrong!';
                $message['error']['message_description'] = 'The data you sent has not been validated on the server. Please, try again later!';
            }
            $this->errors = $message;
        }

        public function getUserByLogin($login) { // Simple method for User Model in this case, works with arrays instead of objects
            $users = require_once(ROOT . '/config/users.php');
            foreach ($users as $key => $user) {
                if ($user['login'] == $login || $user['email'] == $login) {
                    return $users[$key];
                }
            }
            return false;
        }

        public function isAuth() {
            $message = false;
            if (isset($_SESSION["logged_user"])) { // If session variable logged_user exists
                if ($this->checkSession()) {
                    $this->logged_user = $this->getUserByLogin($_SESSION["logged_user"]);
                    return true;
                } else {
                    $message['error']['message_heading'] = 'Suspected XSS attack!';
                    $message['error']['message_description'] = 'For security reasons, we have deleted the previous session. Please log in again!';
                    $this->errors = $message;
                }
            }
            return false; //The user is not logged in because variable logged_user not created or checking session is false
        }

        public function doLogout() {
            session_regenerate_id();
            setcookie(session_name(), session_id(), time()-3600);
            unlink(session_save_path() . '/sess_' . $_SESSION[session_name()]);
            unset($_COOKIE);
            unset($_SESSION);
            session_unset();
            session_destroy();
            header('Location: ' . HOME);
        }

        public function emergencyLogout() {
            session_regenerate_id();
            $compromised_cookies = $_COOKIE[session_name()];
            unlink(session_save_path() . '/sess_' . $compromised_cookies);
            setcookie(session_name(), session_id(), time()-3600);
            unset($_COOKIE);
            unset($_SESSION);
            session_unset();
            session_destroy();
        }

        public function writeSession() {
            // Writing data to the session and cookie
            $_SESSION['logged_user'] = $this->logged_user['login'];
            $_SESSION[session_name()] = session_id();
            $_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
            setcookie(session_name(), session_id(), 1440, '/', HOME, false, true);
            header('Location: ' . HOME);
        }

        public function checkSession() {
            // Simple XSS attack check, prohibiting the same session from different user agent and from different IP
            if (($_SERVER['REMOTE_ADDR'] === $_SESSION['REMOTE_ADDR']) && ($_SERVER['HTTP_USER_AGENT'] === $_SESSION['USER_AGENT']) && ($_SESSION[session_name()] === $_COOKIE[session_name()])) {
                $this->logged_user = $_SESSION["logged_user"];
                return true;
            }
            $this->emergencyLogout();
            return false;
        }
    }