<?php

namespace SellNow\Services;


class AuthService
{
    public function addUserDataInSession($userData):void
    {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
    }
}