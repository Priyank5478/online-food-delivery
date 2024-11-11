<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=onlinefoodphp", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check if we are editing an existing partner
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch the delivery partner data for the given ID
    $stmt = $pdo->prepare("SELECT * FROM delivery_partners WHERE id = ?");
    $stmt->execute([$id]);
    $partner = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    try {
        if (isset($id)) {
            // Update an existing delivery partner
            $stmt = $pdo->prepare("UPDATE delivery_partners SET name = ?, phone = ?, email = ?, status = ? WHERE id = ?");
            if ($stmt->execute([$name, $phone, $email, $status, $id])) {
                echo "<script>alert('Delivery partner updated successfully.');window.location.href='./all_delivery_partners.php'</script>";
            } else {
                echo "<script>alert('Error updating delivery partner.')</script>";
            }
        } else {
            // Add a new delivery partner
            $stmt = $pdo->prepare("INSERT INTO delivery_partners (name, phone, email, status) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $phone, $email, $status])) {
                echo "<script>alert('Delivery partner added successfully.');window.location.href='./all_delivery_partners.php'</script>";
            } else {
                echo "<script>alert('Error adding delivery partner.')</script>";
            }
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo isset($id) ? 'Edit Delivery Partner' : 'Add Delivery Partner'; ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
    <!-- Header and Sidebar Code -->
    <?php include 'header.php';?>
    <?php include './sidebar.php';?>
    <div id="main-wrapper">
        <!-- Other HTML structure code omitted for brevity -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <?php echo $error ?? ''; ?>
                <?php echo $success ?? ''; ?>

                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white"><?php echo isset($id) ? 'Edit Delivery Partner' : 'Add Delivery Partner'; ?></h4>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-body">
                                    <hr>
                                    <!-- Existing form fields -->
                                    <div class="row p-t-20">
                                        <!-- Name Field -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Name</label>
                                                <input type="text" name="name" class="form-control" value="<?php echo $partner['name'] ?? ''; ?>" required>
                                            </div>
                                        </div>
                                        <!-- Phone Field -->
                                        <div class="col-md-6">
                                            <div class="form-group has-danger">
                                                <label class="control-label">Phone</label>
                                                <input type="text" name="phone" class="form-control form-control-danger" value="<?php echo $partner['phone'] ?? ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Email Field -->
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo $partner['email'] ?? ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- New Status Field -->
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="active" <?php if (($partner['status'] ?? '') == 'active') echo 'selected'; ?>>Active</option>
                                                    <option value="inactive" <?php if (($partner['status'] ?? '') == 'inactive') echo 'selected'; ?>>Inactive</option>
                                                    <option value="disabled" <?php if (($partner['status'] ?? '') == 'disabled') echo 'selected'; ?>>Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <input type="submit" class="btn btn-primary" value="<?php echo isset($id) ? 'Update' : 'Save'; ?>">
                                    <a href="add_delivery_partners.php" class="btn btn-inverse">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <footer class="footer">© 2024 - Online Food Ordering System</footer>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
