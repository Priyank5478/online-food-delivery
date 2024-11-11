<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if (empty($_SESSION["adm_id"])) {
    header('location:index.php');
} else {
    $data = [];
?>

    <head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Admin Panel</title>
        <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="css/helper.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/dashbord.css" rel="stylesheet">
    </head>

    <body class="fix-header">

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
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Admin Dashbord</h4>
                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-home f-s-40 "></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from restaurant";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Restaurants</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-cutlery f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from dishes";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Dishes</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-users f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from users";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Users</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-shopping-cart f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from users_orders";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);
                                                    $data['total_order'] = $rws;
                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Total Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-th-large f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from res_category";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Restro Categories</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-spinner f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from users_orders WHERE status = 'in process' ";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Processing Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-check f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from users_orders WHERE status = 'closed' ";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);
                                                    $data['total_deliver'] = $rws;
                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Delivered Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-times f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from users_orders WHERE status = 'rejected' ";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);
                                                    $data['total_cancel'] = $rws;
                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Cancelled Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-rupee f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php
                                                    $result = mysqli_query($db, 'SELECT SUM(price) AS value_sum FROM users_orders WHERE status = "closed"');
                                                    $row = mysqli_fetch_assoc($result);
                                                    $sum = $row['value_sum'];
                                                    $data['total_erning'] = $sum;
                                                    echo $sum;
                                                    ?></h2>
                                                <p class="m-b-0">Total Earnings</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h1 class="text-center">Admin Chart Analysis</h1>

                <div class="dashboard-container">
                    <!-- Total Earnings -->
                    <div class="dashboard-item">
                        <canvas id="earningsChart"></canvas>
                        <p><i class="fas fa-dollar-sign"></i> Total Earnings</p>
                    </div>

                    <!-- Total Orders -->
                    <div class="dashboard-item">
                        <canvas id="ordersChart"></canvas>
                        <p><i class="fas fa-shopping-cart"></i> Total Orders</p>
                    </div>

                    <!-- Delivered Orders -->
                    <div class="dashboard-item">
                        <canvas id="deliveredChart"></canvas>
                        <p><i class="fas fa-check"></i> Delivered Orders</p>
                    </div>

                    <!-- Cancelled Orders -->
                    <div class="dashboard-item">
                        <canvas id="cancelledChart"></canvas>
                        <p><i class="fas fa-times"></i> Cancelled Orders</p>
                    </div>
                </div>

                <?php
                // Sample data - Replace with actual database queries or dynamic values
                $totalEarnings = $data['total_erning'];
                $totalOrders =  $data['total_order'];
                $deliveredOrders = $data['total_deliver'];
                $cancelledOrders = $data['total_cancel'];
                ?>

                <script>
                    console.log(<?= $data['total_cancel'] ?>)
                    // Fetch PHP variables into JavaScript
                    const totalEarnings = <?php echo $totalEarnings; ?>;
                    const totalOrders = <?php echo $totalOrders; ?>;
                    const deliveredOrders = <?php echo $deliveredOrders; ?>;
                    const cancelledOrders = <?php echo $cancelledOrders; ?>;

                    // Common chart configuration
                    const config = {
                        type: 'pie',
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            size: 12
                                        },
                                        padding: 15
                                    }
                                }
                            }
                        }
                    };

                    // Total Earnings Chart
                    new Chart(document.getElementById('earningsChart'), {
                        ...config,
                        data: {
                            labels: ['Earnings', 'Remaining'],
                            datasets: [{
                                data: [totalEarnings, 100 - totalEarnings],
                                backgroundColor: ['#4CAF50', '#e0e0e0'],
                                hoverBackgroundColor: ['#4CAF50', '#e0e0e0']
                            }]
                        }
                    });

                    // Total Orders Chart
                    new Chart(document.getElementById('ordersChart'), {
                        ...config,
                        data: {
                            labels: ['Orders', 'Remaining'],
                            datasets: [{
                                data: [totalOrders, 100 - totalOrders],
                                backgroundColor: ['#2196F3', '#e0e0e0'],
                                hoverBackgroundColor: ['#2196F3', '#e0e0e0']
                            }]
                        }
                    });

                    // Delivered Orders Chart
                    new Chart(document.getElementById('deliveredChart'), {
                        ...config,
                        data: {
                            labels: ['Delivered', 'Remaining'],
                            datasets: [{
                                data: [deliveredOrders, totalOrders - deliveredOrders],
                                backgroundColor: ['#4CAF50', '#e0e0e0'],
                                hoverBackgroundColor: ['#4CAF50', '#e0e0e0']
                            }]
                        }
                    });

                    // Cancelled Orders Chart
                    new Chart(document.getElementById('cancelledChart'), {
                        ...config,
                        data: {
                            labels: ['Cancelled', 'Remaining'],
                            datasets: [{
                                data: [cancelledOrders, totalOrders - cancelledOrders],
                                backgroundColor: ['#f44336', '#e0e0e0'],
                                hoverBackgroundColor: ['#f44336', '#e0e0e0']
                            }]
                        }
                    });
                </script>
                <script src="js/lib/jquery/jquery.min.js"></script>
                <script src="js/lib/bootstrap/js/popper.min.js"></script>
                <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
                <script src="js/jquery.slimscroll.js"></script>
                <script src="js/sidebarmenu.js"></script>
                <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
                <script src="js/custom.min.js"></script>

    </body>

</html>
<?php
}
?>