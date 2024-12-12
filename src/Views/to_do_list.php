<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '../../../config/database.php';

$pdo =  require_once __DIR__ . '../../../config/database.php';
$user_id = $_SESSION['user_id'];

// Aggiungi un task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = $_POST['task'];
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task) VALUES (:user_id, :task)");
    $stmt->execute(['user_id' => $user_id, 'task' => $task]);
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
        <h1>Ciao, gestisci la tua To-Do List</h1>

        <!-- Form per aggiungere un task -->
        <form method="post" class="task-form">
            <input type="text" name="task" placeholder="Aggiungi un nuovo task" required>
            <button type="submit">Aggiungi</button>
        </form>

        <!-- Elenco dei task -->
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li class="<?= $task['completed'] ? 'completed' : '' ?>">
                    <?= htmlspecialchars($task['task']) ?>
                    <?php if (!$task['completed']): ?>
                        <a href="?complete=<?= $task['id'] ?>" class="complete">Completa</a>
                    <?php endif; ?>
                    <a href="?delete=<?= $task['id'] ?>" class="delete">Elimina</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>