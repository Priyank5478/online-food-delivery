<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>ALL Delivery Partner</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .text-success {
            color: green;
        }

        .text-warning {
            color: orange;
        }

        .text-danger {
            color: red;
        }
    </style>
</head>

<body class="fix-header fix-sidebar">

    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">

        <?php include 'header.php'; ?>
        <?php include './sidebar.php' ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">ALL Delivery Partner</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="example23"
                                        class="display nowrap table table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM delivery_partners ORDER BY id DESC";
                                            $query = mysqli_query($db, $sql);
                                            foreach ($query as $partner): ?>
                                                <tr>
                                                    <td><?php echo $partner['id']; ?></td>
                                                    <td><?php echo $partner['name']; ?></td>
                                                    <td><?php echo $partner['phone']; ?></td>
                                                    <td><?php echo $partner['email']; ?></td>
                                                    <td>
                                                        <?php
                                                        // Define status colors and icons based on the status
                                                        $status = $partner['status'];
                                                        if ($status == 'active') {
                                                            echo '<span class="text-success"><i class="fa fa-check-circle"></i> Active</span>';
                                                        } elseif ($status == 'inactive') {
                                                            echo '<span class="text-warning"><i class="fa fa-exclamation-circle"></i> Inactive</span>';
                                                        } elseif ($status == 'disabled') {
                                                            echo '<span class="text-danger"><i class="fa fa-times-circle"></i> Disabled</span>';
                                                        } else {
                                                            echo '<span>Unknown</span>'; // Fallback for unexpected statuses
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="delete_partner.php?id=<?php echo $partner['id']; ?>"
                                                            class="btn btn-danger btn-flat btn-addon btn-xs m-b-10"><i
                                                                class="fa fa-trash-o" style="font-size:16px"></i></a>
                                                        <a href="add_delivery_partners.php?id=<?php echo $partner['id']; ?>"
                                                            class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5"><i
                                                                class="fa fa-edit"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>

    <footer class="footer"> © 2024 - Online Food Ordering System </footer>

    </div>

    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
</body>

</html>