<?php
// Database Connection
$connection = new mysqli('localhost', 'root', '', 'quiz');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// SQL ક્વેરી: id, name, password, અને created_at કૉલમ્સ ખેંચો
$sql = "SELECT id, name, password, created_at FROM user ORDER BY id ASC";
$result = $connection->query($sql);

if (!$result) {
    die("Query failed: " . $connection->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { padding: 20px; }
        table th, table td { text-align: center; vertical-align: middle; }
        .text-nowrap { white-space: nowrap; }
        .password-cell { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
<div class="container-fluid">
    <h3 class="mb-4">User List</h3>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">Name</th>
                    <th style="width: 25%;">Password (Hashed)</th>
                    <th style="width: 15%;">Date Added</th> <th style="width: 15%;">Time Added</th> <th style="width: 20%;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): 
                    // created_at માંથી તારીખ અને સમયને અલગથી ખેંચો
                    $timestamp = strtotime($row['created_at']);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        
                        <td class="password-cell">
                             <?= htmlspecialchars(substr($row['password'], 0, 20)) . (strlen($row['password']) > 20 ? '...' : '') ?>
                        </td>
                        
                        <td class="text-nowrap">
                            <?= date('d-M-Y', $timestamp) ?>
                        </td>
                        
                        <td class="text-nowrap">
                            <?= date('h:i:s A', $timestamp) ?>
                        </td>
                        
                        <td>
                            <a href="delete_user.php?user_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No users found.</td></tr> <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="add_user.php" class="btn btn-success">Add New User</a>
</div>
</body>
</html>