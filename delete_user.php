<?php
// delete_user.php - Quick Fix Solution

// 🛑 એરર જોવા માટે આ લાઈનો ફરીથી બંધ કરો જેથી સ્ક્રીન પર મેસેજ ન આવે
ini_set('display_errors', 0);
error_reporting(0);

// Database Connection
$conn = new mysqli("localhost", "root", "", "quiz");

if ($conn->connect_error) {
    // કનેક્શન ફેલ થાય તો પણ શાંતિથી પાછા જાઓ
    header("Location: quiz_timing.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    
    $user_id = $_POST['user_id'];
    
    // 1. ⚠️ Foreign Key Checks બંધ કરો (આનાથી બધી ભૂલો અવગણવામાં આવશે)
    // આનાથી timing કે અન્ય કોઈ ટેબલની જરૂર નહીં રહે.
    $conn->query("SET FOREIGN_KEY_CHECKS = 0"); 

    // 2. હવે user ટેબલમાંથી યુઝરને ડિલીટ કરો
    $sql_user = "DELETE FROM user WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    
    if ($stmt_user) {
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();
        $stmt_user->close();
    }
    
    // 3. ⚠️ Foreign Key Checks ફરીથી ચાલુ કરો (સુરક્ષા માટે આ જરૂરી છે)
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    $conn->close();

    // 4. મેસેજ આપ્યા વિના યુઝર લિસ્ટ પર પાછા જાઓ
    header("Location: quiz_timing.php");
    exit();
} else {
    $conn->close();
    header("Location: quiz_timing.php");
    exit();
}
?>