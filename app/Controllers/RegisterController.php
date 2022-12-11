<?php

namespace App\Controllers;

use App\{Redirect, UserValidation, View};
use App\Services\User\{RegisterUserServiceRequest, UserManagementService};

class RegisterController
{
    public function index(): View
    {
        return new View("register");
    }

    public function register(): Redirect
    {
        $validation = new UserValidation();
        $validation->registerValidate($_POST);
        if ($validation->validationFailed()) {
            return new Redirect('/register');
        }

        $registerService = new UserManagementService();
        $registerService->register(
            new RegisterUserServiceRequest(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            )
        );
        return new Redirect('/login');
    }
}