<?php
// Set the correct timezone for consistent time display
date_default_timezone_set('Asia/Kolkata');

// Database connection details
$connection = new mysqli('localhost', 'root', '', 'quiz');
if ($connection->connect_error) {
    // If connection fails, stop execution and show error
    die("Connection failed: " . $connection->connect_error);
}

// ==========================================================
// ✅ FIX: Function must be defined before it is called.
// ==========================================================
// Function to convert duration seconds into a readable format (H hr, M min, S sec)
function secondsToDurationDisplay($seconds) {
    // Ensure duration is non-negative and numeric
    if (!is_numeric($seconds) || $seconds <= 0) {
        return '0 sec';
    }
    
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);
    $s = $seconds % 60;
    
    $parts = [];
    if ($h > 0) $parts[] = $h . " hr";
    if ($m > 0) $parts[] = $m . " min";
    
    // Always display seconds if no other unit is displayed, or if seconds > 0
    if ($s > 0 || empty($parts)) $parts[] = $s . " sec";

    return implode(", ", $parts);
}
// ==========================================================


// SQL Query to fetch the latest quiz timing for each user
// It uses MAX(id) to ensure only the most recent attempt's timing is shown.
$sql = "SELECT u.id, u.name, t.start_time, t.end_time
        FROM user u
        LEFT JOIN timing t ON t.id = (
            SELECT MAX(id) FROM timing WHERE user_id = u.id
        )
        ORDER BY u.id ASC";

$result = $connection->query($sql);

if (!$result) {
    // If query fails, stop execution and show MySQL error
    die("Query failed: " . $connection->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Quiz Timing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { padding: 20px; background-color: #f0f2f5; font-family: Arial, sans-serif; }
        .container { max-width: 1000px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h3 { color: #333; margin-bottom: 25px; font-weight: bold; }
        table.table { background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        table th { background-color: #343a40; color: #fff; font-weight: 600; border: none; text-transform: uppercase; font-size: 14px; }
        table td { padding: 12px; vertical-align: middle; color: #555; font-size: 14px; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: #f9f9f9; }
        .badge-success { background-color: #28a745; font-size: 13px; color: #fff; }
        .badge-warning { background-color: #ffc107; font-size: 13px; color: #333; }
        .badge-secondary { background-color: #6c757d; font-size: 13px; color: #fff; }
        .btn-sm { font-size: 13px; padding: 4px 10px; }
        .btn-success, .btn-danger { margin: 2px; }
    </style>
</head>
<body>
<div class="container">
    <h3>User List & Quiz Timing</h3>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Quiz Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Duration (seconds)</th>
                <th>Duration Display</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Fetch times from the database
                $start_time = $row['start_time'];
                $end_time = $row['end_time'];

                // Initialize variables for display
                $date_display = '-';
                $start_display = '-';
                $end_display = '-';
                $duration_seconds = '-';
                $duration_display = '-';
                $status = "Not Attempted";

                if (!empty($start_time)) {
                    $start_ts = strtotime($start_time); // Convert datetime string to timestamp

                    // Display Date (e.g., 6 October 2025)
                    $date_display = date("j F Y", $start_ts);

                    // Display Start Time (e.g., 10:19:00 AM)
                    $start_display = date("h:i:s A", $start_ts);
                    $status = "In Progress"; 

                    if (!empty($end_time)) {
                        $end_ts = strtotime($end_time);
                        
                        // Display End Time (e.g., 10:20:00 AM)
                        $end_display = date("h:i:s A", $end_ts);

                        // --- Calculate Duration ---
                        $duration = $end_ts - $start_ts;
                        if ($duration < 0) {
                            $duration = 0;
                        }
                        
                        $duration_seconds = $duration;
                        // Line 79: Call the now defined function
                        $duration_display = secondsToDurationDisplay($duration);
                        
                        $status = "Completed";
                    } 
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($date_display) ?></td>
                    <td><?= htmlspecialchars($start_display) ?></td>
                    <td><?= htmlspecialchars($end_display) ?></td>
                    <td><?= htmlspecialchars($duration_seconds) ?></td>
                    <td><?= htmlspecialchars($duration_display) ?></td>
                    <td>
                        <?php if ($status === 'Completed'): ?>
                            <span class="badge badge-success"><?= $status ?></span>
                        <?php elseif ($status === 'In Progress'): ?>
                            <span class="badge badge-warning"><?= $status ?></span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?= $status ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="delete_user.php?user_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>

                <?php
            }
        } else {
            ?>
            <tr><td colspan="9" class="text-center">No users found.</td></tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <div class="mt-3">
        <a href="add_user.php" class="btn btn-success btn-sm">Add New User</a>
    </div>
</div>
</body>
</html>