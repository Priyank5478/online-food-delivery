<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust this path if you're not using Composer
// include("../connection/connect.php");

class EmailSender extends PHPMailer
{

    public function __construct($exceptions = true)
    {
        parent::__construct($exceptions);

        // SMTP Configuration
        $this->isSMTP();
        $this->Host = 'smtp.hostinger.com';
        $this->SMTPAuth = true;
        $this->Username = 'priyank.lathiya@marelab.in'; // Replace with your email
        $this->Password = 'Priyank@9898'; // Replace with your email password or app password
        $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->Port = 465;
    }

    public function sendEmail($bodyContent, $email, $subject)
    {
        try {
            // Set recipient and content
            $this->setFrom('priyank.lathiya@marelab.in', 'O.F.O.S');
            $this->addAddress($email, $_SESSION['user_name'] ?? 'Customer');

            // Email content
            $this->isHTML(true);
            $this->Subject = $subject;
            $this->Body = $bodyContent;

            // Send the email
            $this->send();
            $email = "";
            return "Email has been sent with the receipt.";
        } catch (Exception $e) {
            return "Email could not be sent. Please contact +91 9988776655. Error: {$this->ErrorInfo}";
        }
    }

    public function orderTracking($email)
    {
        // Generate a random tracking number
        $trackingNumber = mt_rand(10000000000000, 99999999999999); // 14-digit random number

        // Create the tracking number string with a "#" prefix
        $trackingNumberHtml = "#" . $trackingNumber;

        // HTML email content
        // $emailSubject = "Your Order Tracking Number";
        $emailContent = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f9f9f9;
                }
                .header {
                    background-color: #4CAF50;
                    padding: 20px;
                    text-align: center;
                    color: white;
                    font-size: 24px;
                }
                .email-container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                .email-header {
                    text-align: center;
                    padding-bottom: 20px;
                }
                .email-header h1 {
                    color: #333;
                    font-size: 24px;
                    margin: 0;
                }
                .email-body {
                    font-size: 16px;
                    color: #555;
                    line-height: 1.6;
                    margin: 20px 0;
                }
                .tracking-number {
                    font-size: 18px;
                    font-weight: bold;
                    color: #4CAF50;
                    background-color: #f1f1f1;
                    padding: 10px;
                    border-radius: 5px;
                    text-align: center;
                    display: inline-block;
                }
                .footer {
                    text-align: center;
                    font-size: 14px;
                    color: #888;
                    margin-top: 40px;
                }
                .footer a {
                    color: #4CAF50;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header header'>
                    <h1 style='color:#fff;'>Your Order Tracking Number</h1>
                </div>
                <div class='email-body'>
                    <p>Dear " . strtoupper($_SESSION['user_name']) . "</p>
                    <p>Thank you for shopping with us! Your order has been processed, and your tracking number is ready.</p>
                    <p>Use the tracking number below to track your order:</p>
                    <div style='text-align: center;'><p class='tracking-number'>$trackingNumberHtml</p></div>
                    <p>If you have any questions or need further assistance, feel free to :9988776655</p>
                </div>
                <div class='footer'>
                    <p>Thank you for choosing us!<br>Visit our website: <a href='https://www.OFOS.com'>www.O.F.O.S.com</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
        $subject = "Your Order Tracking Number";
        // Send the email
        return $this->sendEmail($emailContent, $email, $subject);
    }

