<?php

namespace SellNow\Services;


class AuthService
{
    public function addUserDataInSession($userData):void
    {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
    }
    public function getAddData($data):array
    {
      return  [
            'email' => $data['email'],
            'username' => $data['username'],
            'full_name' => $data['full_name'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];
    }
}