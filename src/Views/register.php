<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '../../../config/database.php';
    require_once  __DIR__ .  '../../../src/Models/User.php';

    $pdo = require __DIR__ . '../../../config/database.php';
    $userModel = new App\Models\UserModel($pdo);

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash della password

    if ($userModel->createUser($username, $password, $email)) {
        echo "Registrazione completata con successo.";
    } else {
        echo "Errore durante la registrazione.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Registrazione</title>
</head>

<body>
    <h1>Registrazione</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Nome utente" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Registrati</button>
    </form>
</body>

</html>