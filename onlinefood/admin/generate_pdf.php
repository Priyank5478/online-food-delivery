<?php
require '../vendor/autoload.php';

session_start();

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../connection/connect.php");

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$userId = 1;

if (isset($_GET['order_ids'])) {
    $orderIds = explode(',', $_GET['order_ids']);
    $orderIds = array_filter($orderIds, 'is_numeric');

    if (!empty($orderIds)) {
        $orderIdsStr = implode(',', $orderIds);
        $SQL = "SELECT * FROM users_orders WHERE o_id IN ($orderIdsStr)";
        $result = mysqli_query($db, $SQL);

        if ($result && mysqli_num_rows($result) > 0) {
            $orderItemsHtml = '';
            $subtotal = 0;

            while ($order = mysqli_fetch_assoc($result)) {
                $totalPrice = $order['quantity'] * $order['price'];
                $subtotal += $totalPrice;
                $userId = $order['u_id'];
                $restaurantId = $order['res_id'];
                $orderId = $order['o_id'];

                $orderItemsHtml .= "<tr>
                    <td>{$order['title']}</td>
                    <td>{$order['quantity']}</td>
                    <td>" . number_format($order['price'], 2) . "</td>
                    <td>" . number_format($totalPrice, 2) . "</td>
                </tr>";
            }

            $userSQL = "SELECT * FROM users WHERE u_id = $userId";
            $userResult = mysqli_query($db, $userSQL);
            $user = mysqli_fetch_assoc($userResult);

            $restaurantSQL = "SELECT * FROM restaurant WHERE rs_id = $restaurantId";
            $restaurantResult = mysqli_query($db, $restaurantSQL);
            $restaurant = mysqli_fetch_assoc($restaurantResult);

            $total = $subtotal;
            $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Order Receipt</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        color: #333;
                        background-color: #f4f6f9;
                        margin: 0;
                        padding: 0;
                    }
                    .receipt {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #fff;
                        border-radius: 10px;
                        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
                        padding: 30px;
                    }
                    .header {
                        background-color: #4CAF50;
                        color: #fff;
                        padding: 20px;
                        text-align: center;
                        border-top-left-radius: 10px;
                        border-top-right-radius: 10px;
                    }
                    .header h1 {
                        font-size: 28px;
                        margin: 0;
                    }
                    .header p {
                        font-size: 14px;
                        color: #e8e8e8;
                    }
                    .section {
                        margin-bottom: 20px;
                    }
                    .section h2 {
                        font-size: 20px;
                        color: #333;
                        border-bottom: 2px solid #4CAF50;
                        padding-bottom: 8px;
                        margin-bottom: 15px;
                    }
                    .section p {
                        margin: 5px 0;
                        font-size: 14px;
                        color: #555;
                    }
                    .table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                    }
                    .table th, .table td {
                        padding: 12px;
                        border: 1px solid #ddd;
                        text-align: left;
                        font-size: 14px;
                    }
                    .table th {
                        background-color: #f9f9f9;
                        font-weight: bold;
                        color: #4CAF50;
                    }
                    .totals {
                        text-align: right;
                        font-size: 18px;
                        color: #333;
                        font-weight: bold;
                        margin-top: 20px;
                    }
                    .totals p {
                        margin: 0;
                    }
                    .footer {
                        background-color: #f2f2f2;
                        text-align: center;
                        margin-top: 30px;
                        color: #777;
                        font-size: 12px;
                        padding: 15px;
                        border-bottom-left-radius: 10px;
                        border-bottom-right-radius: 10px;
                    }
                    .footer p {
                        margin: 5px 0;
                    }
                    .uppercase {
                        text-transform: uppercase;
                    }
                    .clblack {
                        color: black !important;
                    }
                </style>
            </head>
            <body>
                <div class="receipt">
                    <div class="header">
                        <h1>Order Receipt</h1>
                        <p>Thank you for order, <span class="uppercase">' . $user["f_name"] . '</span>! </p>
                        <p> Order Date : ' . $user["date"] . '</p>
                    </div>
                    <div class="section">
                        <h2>Customer Info</h2>
                        <p class="clblack">Name : <span class="uppercase">' . $user["f_name"] . ' ' . $user["l_name"] . '</span></p>
                        <p class="clblack">Address : ' . $user["address"] . '</p>
                        <p class="clblack">Mobile No : ' . $user["phone"] . '</p>
                    </div>
                    <div class="section">
                        <h2>Restaurant Info</h2>
                        <p class="clblack">Name : ' . $restaurant["title"] . '</p>
                        <p class="clblack">E-Mail : ' . $restaurant["email"] . '</p>
                        <p class="clblack">Address : ' . $restaurant["address"] . '</p>
                        <p class="clblack">Mobile No : ' . $restaurant["phone"] . '</p>
                    </div>
                    <div class="section">
                        <h2>Order Summary</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>'.
                                $orderItemsHtml.'
                            </tbody>
                        </table>
                    </div>
                    <div class="section totals">
                        <p>Total: ' . number_format($total, 2) . '</p>
                    </div>
                    <div class="footer">
                        <p><b>O.F.O.S</b></p>
                        <p>108, Avlone Ved Road, Katargam, Surat</p>
                        <p>If you have any questions, feel free to contact us :</p>
                        <p>Phone: 9988776655</p>
                        <p>&copy; Copyright 2024 BY O.F.O.S </p>
                    </div>
                </div>
            </body>
            </html>
            ';

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            // $dompdf->stream("Order_Receipt.pdf", ["Attachment" => false]);
            $pdfFilePath = 'receipt_' . time() . '.pdf';
            file_put_contents($pdfFilePath, $dompdf->output());

            $mail = new PHPMailer(true);

            $email = ""; // replace with your email
            $password = ""; // replace with your password

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.hostinger.com'; // Use Gmail SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'priyank.lathiya@marelab.in'; // Replace with your Gmail address
                $mail->Password = 'Priyank@9898';        // Replace with your Gmail password or app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
            
                // Recipients
                $mail->setFrom('priyank.lathiya@marelab.in', 'O.F.O.S');

                $userSQL = "SELECT * FROM users WHERE u_id = $userId";
                $userResult = mysqli_query($db, $userSQL);
                $user = mysqli_fetch_assoc($userResult);
                $mail->addAddress($user['email'], $user['f_name']);
                $mail->isHTML(true);
                $mail->Subject = 'Your Order Receipt';
                $mail->Body = "<!DOCTYPE html>
                                <html>
                                <head>
                                <style>
                                    body {
                                    font-family: Arial, sans-serif;
                                    color: #333;
                                    margin: 0;
                                    padding: 0;
                                    }
                                    .container {
                                    width: 100%;
                                    max-width: 600px;
                                    margin: auto;
                                    border: 1px solid #ddd;
                                    border-radius: 8px;
                                    overflow: hidden;
                                    background-color: #f9f9f9;
                                    }
                                    .header {
                                    background-color: #4CAF50;
                                    padding: 20px;
                                    text-align: center;
                                    color: white;
                                    font-size: 24px;
                                    }
                                    .content {
                                    padding: 20px;
                                    line-height: 1.6;
                                    }
                                    .content p {
                                    margin: 0 0 1em;
                                    }
                                    .content .invoice-details {
                                    border: 1px solid #ddd;
                                    padding: 10px;
                                    margin-top: 10px;
                                    background-color: #fff;
                                    border-radius: 5px;
                                    }
                                    .footer {
                                    padding: 10px;
                                    text-align: center;
                                    font-size: 12px;
                                    color: #777;
                                    }
                                </style>
                                </head>
                                <body>
                                <div class='container'>
                                    <div class='header'>
                                    Your Invoice is Ready!
                                    </div>
                                    <div class='content'>
                                    <p>Dear <span class='uppercase'>" . $user["f_name"] . " " . $user["l_name"] . "</span>,</p>
                                    <p>Thank you for your purchase! Please find the attached invoice for your recent transaction.</p>
                                    <div class='invoice-details'>
                                        <p><strong>Invoice Number:</strong> #000" . $orderId . "</p>
                                        <p><strong>Date & Time:</strong> " . $user["date"] . "</p>
                                        <p><strong>Total Amount:</strong> " . number_format($total, 2) . "</p>
                                    </div>
                                    <p style='margin-top: 8px;'>If you have any questions, feel free to contact us.</p>
                                    </div>
                                    <div class='footer'>
                                    <p>&copy; Copyright 2024 BY O.F.O.S </p>.
                                    </div>
                                </div>
                                </body>
                                </html>";


                $mail->addAttachment($pdfFilePath);

                $mail->send();
                echo "Email has been sent with the receipt.";

                unlink($pdfFilePath);
            } catch (Exception $e) {
                echo "Email could not be sent. Please contact +91 9988776655";
            }
        } else {
            echo "No orders found.";
        }
    } else {
        echo "Invalid order IDs.";
    }
} else {
    echo "No order IDs provided in the URL.";
}
