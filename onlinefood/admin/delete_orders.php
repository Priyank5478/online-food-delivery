<?php
include("../connection/connect.php"); //connection to db
require '../backend.php';
$emailSenderCancel = new EmailSender();
$emailSenderCancel->ordeRejected($_GET['order_del'], $db);
error_reporting(0);
session_start();


// sending query
mysqli_query($db, "DELETE FROM users_orders WHERE o_id = '" . $_GET['order_del'] . "'");
header("location:all_orders.php");
