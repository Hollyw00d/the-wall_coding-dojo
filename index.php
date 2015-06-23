<?php
session_start();
require_once('includes/new-connection.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Wall - Registration and Login - Coding Dojo Assignment</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>

<div id="wrapper">

    <h1>The Wall - Registration and Login</h1>

    <h3>Registration</h3>

    <form action="includes/process.php" method="post">

        <input type="hidden" name="action" value="register"/>
        <p>First Name: <input type="text" name="first_name"/></p>
        <p>Last Name: <input type="text" name="last_name"/></p>
        <p>Email: <input type="text" name="email"/></p>
        <p>Password: <input type="password" name="password"/></p>
        <p>Retype Password: <input type="password" name="retype_password"/></p>
        <p><input name="submit" type="submit" value="Submit"/></p>

    </form>

    <div class="errors">

        <?php
        if(isset($_SESSION['errors']))
        {

            foreach($_SESSION['errors'] as $error)
            {
                echo "<p>$error</p>";
            }

            unset($_SESSION['errors']);

        }
        ?>

    </div>

    <div class="success">
    <?php
    if(isset($_SESSION['first_name']) || $_SESSION['last_name']) {
        ?>
        <p><strong>Hello, <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] ?>. Thanks for registering!</strong></p>

        <p><strong>Log in using the form below.</strong></p>
    <?php
    }
    ?>
    </div>

    <h3>Log In</h3>

    <form action="includes/process.php" method="post">

        <input type="hidden" name="action" value="login"/>
        <p>Email: <input type="text" name="email"/></p>
        <p>Password: <input type="password" name="password"/></p>
        <p><input name="submit" type="submit" value="Submit"/></p>

    </form>

    <div class="errors">

        <?php
        if(isset($_SESSION['login_errors']))
        {

            foreach($_SESSION['login_errors'] as $error)
            {
                echo "<p>$error</p>";
            }

            unset($_SESSION['login_errors']);

        }
        ?>

    </div>

    <p>&nbsp;</p>
    <p><a href="includes/reset.php">DESTROY SESSION</a></p>

</div>

</body>
</html>