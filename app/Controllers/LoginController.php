<?php

namespace App\Controllers;

use App\{Database, Redirect, Validation, View};

class LoginController
{
    public function index(): View
    {
        return new View("login");
    }

    public function login(): Redirect
    {
        $validation = new Validation();
        $validation->loginValidate($_POST);

        if ($validation->validationFailed()) {
            return new Redirect('/login');
        }

        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userData = $queryBuilder
            ->select('*')
            ->from('Users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])
            ->fetchAssociative();

        $_SESSION["auth_id"] = $userData['id'];;
        return new Redirect('/');
    }

    public function logout(): Redirect
    {
        unset($_SESSION['auth_id']);
        return new Redirect('/login');
    }
}