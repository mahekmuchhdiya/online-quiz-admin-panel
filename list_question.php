<?php
$conn = new mysqli("localhost", "root", "", "quiz");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SELECT * FROM question");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Question List</title>
    <style>
        table { width: 80%; margin: 30px auto; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        th { background-color: #333; color: white; }
        button { padding: 5px 10px; background: #007bff; color: white; border: none; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Question List</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Question</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['question']) ?></td>
        <td>
            <form action="edit_question.php" method="get">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit">Edit</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>