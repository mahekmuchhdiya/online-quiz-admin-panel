<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "quiz");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $question = $_POST['question'];
    $answers = $_POST['answers']; // 4 options
    $correct_option = $_POST['correct_option']; // 1 to 4

    // Step 1: Insert question (ans_id will be updated later)
    $insert_question = $conn->prepare("INSERT INTO question (question, ans_id) VALUES (?, 0)");
    $insert_question->bind_param("s", $question);
    if ($insert_question->execute()) {
        $qid = $insert_question->insert_id;

        $insert_answer = $conn->prepare("INSERT INTO answer (qid, answer) VALUES (?, ?)");
        $answer_ids = [];

        // Step 2: Insert all 4 options
        foreach ($answers as $opt) {
            $insert_answer->bind_param("is", $qid, $opt);
            $insert_answer->execute();
            $answer_ids[] = $insert_answer->insert_id;
        }

        // Step 3: Get correct answer's ID
        $correct_ans_id = $answer_ids[$correct_option - 1];

        // Step 4: Update question with correct answer ID
        $update = $conn->prepare("UPDATE question SET ans_id = ? WHERE id = ?");
        $update->bind_param("ii", $correct_ans_id, $qid);
        $update->execute();

        echo "<p style='color:green;'>Question & options added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $insert_question->error . "</p>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <style>
        form {
            width: 60%;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #aaa;
            background-color: #f9f9f9;
        }

        label, textarea, input {
            display: block;
            margin-bottom: 15px;
            width: 100%;
        }

        input[type="submit"] {
            width: auto;
            padding: 10px 20px;
        }
    </style>
</head>
<body>

    <h2 style="text-align:center;">Add New Question</h2>

    <form method="post" action="add_question.php">
        <label for="question">Enter Question:</label>
        <textarea name="question" id="question" required></textarea>

        <label>Option 1:</label>
        <input type="text" name="answers[]" required>

        <label>Option 2:</label>
        <input type="text" name="answers[]" required>

        <label>Option 3:</label>
        <input type="text" name="answers[]" required>

        <label>Option 4:</label>
        <input type="text" name="answers[]" required>

        <label for="correct_option">Correct Option Number (1 to 4):</label>
        <input type="number" name="correct_option" id="correct_option" min="1" max="4" required>

        <input type="submit" value="Add Question">
    </form>

</body>
</html>