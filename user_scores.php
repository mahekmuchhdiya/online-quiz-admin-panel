<?php
session_start();
// ડેટાબેઝ કનેક્શન
$connection = new mysqli('localhost', 'root', '', 'quiz');
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// ત્રણ ટેબલમાંથી ડેટા ખેંચવાની ક્વેરી
$sql = "
    SELECT 
        u.name AS user_name,  /* યુઝરનું નામ */
        s.score,              /* યુઝરનો સ્કોર */
        s.total_questions,    /* કુલ પ્રશ્નો */
        t.start_time,         /* શરૂઆતનો સમય */
        t.end_time            /* સમાપ્તિ સમય */
    FROM 
        scores s
    JOIN 
        user u ON s.user_id = u.id
    JOIN 
        timing t ON s.timing_id = t.id 
    ORDER BY 
        s.created_at DESC
";
$result = $connection->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>યુઝર પરિણામો | Admin Panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <style>
        /* તમારા એડમિન પેનલની સ્ટાઇલ અહીં મૂકો */
        .sidebar { /* ... તમારી sidebar style ... */ }
        .main { margin-left: 250px; padding: 20px; }
        table { width: 100%; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="user_results.php" class="active">યુઝરના પરિણામો</a></li>
            </ul>
    </div>

    <div class="main">
        <div class="topbar"><h1>યુઝર પરિણામો</h1></div>
        <div class="container-fluid">
            <h2>પૂર્ણ થયેલા ક્વિઝના માર્ક્સ</h2>
            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ક્રમ</th>
                        <th>યુઝરનું નામ</th>
                        <th>અંતિમ સ્કોર</th>
                        <th>ટકાવારી (%)</th>
                        <th>ક્વિઝ સમય</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $counter = 1;
                    while($row = $result->fetch_assoc()) {
                        // સમય ગણતરી (મિનિટ અને સેકન્ડમાં)
                        $time_taken = "N/A";
                        if ($row['end_time']) {
                            $start_ts = strtotime($row['start_time']);
                            $end_ts = strtotime($row['end_time']);
                            $diff_seconds = $end_ts - $start_ts;
                            $minutes = floor($diff_seconds / 60);
                            $seconds = $diff_seconds % 60;
                            $time_taken = "{$minutes} મિનિટ, {$seconds} સેકન્ડ";
                        }
                        
                        // ટકાવારી ગણતરી
                        $percentage = ($row['total_questions'] > 0) ? round(($row['score'] / $row['total_questions']) * 100) : 0;
                ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= htmlspecialchars($row["user_name"]) ?></td>
                            <td>**<?= htmlspecialchars($row["score"]) ?> / <?= htmlspecialchars($row["total_questions"]) ?>**</td>
                            <td><?= $percentage ?>%</td>
                            <td><?= $time_taken ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center">કોઈ પરિણામ સેવ થયેલું નથી.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
$connection->close();
?>
</body>
</html>