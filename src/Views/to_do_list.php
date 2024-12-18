<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '../../../config/database.php';

$pdo =  require __DIR__ . '../../../config/database.php';
$user_id = $_SESSION['user_id'];

// Aggiungi un task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['description'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, status) VALUES (:user_id, :title, :description, :status)");
    $stmt->execute([
        'user_id' => $user_id,
        'title' => $title,
        'description' => $description,
        'status' => 'pending' // Valore predefinito per lo stato
    ]);
}

// Segna un task come completato
if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];
    $stmt = $pdo->prepare("UPDATE tasks SET completed = TRUE WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
}

// Cancella un task
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
}

// Recupera i task dell'utente
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="../style/to_do_list.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>La tua To-Do List</h1>
        </header>

        <!-- Messaggi di errore/successo -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert success"><?= htmlspecialchars($successMessage) ?></div>
        <?php elseif (!empty($errorMessage)): ?>
            <div class="alert error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <!-- Form per aggiungere un task -->
        <form method="post" class="task-form">
            <div class="form-group">
                <input type="text" name="title" placeholder="Titolo del task" required>
            </div>
            <div class="form-group">
                <textarea name="description" placeholder="Descrizione del task" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Task</button>
        </form>

        <!-- Elenco dei task -->
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li class="task-item <?= $task['status'] === 'completed' ? 'completed' : '' ?>">
                    <div class="task-content">
                        <h3><?= htmlspecialchars($task['title']) ?></h3>
                        <p><?= htmlspecialchars($task['description']) ?></p>
                        <span class="status <?= $task['status'] ?>"><?= ucfirst($task['status']) ?></span>
                    </div>
                    <div class="task-actions">
                        <?php if ($task['status'] !== 'completed'): ?>
                            <a href="?complete=<?= $task['id'] ?>" class="btn btn-success">Completa</a>
                        <?php endif; ?>
                        <a href="?delete=<?= $task['id'] ?>" class="btn btn-danger">Elimina</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>