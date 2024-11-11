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
    // Fetch the delivery partner details
    $stmt = $pdo->prepare("SELECT * FROM delivery_partners WHERE id = ?");
    $stmt->execute([$partner_id]);
    $partner = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$partner) {
        echo "Delivery partner not found.";
        exit;
    }

    // Handle the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? $partner['name'];
        $phone = $_POST['phone'] ?? $partner['phone'];
        $email = $_POST['email'] ?? $partner['email'];

        // Update the partner in the database
        $sql = "UPDATE delivery_partners SET name = ?, phone = ?, email = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$name, $phone, $email, $partner_id]);
            echo "Partner details updated successfully.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "Invalid delivery partner ID.";
    exit;
}
?>

<!-- HTML form for editing the partner -->
<form action="" method="POST">
    <label for="name">Partner Name:</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($partner['name']); ?>" required>

    <label for="phone">Phone:</label>
    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($partner['phone']); ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($partner['email']); ?>" required>

    <button type="submit">Update Partner</button>
</form>
