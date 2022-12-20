<?php

namespace App\Controllers;

use App\{Redirect, SendStockValidation, View};
use App\Services\Friends\{ShowFriendsService, SendStockService};

class FriendsController
{
    public function index(): View
    {
        $service = new ShowFriendsService();
        $friends = $service->execute();
        return new View("friends", ['friends' => $friends]);
    }

    public function singleFriend(): View
    {
        $service = new ShowFriendsService();
        $friend = $service->executeSingle($_GET['id']);
        return new View("singleFriend", ['friend' => $friend]);
    }

    public function sendStock(): Redirect
    {
        $validation = new SendStockValidation();
        $validation->validate($_POST);
        if ($validation->validationFailed()) {
            return new Redirect('/friend?id=' . $_POST['friend_id']);
        }

        $service = new SendStockService();
        $service->execute($_POST);
        return new Redirect('/friend?id=' . $_POST['friend_id']);
    }
}