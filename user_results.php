<?php
session_start();

// ડેટાબેઝ કનેક્શન સેટિંગ્સ
$servername = "localhost"; 
$username = "root";       
$password = "";           
$dbname = "quiz";         

// ડેટાબેઝ સાથે જોડાણ (Connection)
$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("DB Connection failed: " . mysqli_connect_error());
}

// 3. ડેટાબેઝમાંથી ડેટા ખેંચવાની SQL ક્વેરી
$sql = "SELECT 
            r.id AS score_id,     
            r.score, 
            r.total_questions,
            r.created_at,        
            u.name AS user_name,
            u.id AS user_id       
        FROM scores r       
        JOIN user u ON r.user_id = u.id
        ORDER BY r.created_at ASC";

$results_data = mysqli_query($connection, $sql);

if (!$results_data) {
    die("Error fetching results: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Results | Quiz Admin</title>
    <link rel="stylesheet" href="dashbord.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .main { margin-left: 0; padding: 20px; background-color: #f8f9fa; width: 100%; min-height: 100vh; } 
        .container-fluid.mt-4 { background-color: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .table thead th { background-color: #343a40; color: white; font-size: 0.9rem; vertical-align: middle; text-align: center; }
        .table td { font-size: 0.9rem; vertical-align: middle; padding: 0.75rem; }
        .result-score { font-weight: bold; text-align: center; }
        h1, h2 { color: #343a40; margin-bottom: 20px; }
    </style>
</head>
<body>
    
<div class="main">
    
    <div class="container-fluid mt-9">
        <h2>Quiz Results</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;"> Name</th>
                        <th style="width: 8%;">Total Question</th>
                        <th style="width: 8%;">Right Answer</th>
                        <th style="width: 8%;">Wrog Answer</th>
                   
                        <th style="width: 13%;">Date</th> <th style="width: 10%;">Time</th>
                        <th style="width: 18%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    if (mysqli_num_rows($results_data) > 0) {
                        while($row = mysqli_fetch_assoc($results_data)):
                            $score = $row['score']; 
                            $total = $row['total_questions'];
                            
                            $wrong_answers = $total - $score;
                            
                            $percentage = ($total > 0) ? ($score / $total) * 100 : 0;
                            $percentage_formatted = number_format($percentage, 2) . "%";
                            
                            // તારીખ અને સમયને ચોક્કસ ફોર્મેટમાં અલગ કરો
                            $quiz_datetime = strtotime($row['created_at']);
                            // હંમેશા ચોક્કસ તારીખ બતાવવા માટે
                            $quiz_date = date('d-M-Y', $quiz_datetime); 
                            $quiz_time = date('h:i:s A', $quiz_datetime); 
                    ?>
                    <tr>
                        <td class="text-center"><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td class="text-center"><?= $total ?></td>
                        <td class="text-success result-score"><?= $score ?></td>
                        <td class="text-danger result-score"><?= $wrong_answers ?></td>
                       
                        <td class="text-nowrap text-center"><?= $quiz_date ?></td> <td class="text-nowrap text-center"><?= $quiz_time ?></td>
                        <td class="text-center">
                            <a href="delete_result.php?id=<?= $row['score_id'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this result?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    } else {
                    ?>
                    <tr>
                        <td colspan="9" class="text-center">કોઈ પરિણામ મળ્યું નથી.</td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
             <a href="admin_dashbord.php" class="btn btn-secondary">Go to Dashboard</a>
            
        </div>
    </div>
</div>

</body>
</html>