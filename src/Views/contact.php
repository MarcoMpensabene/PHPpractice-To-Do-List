<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validazione dei campi del form
    $name = trim($_POST['name']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $message = trim($_POST['message']) ?? '';

    if (empty($name) || empty($email) || empty($message)) {
        $errorMessage = "Tutti i campi sono obbligatori.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Inserisci un'email valida.";
    } else {
        // Parametri email
        $to = "admin@example.com"; // Sostituisci con l'email dell'amministratore
        $subject = "Nuovo messaggio da $name";
        $body = "Nome: $name\nEmail: $email\nMessaggio:\n$message";
        $headers = "From: $email";

        // Invio email
        if (mail($to, $subject, $body, $headers)) {
            $successMessage = "Il tuo messaggio è stato inviato con successo!";
        } else {
            $errorMessage = "Si è verificato un errore nell'invio del messaggio.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contattaci</title>
    <link rel="stylesheet" href="../style/contact.css">
</head>

<body>
    <div class="container">
        <h1>Contattaci</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="success"><?= htmlspecialchars($successMessage) ?></div>
        <?php elseif (!empty($errorMessage)): ?>
            <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="contact.php">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" placeholder="Il tuo nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="La tua email" required>

            <label for="message">Messaggio:</label>
            <textarea id="message" name="message" placeholder="Scrivi il tuo messaggio..." required></textarea>

            <button type="submit">Invia</button>
        </form>
    </div>
</body>

</html>