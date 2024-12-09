<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '../../../config/database.php';
    require_once  __DIR__ .  '../../../src/Models/User.php';

    $pdo = require __DIR__ . '../../../config/database.php';
    $userModel = new App\Models\UserModel($pdo);

    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $userModel->getUserByUsername($username);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo "Login effettuato con successo.";
    } else {
        echo "Credenziali non valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Nome utente" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Accedi</button>
    </form>
</body>

</html>