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
    <link rel="stylesheet" href="../style/register.css">
</head>

<body>
    <div class="form-container">
        <h1>Registrazione</h1>
        <?php if (!empty($successMessage)): ?>
            <div class="message success"><?= $successMessage ?></div>
        <?php elseif (!empty($errorMessage)): ?>
            <div class="message error"><?= $errorMessage ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Nome utente</label>
                <input type="text" id="username" name="username" placeholder="Inserisci il tuo nome utente" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Inserisci la tua email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Inserisci la tua password" required>
            </div>
            <div class="form-group">
                <button type="submit">Registrati</button>
            </div>
        </form>
    </div>
</body>

</html>