<?php
include("connection/connect.php");
// include_once 'product-action.php';
require 'backend.php';
$emailReSender = new EmailSender();
$message = '';

$form_id = $_GET['o_id'];
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['otp']) || isset($_POST['resend'])) {
    $otp = implode("", array_map('intval', $_POST['otp'])); // Convert OTP to a string of numbers

    if (isset($_POST['resend'])) {
        $emailReSender->ordeProcessing($form_id, $db); // Ensure $form_id is defined and passed correctly
        $message = "OTP resent successfully!";
        $_POST['resend'] = "";
    } elseif (isset($_POST['otp']) && !empty($_POST['otp'])) {
        // Verify the OTP
        $userSQL = "SELECT * FROM users_orders WHERE o_id = $form_id"; // Replace o_id with the correct order ID or user ID
        $userResult = mysqli_query($db, $userSQL);
        $result = mysqli_fetch_assoc($userResult);

        if ($result && $result['o_otp'] === $otp) {
            $message = "OTP verified successfully!";
            header("Location: admin/generate_pdf.php?order_ids=$form_id");
            $result['o_otp'] = "";
        } else {
            $message = "Invalid OTP. Please try again.";
            $result['o_otp'] = "";
        }
    }
} else {
    $message = "Please enter the OTP.";
}
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
        }

        .otp-container {
            background: #fff;
            border-radius: 8px;
            padding: 30px 20px;
            text-align: center;
            width: 320px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }

        .otp-container h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .otp-container p {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 5px;
            margin-bottom: 20px;
        }

        .otp-input {
            width: 40px;
            height: 45px;
            border: 2px solid #ddd;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .otp-input:focus {
            outline: none;
            border-color: #6e8efb;
            box-shadow: 0 0 8px rgba(110, 142, 251, 0.5);
        }

        .verify-btn {
            width: 100%;
            padding: 10px;
            background: #6e8efb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .verify-btn:hover {
            background: #a777e3;
        }

        .resend {
            margin-top: 15px;
            font-size: 13px;
            color: #666;
        }

        .resend a {
            color: #6e8efb;
            text-decoration: none;
            font-weight: bold;
        }

        .resend a:hover {
            text-decoration: underline;
        }

        .message {
            margin-top: 15px;
            font-size: 14px;
            color: #333;
        }

        .resent-otp {
            border: none;
            background-color: white;
            color: #3099dc;
            font-size: 15px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="otp-container">
        <h2>Verify OTP</h2>
        <p>Please enter the OTP sent to your mobile number</p>
        <form method="post" action="">
            <div class="otp-inputs">
                <input type="text" maxlength="1" name="otp[]" class="otp-input" autofocus>
                <input type="text" maxlength="1" name="otp[]" class="otp-input">
                <input type="text" maxlength="1" name="otp[]" class="otp-input">
                <input type="text" maxlength="1" name="otp[]" class="otp-input">
                <input type="text" maxlength="1" name="otp[]" class="otp-input">
                <input type="text" maxlength="1" name="otp[]" class="otp-input">
            </div>
            <button type="submit" class="verify-btn">Verify OTP</button>
            <p class="resend">Didn’t receive the OTP? <button type="submit" class="resent-otp" name="resend">Resend</button></p>
        </form>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>

    <script>
        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === "Backspace" && index > 0 && !input.value) {
                    otpInputs[index - 1].focus();
                }
            });
        });
    </script>
</body>

</html>