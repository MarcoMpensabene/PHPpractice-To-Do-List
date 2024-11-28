<?php

require_once __DIR__ . '../../config/database.php';
require_once  __DIR__ .  '../../src/Models/User.php';

use App\Models\UserModel;

try {

    $pdo = require __DIR__ . '../../config/database.php';


    $userModel = new UserModel($pdo);


    $username = 'test_user';
    $password = password_hash('password123', PASSWORD_BCRYPT);
    $email = 'test@example.com';


    $userCreated = $userModel->createUser($username, $password, $email);
    if ($userCreated) {
        echo "Utente creato con successo!\n";
    } else {
        echo "Errore durante la creazione dell'utente.\n";
    }


    $user = $userModel->getUserByUsername($username);
    if ($user) {
        echo "Utente recuperato con successo:\n";
        print_r($user);
    } else {
        echo "Nessun utente trovato con il nome $username.\n";
    }
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}
