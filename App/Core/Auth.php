<?php

namespace App\Core;

class Auth
{

    public $logged_user = false;
    public $errors = false;

    public function __construct()
    {
        $this->isAuth();
        if (isset($_GET['do']) && $_GET['do'] == 'login') $this->doLogin();
        if (isset($_GET['do']) && $_GET['do'] == 'logout') Session::delete();
    }

    private function doLogin()
    {
        $message = false;
        if ($data = filter_input_array(INPUT_POST)) {
            if ($user = $this->getUserByLogin($data['login'])) {
                if (password_verify($data['password'], $user['pass_hash'])) {
                    // That's right, let's authorize the user and redirecting him to Home page.
                    $this->logged_user = $user;
                    Session::writeSession($this->logged_user);
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

    private function getUserByLogin($login)
    { // Simple method for User Model in this case, works with arrays instead of objects
        $users = require_once(ROOT . '/config/users.php');
        foreach ($users as $key => $user) {
            if ($user['login'] == $login || $user['email'] == $login) {
                return $users[$key];
            }
        }
        return false;
    }

    private function isAuth()
    {
        $message = false;
        if (isset($_SESSION["logged_user"])) { // If session variable logged_user exists
            if ($this->logged_user = Session::checkUserSession()) {
                return true;
            } else {
                $message['error']['message_heading'] = 'Suspected XSS attack!';
                $message['error']['message_description'] = 'For security reasons, we have deleted the previous session. Please log in again!';
                $this->errors = $message;
            }
        }
        return false; //The user is not logged in because variable logged_user not created or checking session is false
    }
}
