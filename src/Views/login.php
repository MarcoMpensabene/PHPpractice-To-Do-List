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
    <link rel="stylesheet" href="../style/login.css">
</head>

<body>
    <div class="form-container">
        <h1>Login</h1>
        <?php if (!empty($errorMessage)): ?>
            <div class="message"><?= $errorMessage ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Nome utente</label>
                <input type="text" id="username" name="username" placeholder="Inserisci il tuo nome utente" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Inserisci la tua password" required>
            </div>
            <div class="form-group">
                <button type="submit">Accedi</button>
            </div>
        </form>
    </div>
</body>

</html>