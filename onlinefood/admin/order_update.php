<?php

include("../connection/connect.php");
require '../backend.php';
$SQL = "SELECT * FROM `delivery_partners` WHERE `status` = 'active' ";
$result = mysqli_query($db, $SQL);
$emailSender = new EmailSender();
$deliheveryPartner = new EmailSender();
error_reporting(0);
session_start();
if (strlen($_SESSION['adm_id']) == 0) {
  header('location:index.php');
} else {
  if (isset($_POST['update'])) {
    $form_id = $_GET['form_id'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];
    $query = mysqli_query($db, "insert into remark(frm_id,status,remark) values('$form_id','$status','$remark')");
    $sql = mysqli_query($db, "update users_orders set status='$status' where o_id='$form_id'");

    if ($status == 'closed') {
      echo "<script>alert('Form Details Updated Successfully $status');</script>";
      if ($status == "closed") {
        header("Location: generate_pdf.php?order_ids=$form_id");
      }
    } elseif ($status == 'process') {
      $emailSender->ordeProcessing($form_id, $db);
      if (isset($_POST['partner_id'])) {
        $deliheveryPartner->deliveriBoy($db, $_POST['partner_id'], $form_id);
      }
      echo "<script>
      document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
          document.getElementById('loader').classList.remove('active');
          document.getElementById('formContainer').classList.remove('blur');
        }, 2000);
      });
      </script>";
    } elseif ($status == 'rejected') {
      $emailSender->ordeRejected($form_id, $db);
      echo "<script>
      document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
          document.getElementById('loader').classList.remove('active');
          document.getElementById('formContainer').classList.remove('blur');
        }, 2000);
      });
      </script>";
    }
  }
?>
  <script language="javascript" type="text/javascript">
    function f2() {
      window.close();
    }

    function f3() {
      window.print();
    }

    function jsLoader() {
      document.getElementById('loader').classList.add('active');
      document.getElementById('formContainer').classList.add('blur');
    }
function datashow() {
  var data = document.getElementById('Ostatus').value;
  var dpDetails = document.getElementsByClassName("dp_detail");
  
  // Loop through all elements with class 'dp_detail'
  for (var i = 0; i < dpDetails.length; i++) {
    if (data == 'process') {
      dpDetails[i].style.display = "table-row";
    } else {
      dpDetails[i].style.display = "none";
    }
  }
}

  </script>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Order Update</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <style type="text/css" rel="stylesheet">
      .indent-small {
        margin-left: 5px;
      }

      .form-group.internal {
        margin-bottom: 0;
      }

      .dialog-panel {
        margin: 10px;
      }

      .datepicker-dropdown {
        z-index: 200 !important;
      }

      .panel-body {
        background: #e5e5e5;
        background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
        background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
        font: 600 15px "Open Sans", Arial, sans-serif;
      }

      label.control-label {
        font-weight: 600;
        color: #777;
      }

      .loader {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        align-items: center;
        justify-content: center;
        z-index: 10;
      }

      .loader.active {
        display: flex;
      }

      .spinner {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #4CAF50;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }

        100% {
          transform: rotate(360deg);
        }
      }

      .blur {
        filter: blur(1px);
      }

      table {
        width: 650px;
        border-collapse: collapse;
        margin: auto;
        margin-top: 50px;
      }

      tr:nth-of-type(odd) {
        background: #eee;
      }

      th {
        background: #004684;
        color: white;
        font-weight: bold;
      }

      td,
      th {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
        font-size: 14px;
      }

      .dp_main {
        display: flex;
        justify-content: space-between;
      }

      td.dp_radio {
        text-align: center;
      }
      .dp_detail {
        display: none;
      }
    </style>
  </head>

  <body>
    <div style="margin-left:50px;" class="form-container" id="formContainer">
      <form name="updateticket" id="updatecomplaint" method="post">
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><b>Form Number</b></td>
            <td><?php echo htmlentities($_GET['form_id']); ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>

            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><b>Status</b></td>
            <td><select name="status" required="required" id="Ostatus" onclick="datashow()">
                <option value="">Select Status</option>
                <option value="process">On the way</option>
                <option value="closed">Delivered</option>
                <option value="rejected">Cancelled</option>
              </select></td>
          </tr>
          <tr class="dp_detail">
            <td>Select Delivery-Partner</td>
            <td class="dp_main"><span class="dp_id">Id</span><span class="dp_id"> Name</span><span class="dp_contact">Contact</span></td>
          </tr>
          <?php while ($dp_data = mysqli_fetch_assoc($result)): ?>
            <tr class="dp_detail">
              <td class="dp_radio"><input type="radio" name="partner_id" value="<?= $dp_data['id'] ?>"></td>
              <td class="dp_main">
                <span class="dp_id"><?= $dp_data['id'] ?></span>
                <span class="dp_id"><?= $dp_data['name'] ?></span>
                <span class="dp_contact"><?= $dp_data['phone'] ?></span>
              </td>
            </tr>
          <?php endwhile; ?>
          <tr>
            <td><b>Message</b></td>
            <td><textarea name="remark" cols="50" rows="10" required="required"></textarea></td>
          </tr>
          <tr>
            <td><b>Action</b></td>
            <td><input type="submit" name="update" class="btn btn-primary" value="Submit" onClick="jsLoader()">
              <input name="Submit2" type="submit" class="btn btn-danger" value="Close this window " onClick="return f2();" style="cursor: pointer;" />
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div id="loader" class="loader">
      <div class="spinner"></div>
    </div>
  </body>

  </html>
<?php } ?>
