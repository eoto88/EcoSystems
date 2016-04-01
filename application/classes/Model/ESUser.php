<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/*
This class is called Model_ESUser simply because Model_User already exists in module ORM
*/

class Model_ESUser {

    public function getUserByUsername($username) {
        $query = DB::select('*')->from('user')->where('username', '=', $username);
        return $query->execute()->current();
    }

    public function checkPassword($username, $password) {
        $query = DB::select('*')->from('user')->where('username', '=', $username)->and_where('password', '=', $password);
        return $query->execute()->current();
    }

    public function insertUser($username, $name, $email, $password) {
        $user =$this->getUserByUsername($username);

        if ($user) {
            return array("username" => "Username already used.");
        } else {
            $hPass = new Helper_PasswordHash();
            $hPass->PasswordHash(8, false);
            $password = $hPass->HashPassword($password);

            $query = DB::insert('user', array(
                'username', 'name', 'email', 'password'
            ))->values( array(
                $username, $name, $email, $password
            ) );
            $query->execute();
        }
    }
}