<?php
// ડેટાબેઝ કનેક્શન સેટિંગ્સ
$servername = "localhost"; 
$username = "root";       
$password = "";           
$dbname = "quiz";         

// ડેટાબેઝ સાથે જોડાણ (Connection) સ્થાપિત કરવું
$connection = mysqli_connect($servername, $username, $password, $dbname);

// જોડાણ (Connection) નિષ્ફળ જાય તો તપાસ કરવી
if (!$connection) {
    // જો કનેક્શન ન થાય, તો ભૂલ બતાવીને અટકી જવું
    die("DB Connection failed: " . mysqli_connect_error());
}

// 1. URL માંથી Score ID (id) મેળવવો
// આપણે user_results.php માંથી id=XXX મોકલીએ છીએ
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    // સુરક્ષા માટે ઇનપુટ સાફ કરવું (SQL Injection થી બચવા)
    $score_id_to_delete = mysqli_real_escape_string($connection, $_GET['id']);
    
    // 2. Score ડિલીટ કરવા માટેની SQL ક્વેરી
    // આપણે માત્ર scores ટેબલમાંથી એન્ટ્રી ડિલીટ કરી રહ્યા છીએ
    $sql = "DELETE FROM scores WHERE id = '$score_id_to_delete'";
    
    if (mysqli_query($connection, $sql)) {
        // સફળતાપૂર્વક ડિલીટ થયા પછી user_results.php પર રીડાયરેક્ટ કરો
        header("Location: user_results.php?delete_status=success");
        exit();
    } else {
        // જો ડિલીટ કરવામાં ભૂલ આવે તો
        header("Location: user_results.php?delete_status=error&msg=" . urlencode("Delete failed: " . mysqli_error($connection)));
        exit();
    }
} else {
    // જો URL માં id પેરામીટર ન હોય તો
    header("Location: user_results.php?delete_status=error&msg=" . urlencode("Invalid score ID provided."));
    exit();
}

// ડેટાબેઝ કનેક્શન બંધ કરવું
mysqli_close($connection);
?>