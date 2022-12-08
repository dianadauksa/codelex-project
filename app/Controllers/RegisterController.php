<?php

namespace App\Controllers;

use App\{Redirect, Validation, View};
use App\Services\{RegisterServiceRequest, RegisterService};

class RegisterController
{
    public function index(): View
    {
        return new View("register");
    }

    public function register(): Redirect
    {
        $validation = new Validation();
        $validation->registerValidate($_POST);
        if ($validation->validationFailed()) {
            return new Redirect('/register');
        }

        $registerService = new RegisterService();
        $registerService->execute(
            new RegisterServiceRequest(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            )
        );
        return new Redirect('/login');
    }
}