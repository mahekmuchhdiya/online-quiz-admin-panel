<?php
// add_user.php

// ⚠️ Database Connection
$connection = new mysqli('localhost', 'root', '', 'quiz');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$message = '';
$name = '';    

// 1. ચેક કરો કે ફોર્મ POST થયું છે કે નહીં
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. POST ડેટા મેળવો
    $name = trim($_POST['name']);
    $password_raw = $_POST['password']; // 🔑 અહીં પાસવર્ડ સાદો છે
    
    // ડેટા Validation
    if (empty($name) || empty($password_raw)) {
        $message = '<div class="alert alert-danger">Please fill in all fields.</div>';
    } else {
        
        // 🔑 પાસવર્ડ હેશિંગનો કોડ અહીંથી દૂર કરવામાં આવ્યો છે
        $password_to_save = $password_raw;
        
        // 4. SQL ક્વેરી: યુઝરને ડેટાબેઝમાં દાખલ કરો
        $sql = "INSERT INTO user (name, password, created_at) VALUES (?, ?, NOW())";
        
        // Prepared Statement નો ઉપયોગ કરો (સુરક્ષા માટે)
        $stmt = $connection->prepare($sql);
        
        if ($stmt === false) {
            die('MySQL prepare error: ' . $connection->error);
        }
        
        // 5. bind_param માં સાદા પાસવર્ડને Bind કરો
        $stmt->bind_param("ss", $name, $password_to_save);
        
        // 6. Execute કરો
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">User **' . htmlspecialchars($name) . '** added successfully!</div>';
            $name = ''; 
        } else {
            $message = '<div class="alert alert-danger">Error adding user: ' . $stmt->error . '</div>';
        }

        $stmt->close();
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { padding: 20px; background-color: #f8f9fa; }
        .container { max-width: 600px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <h3 class="mb-4 text-center">Add New User</h3>
    
    <?= $message ?>
    
    <form method="post" action="add_user.php">
        <div class="form-group">
            <label for="name">User Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-success btn-block mt-4">Add User</button>
        <a href="user_list.php" class="btn btn-secondary btn-block mt-2">Back to User List</a>
    </form>
</div>
</body>
</html>