<?php

namespace App\Controllers;

use App\{Redirect, Services\User\UserManagementService, UserValidation, View};

class LoginController
{
    public function index(): View
    {
        return new View("login");
    }

    public function login(): Redirect
    {
        $validation = new UserValidation();
        $validation->loginValidate($_POST);

        if ($validation->validationFailed()) {
            return new Redirect('/login');
        }

        $service = new UserManagementService();
        $userData = $service->getUserByEmail($_POST['email']);

        $_SESSION["auth_id"] = $userData['id'];;
        return new Redirect('/');
    }

    public function logout(): Redirect
    {
        unset($_SESSION['auth_id']);
        unset($_SESSION['portfolio']);
        return new Redirect('/login');
    }
}