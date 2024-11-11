<?php
include("../connection/connect.php");
// db.php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=onlinefoodphp", "root", "");
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Get the partner ID from the URL
$partner_id = $_GET['id'] ?? null;

if ($partner_id) {
    // Delete the delivery partner from the database
    $stmt = $pdo->prepare("DELETE FROM delivery_partners WHERE id = ?");
    
    try {
        $stmt->execute([$partner_id]);
        echo "Partner deleted successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid delivery partner ID.";
}
?>
