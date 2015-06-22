<?php
session_start();
require_once('includes/new-connection.php');

// Set default timezone as Los Angeles, CA, USA
date_default_timezone_set('America/Los_Angeles');

// Select query to display all data from users table
$select_query = "SELECT * FROM users";
// Fetch function to display all data
$users = fetch($select_query);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Wall - Logged In</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>

<div id="wrapper">

    <h1>The Wall - Logged In<a class="log-out" href="includes/reset.php">Log Out</a></h1>

    <?php
    if(isset($_SESSION['first_name']) || $_SESSION['last_name']) {
    ?>
        <p><strong>Hello, <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] ?>. Thanks for registering!</strong></p>
    <?php
    }
    elseif(isset($_SESSION['login_success'])) {
    ?>
        <p>You've logged in! Welcome back <?php echo $_SESSION['first_name_login'] . ' ' . $_SESSION['last_name_login']; ?>.</p>
    <?php
    }
    ?>

    <h2>Record of People Registered</h2>

    <table id="regisration-records">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Updated At</th>
            </tr>
        </thead>

        <tbody>
        <?php
        foreach(array_reverse($users) as $user)
        {
            echo '<tr>
                <td>' . $user['first_name'] . '</td><td>' .  $user['last_name'] . '</td><td>'. $user['email'] . '</td><td>'. date('g:ia F j Y', strtotime($user['updated_at'])) . '</td>
            </tr>';
        }
        ?>
        </tbody>

    </table>

</div>

</body>
</html>