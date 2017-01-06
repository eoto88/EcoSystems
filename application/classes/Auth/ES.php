<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Auth_ES extends Auth
{
    protected function _login($username, $password, $remember) {
        $mUser = new Model_ESUser();

        $user = $mUser->checkPassword($username, $password);

        if($user) {
            if($remember) {
                Cookie::$expiration = Date::WEEK;
            }
            return $this->complete_login($username);
        }

        return FALSE;
    }

    public function password($username) {
        // Return the password for the username
        return '';
    }

    public function check_password($password) {
        // Check to see if the logged in user has the given password
        $username = parent::get_user();
        $mUser = new Model_ESUser();
        $user = $mUser->checkPassword($username, $password);

        if($user) {
            return TRUE;
        }

        return FALSE;
    }

    public function get_user($default = NULL) {
        $username = parent::get_user();

        $mUser = new Model_ESUser();
        $user = $mUser->getUserByUsername($username);

        if ($user) {
            return $user;
        } else {
            return null;
        }
    }
}