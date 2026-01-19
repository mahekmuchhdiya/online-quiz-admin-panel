<?php
ob_start();
$mysqli = new mysqli("localhost", "root", "", "quiz");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: list_question.php");
    exit();
}

// Get question
$question = $mysqli->query("SELECT * FROM question WHERE id = $id")->fetch_assoc();

// Get answers
$answers_res = $mysqli->query("SELECT * FROM answer WHERE qid = $id");
$answers = [];
while ($row = $answers_res->fetch_assoc()) {
    $answers[] = $row;
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $qtext = trim($_POST['question']);
    $correct = (int)$_POST['correct'];

    if ($qtext == "" || count($_POST['answers']) < 2) {
        $error = "Please fill all fields and provide at least 2 answers.";
    } else {
        $mysqli->query("UPDATE question SET question = '$qtext', ans_id = $correct WHERE id = $id");

        foreach ($_POST['answers'] as $aid => $atext) {
            $atext = $mysqli->real_escape_string($atext);
            $mysqli->query("UPDATE answer SET answer = '$atext' WHERE id = $aid");
        }

        $success = "✅ Question successfully edited!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Question</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
        }

        .container {
            background: white;
            width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        textarea, input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        input[type="radio"] {
            margin-right: 8px;
        }

        label {
            font-size: 16px;
            color: #444;
        }

        .answers {
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Question</h2>

    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Question:</label>
        <textarea name="question" rows="4" required><?= htmlspecialchars($question['question']) ?></textarea>

        <label>Answers:</label><br>
        <div class="answers">
            <?php foreach ($answers as $ans): ?>
                <input type="text" name="answers[<?= $ans['id'] ?>]" value="<?= htmlspecialchars($ans['answer']) ?>" required>
                <label>
                    <input type="radio" name="correct" value="<?= $ans['id'] ?>" <?= ($ans['id'] == $question['ans_id']) ? "checked" : "" ?>>
                    Correct
                </label>
                <br><br>
            <?php endforeach; ?>
        </div>

        <button type="submit">Save Changes</button>
    </form>

    <a class="back-link" href="list_question.php">← Back to Question List</a>
</div>

</body>
</html>