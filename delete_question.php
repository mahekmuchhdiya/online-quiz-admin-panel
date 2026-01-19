<?php
session_start();



$connection = new mysqli("localhost", "root", "", "quiz");
if ($connection->connect_error) {
    die("DB connection failed: " . $connection->connect_error);
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Delete answers related to question first (to avoid foreign key issues)
    $stmt = $connection->prepare("DELETE FROM answer WHERE qid = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    // Delete the question itself
    $stmt = $connection->prepare("DELETE FROM question WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    header("Location: delete_question.php?msg=Question deleted successfully");
    exit();
}

// Fetch all questions
$result = $connection->query("SELECT * FROM question ORDER BY id ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Questions | Quiz Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        a.delete-btn {
            color: white;
            background-color: #d9534f;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }
        a.delete-btn:hover {
            background-color: #c9302c;
        }
        .msg {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <h1>Delete Questions</h1>
    <?php if (isset($_GET['msg'])): ?>
        <div class="msg"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['question']) ?></td>
                    <td>
                        <a class="delete-btn" href="delete_question.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="3">No questions found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>