    public function getUserData($formId, $db)
    {
        $SQL = "SELECT * FROM users_orders WHERE o_id  = $formId";
        $result = mysqli_query($db, $SQL);
        $order = mysqli_fetch_assoc($result);

        $userSQL = "SELECT * FROM users WHERE u_id = " . $order['u_id'] . "";
        $userResult = mysqli_query($db, $userSQL);
        $user = mysqli_fetch_assoc($userResult);
        $user[] = $order;
        $masterData['userdata'] = $user;
        $masterData['orderdata'] = $order;
        return $masterData;
    }
    public function ordeProcessing($formId, $db)
    {
        $userData = $this->getUserData($formId, $db);
        $otp = rand(100000, 999999);
        $sql = "UPDATE users_orders SET o_otp = $otp WHERE o_id = $formId";
        $userResult = mysqli_query($db, $sql);
        $body = "<!DOCTYPE html>
            <html>
            <head>
            <style>
                body {
                font-family: Arial, sans-serif;
                background-color: #f4f6f9;
                color: #333;
                margin: 0;
                padding: 0;
                }
                .container {
                width: 100%;
                max-width: 600px;
                margin: 20px auto;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
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
                font-size: 16px;
                line-height: 1.6;
                color: #555;
                }
                .otp-code {
                font-size: 32px;
                font-weight: bold;
                color: #4CAF50;
                text-align: center;
                margin: 20px 0;
                }
                .message {
                text-align: center;
                font-size: 14px;
                color: #777;
                margin-top: 20px;
                }
                .footer {
                padding: 15px;
                text-align: center;
                background-color: #f2f2f2;
                color: #999;
                font-size: 12px;
                }
            </style>
            </head>
            <body>
            <div class='container'>
                <div class='header'>
                OTP Verification
                </div>
                <div class='content'>
                <p>Dear " . $userData['userdata']['f_name'] . ",</p>
                <p>To complete your verification, please use the following OTP:</p>
                <div class='otp-code'>" . $otp . "</div> <!-- Replace with dynamic OTP -->
                <p>Please do not share this code with anyone. It will expire in 10 minutes.</p>
                </div>
                <div class='message'>
                If you did not request this code, please ignore this email or contact our support.
                </div>
                <div class='footer'>
                <p>&copy; Copyright 2024 BY O.F.O.S</p>
                </div>
            </div>
            </body>
            </html>
            ";
        $subject = "One-Time-Password For Your Order";
        return $this->sendEmail($body, $userData['userdata']['email'], $subject);
    }
    public function ordeRejected($formId, $db)
    { 
        $userData = $this->getUserData($formId, $db);
        $body = "<!DOCTYPE html>
            <html>
            <head>
            <style>
                body {
                font-family: Arial, sans-serif;
                background-color: #f4f6f9;
                color: #333;
                margin: 0;
                padding: 0;
                }
                .container {
                width: 100%;
                max-width: 600px;
                margin: 20px auto;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                }
                .header {
                background-color: #d9534f;
                padding: 20px;
                text-align: center;
                color: white;
                font-size: 24px;
                }
                .content {
                padding: 20px;
                font-size: 16px;
                line-height: 1.6;
                color: #555;
                }
                .order-info {
                border-top: 1px solid #ddd;
                border-bottom: 1px solid #ddd;
                padding: 10px 0;
                margin: 15px 0;
                font-size: 14px;
                color: #777;
                }
                .footer {
                padding: 15px;
                text-align: center;
                background-color: #f2f2f2;
                color: #999;
                font-size: 12px;
                }
            </style>
            </head>
            <body>
            <div class='container'>
                <div class='header'>
                Order Cancellation Notice
                </div>
                <div class='content'>
                <p>Dear " . $userData['userdata']['f_name'] . " " . $userData['userdata']['l_name'] . " ,</p>
                <p>We regret to inform you that your recent order has been cancelled. We apologize for any inconvenience caused.</p>
                <div class='order-info'>
                    <p><strong>Order Number:</strong> #000" . $userData['orderdata']['o_id'] . "</p> <!-- Replace with dynamic order number -->
                    <p><strong>Order Date & Time :</strong> " . $userData['orderdata']['date'] . "</p> <!-- Replace with dynamic order date -->
                </div>
                <p>If you have any questions or require further assistance, please feel free to contact our support team.</p>
                </div>
                <div class='footer'>
                    <p>&copy; Copyright 2024 BY O.F.O.S </p>
                </div>
            </div>
            </body>
            </html>
            ";
        $subject = "Your Order IS Cancelled";
        mysqli_query($db,"DELETE FROM users_orders WHERE o_id = '".$formId."'");
        return $this->sendEmail($body, $userData['userdata']['email'], $subject);
    }
    public function deliveriBoy($db, $id, $formId)
    {
        $userSQL = "SELECT * FROM delivery_partners  WHERE id = $id";
        $result = mysqli_query($db, $userSQL);
        $deliveryBoyData = mysqli_fetch_assoc($result);

        $orderSQL = "SELECT * FROM users_orders WHERE o_id = $formId";
        $OrderResult = mysqli_query($db, $orderSQL);
        $orderData = mysqli_fetch_assoc($OrderResult);
        $totalAmount = $orderData['price'] * $orderData['quantity'];

        $resSQL = "SELECT * FROM restaurant WHERE rs_id = " . $orderData['res_id'] . "";
        $resResult = mysqli_query($db, $resSQL);
        $resData = mysqli_fetch_assoc($resResult);

        $uSQL = "SELECT * FROM users WHERE u_id = " . $orderData['u_id'] . "";
        $uResult = mysqli_query($db, $uSQL);
        $uData = mysqli_fetch_assoc($uResult);

        $subject = "Order ready for delivery.";
        $body = "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            color: #333;
                            background-color: #f4f6f9;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
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
                            color: #fff;
                        }
                        .content {
                            margin-bottom: 20px;
                        }
                        .content p {
                            font-size: 14px;
                            color: #555;
                            margin: 10px 0;
                        }
                        .order-summary {
                            background-color: #f9f9f9;
                            border-radius: 6px;
                            padding-left: 10px;
                            margin-top: 20px;
                        }
                        .order-summary h3 {
                            font-size: 20px;
                            color: #333;
                            border-bottom: 2px solid #4CAF50;
                            padding-bottom: 8px;
                            margin-bottom: 15px;
                        }
                        .order-summary p {
                            font-size: 14px;
                            color: #555;
                            margin: 5px 0;
                        }
                        .restaurant {
                            margin-top: 20px;
                            margin-bottom: 20px;
                            padding-left: 10px;
                        }
                        .restaurant h3 {
                            font-size: 20px;
                            color: #333;
                            border-bottom: 2px solid #4CAF50;
                            padding-bottom: 8px;
                            margin-bottom: 15px;
                        }
                        .restaurant p {
                            font-size: 14px;
                            color: #555;
                            margin: 5px 0;
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
                        .verify-customer {
                            text-align: center;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Order Update: On the Way</h1>
                        </div>

                        <div class='content'>
                            <p>Hi <strong>" . $deliveryBoyData['name'] . "</strong>,</p>
                            <p>Good news! The order has been marked as <strong>On the Way</strong> and is ready for delivery. Here are the details:</p>

                            <div class='order-summary'>
                                <h3>Order Summary</h3>
                                <p><strong>Order No:</strong> #000" . $orderData['o_id'] . "</p>
                                <p><strong>Status:</strong> On the Way</p>
                                <p><strong>Customer:</strong> " . $uData['f_name'] . " " . $uData['l_name'] . "</p>
                                <p><strong>Customer Address:</strong> " . $uData['address'] . "</p>
                                <p><strong>Estimated Delivery Time:</strong> Up To 45-min.</p>
                                <p><strong>Order Total:</strong> " . number_format($totalAmount, 2) . "</p>
                            </div>

                            <div class='restaurant'>
                                <h3>Restaurant Details</h3>
                                <p><strong>Name:</strong> " . $resData['title'] . "</p>
                                <p><strong>Address:</strong> " . $resData['address'] . "</p>
                                <p><strong>Phone:</strong> " . $resData['phone'] . "</p>
                            </div>

                            <p>Please ensure that you follow the directions carefully and deliver the order to the customer as soon as possible. If you encounter any issues, please don't hesitate to contact us.</p>
                            <p>Thank you for your excellent service! We're counting on you to complete this delivery successfully.</p>
                            <p>Best regards,</p>
                            <p><strong>O.F.O.S</strong> Team.</p>
                            <p class='verify-customer'><a href='http://localhost/onlinefood/otpverification.php?o_id=".$formId."'><strong>Click To Verify Customer</a></strong></p>
                        </div>

                        <div class='footer'>
                            <p><b>O.F.O.S</b></p>
                            <p>108, Avlone Ved Road, Katargam, Surat</p>
                            <p>If you have any questions, feel free to contact us:</p>
                            <p>Phone: 9988776655</p>
                            <p>&copy; Copyright 2024 BY O.F.O.S</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
        return $this->sendEmail($body, $deliveryBoyData['email'], $subject);
    }
}
