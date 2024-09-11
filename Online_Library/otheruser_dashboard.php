<?php
session_start();

include("data_class.php");

// Sanitize and validate session data
$userloginid = isset($_GET['userlogid']) ? htmlspecialchars(trim($_GET['userlogid'])) : null;
$_SESSION["userid"] = $userloginid;

// Ensure that session ID is valid
if (empty($userloginid)) {
    die("Invalid session.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Dashboard</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <style>
        .innerright, label {
            color: rgb(16, 170, 16);
            font-weight: bold;
        }
        .container, .row, .imglogo {
            margin: auto;
        }
        .innerdiv {
            text-align: center;
            margin: 100px;
        }
        input {
            margin-left: 20px;
        }
        .leftinnerdiv {
            float: left;
            width: 25%;
        }
        .rightinnerdiv {
            float: right;
            width: 75%;
        }
        .innerright {
            background-color: lightgreen;
        }
        .greenbtn {
            background-color: lightgray;
            color: black;
            width: 95%;
            height: 40px;
            margin-top: 8px;
        }
        .greenbtn, a {
            text-decoration: none;
            color: black;
            font-size: large;
        }
        th {
            background-color: #16DE52;
            color: black;
        }
        td {
            background-color: #b1fec7;
            color: black;
        }
        td, a {
            color: black;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="innerdiv">
        <div class="row"><img class="imglogo" src="images/logo.png" alt="Logo"/></div>
        <div class="leftinnerdiv">
            <br>
            <button class="greenbtn" onclick="openpart('myaccount')">
                <img class="icons" src="images/icon/profile.png" width="30px" height="30px" alt="Profile"/> My Account
            </button>
            <button class="greenbtn" onclick="openpart('requestbook')">
                <img class="icons" src="images/icon/book.png" width="30px" height="30px" alt="Book"/> Request Book
            </button>
            <button class="greenbtn" onclick="openpart('issuereport')">
                <img class="icons" src="images/icon/monitoring.png" width="30px" height="30px" alt="Report"/> Book Report
            </button>
            <a href="index.php">
                <button class="greenbtn">
                    <img class="icons" src="images/icon/logout.png" width="30px" height="30px" alt="Logout"/> LOGOUT
                </button>
            </a>
        </div>

        <div class="rightinnerdiv">
            <div id="myaccount" class="innerright portion" style="<?php echo empty($_REQUEST['returnid']) ? '' : 'display:none'; ?>">
                <button class="greenbtn">My Account</button>

                <?php
                $u = new data;
                $u->setconnection();
                $recordset = $u->userdetail($userloginid);
                foreach ($recordset as $row) {
                    $id = $row[0];
                    $name = htmlspecialchars($row[1]);
                    $email = htmlspecialchars($row[2]);
                    $type = htmlspecialchars($row[4]);
                }
                ?>

                <p style="color:black"><u>Person Name:</u> &nbsp;&nbsp;<?php echo $name; ?></p>
                <p style="color:black"><u>Person Email:</u> &nbsp;&nbsp;<?php echo $email; ?></p>
                <p style="color:black"><u>Account Type:</u> &nbsp;&nbsp;<?php echo $type; ?></p>
            </div>
        </div>

        <div class="rightinnerdiv">
            <div id="issuereport" class="innerright portion" style="<?php echo !empty($_REQUEST['returnid']) ? 'display:none' : 'display:none'; ?>">
                <button class="greenbtn">BOOK RECORD</button>

                <?php
                $userloginid = $_SESSION["userid"];
                $u = new data;
                $u->setconnection();
                $recordset = $u->getissuebook($userloginid);

                $table = "<table style='font-family: Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;'>
                          <tr>
                            <th style='padding: 8px;'>Name</th>
                            <th>Book Name</th>
                            <th>Issue Date</th>
                            <th>Return Date</th>
                            <th>Fine</th>
                            <th>Return</th>
                          </tr>";

                foreach ($recordset as $row) {
                    $table .= "<tr>
                                  <td>{$row[0]}</td>
                                  <td>{$row[2]}</td>
                                  <td>{$row[3]}</td>
                                  <td>{$row[6]}</td>
                                  <td>{$row[7]}</td>
                                  <td>{$row[8]}</td>
                                  <td><a href='otheruser_dashboard.php?returnid={$row[0]}&userlogid={$userloginid}'><button type='button' class='btn btn-primary'>Return</button></a></td>
                                </tr>";
                }
                $table .= "</table>";

                echo $table;
                ?>
            </div>
        </div>

        <div class="rightinnerdiv">
            <div id="return" class="innerright portion" style="<?php echo !empty($_REQUEST['returnid']) ? '' : 'display:none'; ?>">
                <button class="greenbtn">Return Book</button>

                <?php
                $returnid = isset($_REQUEST['returnid']) ? htmlspecialchars(trim($_REQUEST['returnid'])) : null;
                if ($returnid) {
                    $u = new data;
                    $u->setconnection();
                    $u->returnbook($returnid);
                }
                ?>
            </div>
        </div>

        <div class="rightinnerdiv">
            <div id="requestbook" class="innerright portion" style="<?php echo !empty($_REQUEST['returnid']) ? 'display:none' : 'display:none'; ?>">
                <button class="greenbtn">Request Book</button>

                <?php
                $u = new data;
                $u->setconnection();
                $recordset = $u->getbookissue();

                $table = "<table style='font-family: Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;'>
                          <tr>
                            <th>Image</th>
                            <th>Book Name</th>
                            <th>Book Author</th>
                            <th>Branch</th>
                            <th>Price</th>
                            <th>Request Book</th>
                          </tr>";

                foreach ($recordset as $row) {
                    $table .= "<tr>
                                  <td><img src='uploads/{$row[1]}' width='100px' height='100px' style='border:1px solid #333333;' alt='Book Image'></td>
                                  <td>{$row[2]}</td>
                                  <td>{$row[4]}</td>
                                  <td>{$row[6]}</td>
                                  <td>{$row[7]}</td>
                                  <td><a href='requestbook.php?bookid={$row[0]}&userid={$userloginid}'><button type='button' class='btn btn-primary'>Request Book</button></a></td>
                                </tr>";
                }
                $table .= "</table>";

                echo $table;
                ?>
            </div>
        </div>

    </div>
</div>

<script>
function openpart(portion) {
    var i;
    var x = document.getElementsByClassName("portion");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
    }
    document.getElementById(portion).style.display = "block";  
}
</script>

</body>
</html>
