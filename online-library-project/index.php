<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('images/login.jpeg');
            background-size: cover;
        }
        .login-container {
            margin-top: 50px;
        }
        .login-form-3, .login-form-1 {
            padding: 5%;
            box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
        }
        .login-form-3 h3, .login-form-1 h3 {
            text-align: center;
            color: #fff;
        }
        .login-form-3 .btnSubmit, .login-form-1 .btnSubmit {
            font-weight: 600;
            color: #0062cc;
            background-color: #fff;
        }
        .error-label {
            color: red;
        }
    </style>
</head>
<body>

<?php
    // Initialize error message variables
    $emailmsg = $pasdmsg = $msg = $ademailmsg = $adpasdmsg = "";

    // Capture error messages from the URL
    if (isset($_REQUEST['ademailmsg'])) {
        $ademailmsg = $_REQUEST['ademailmsg'];
    }

    if (isset($_REQUEST['adpasdmsg'])) {
        $adpasdmsg = $_REQUEST['adpasdmsg'];
    }

    if (isset($_REQUEST['emailmsg'])) {
        $emailmsg = $_REQUEST['emailmsg'];
    }

    if (isset($_REQUEST['pasdmsg'])) {
        $pasdmsg = $_REQUEST['pasdmsg'];
    }

    if (isset($_REQUEST['msg'])) {
        $msg = $_REQUEST['msg'];
    }
?>

<div class="container login-container">
    <div class="row"><h4><?php echo $msg; ?></h4></div>
    <div class="row">
        <!-- Admin Login Form -->
        <div class="col-md-6 login-form-3">
            <h3>Admin Login</h3>
            <form action="loginadmin_server_page.php" method="POST">
                <div class="form-group">
                    <input type="email" class="form-control" name="login_email" placeholder="Your Email *" required>
                </div>
                <label class="error-label">*<?php echo $ademailmsg; ?></label>
                <div class="form-group">
                    <input type="password" class="form-control" name="login_password" placeholder="Your Password *" required>
                </div>
                <label class="error-label">*<?php echo $adpasdmsg; ?></label>
                <div class="form-group">
                    <input type="submit" class="btnSubmit" value="Login">
                </div>
            </form>
        </div>

        <!-- Student Login Form -->
        <div class="col-md-6 login-form-1">
            <h3>Student Login</h3>
            <form action="login_server_page.php" method="POST">
                <div class="form-group">
                    <input type="email" class="form-control" name="login_email" placeholder="Your Email *" required>
                </div>
                <label class="error-label">*<?php echo $emailmsg; ?></label>
                <div class="form-group">
                    <input type="password" class="form-control" name="login_password" placeholder="Your Password *" required>
                </div>
                <label class="error-label">*<?php echo $pasdmsg; ?></label>
                <div class="form-group">
                    <input type="submit" class="btnSubmit" value="Login">
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

