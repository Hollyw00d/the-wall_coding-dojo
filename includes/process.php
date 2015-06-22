<?php
session_start();
require_once('new-connection.php');

// Function below checks for errors on register form and
// if not errors submits AND
// $get_connection refers to the $connection of new-connection.php to connect to MySQL
function register_user($post, $get_connection)
{
    // Create empty errors array
    $_SESSION['errors'] = [];

    // First name validate
    if(!isset($post['first_name']) || $post['first_name'] == NULL)
    {
        $_SESSION['errors'][] = 'Please enter a first name.';
    }
    elseif(strlen($post['first_name']) < 2 || strlen($post['first_name'] > 100))
    {
        $_SESSION['errors'][] = 'Your first name must be at least 2 characters and less than 100 characters.';
    }

    // Last name validate
    if(!isset($post['last_name']) || $post['last_name'] == NULL)
    {
        $_SESSION['errors'][] = 'Please enter a last name.';
    }
    elseif(strlen($post['last_name']) < 2 || strlen($post['last_name']) > 100)
    {
        $_SESSION['errors'][] = 'Your last name must be at least 2 characters and less than 100 characters.';
    }

    // Email validate
    if(!isset($post['email']) || $post['email'] == NULL)
    {
        $_SESSION['errors'][] = 'Please enter an email.';
    }
    elseif(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
    {
        $_SESSION['errors'][] = 'Please enter a valid email.';
    }

    // Check if email entered matches email in database
    // Set variables to insert into MySQL queries
    $email_sec = mysqli_real_escape_string($get_connection, $post['email']);
    $check_email_password_query = "SELECT * FROM users WHERE users.email = '$email_sec'";

    // Try to grab the user with above credentials
    $execute_check_email_password_query = fetch($check_email_password_query);

    if(count($execute_check_email_password_query) > 0)
    {
        if($execute_check_email_password_query[0]['email'] == $post['email'])
        {
            $_SESSION['errors'][] = 'This email is already in use. Please choose another email.';
        }

    }

    // Password validate
    if(!isset($post['password']) || $post['password'] == NULL)
    {
        $_SESSION['errors'][] = 'Please enter a password.';
    }
    elseif(strlen($post['password']) < 6 || strlen($post['password']) > 20)
    {
        $_SESSION['errors'][] = 'Your password must be at least 6 characters and 20 characters or less.';
    }

    // Re-type password validate
    if(!isset($post['retype_password']) || $post['retype_password'] == NULL)
    {
        $_SESSION['errors'][] = 'Please re-type your password.';
    }
    elseif($post['password'] != $post['retype_password'])
    {
        $_SESSION['errors'][] = 'Your password must match your re-typed password.';
    }


    // Display errors if $errors array is NOT null
    if($_SESSION['errors'] != NULL)
    {

        header('Location: ../index.php');
    }

    // If no errors redirect to logged-in.php, input form fields into database, and
    // show submitted form field info and database info on this new page
    else {

        if(!isset($_SESSION['first_name']) && !isset($_SESSION['last_name']))
        {

            // Set first name, last name, email
            // to session variables to echo on next page
            $_SESSION['first_name'] = $post['first_name'];
            $_SESSION['last_name'] = $post['last_name'];

            // Set variables to insert into MySQL queries
            $first_name_sec = mysqli_real_escape_string($get_connection, $post['first_name']);
            $last_name_sec = mysqli_real_escape_string($get_connection, $post['last_name']);
            $email_sec = mysqli_real_escape_string($get_connection, $post['email']);

            // Escape and encrypt the inputted password and
            // DON'T SET IT to a session variable
            $password_sec = mysqli_real_escape_string($get_connection, md5($post['password']));

            // Insert user MySQL query
            $insert_user = "INSERT INTO users(first_name, last_name, email, password, created_at, updated_at) VALUES ('$first_name_sec', '$last_name_sec', '$email_sec', '$password_sec', NOW(), NOW())";

            $execute_insert_user = run_mysql_query($insert_user);

            header('Location: ../logged-in.php');

        }

    }

}


// Function below checks if someone has already registered AND
// passes in the $connect variable to connect to the database
function login_user($post, $get_connection)
{

    // Create empty login_errors array, session variable
    $_SESSION['login_errors'] = [];

    // Create an empty login_success array to be use as a log in token on the
    // that will show a success message on the logged-in.php page
    $_SESSION['login_success'] = 'login_success';

    // Set variables to insert into MySQL queries
    $email_sec = mysqli_real_escape_string($get_connection, $post['email']);

    // Escape and encrypt the inputted password and
    // DON'T SET IT to a session variable
    $password_sec = mysqli_real_escape_string($get_connection, md5($post['password']));

    $check_email_password_query = "SELECT * FROM users WHERE users.email = '$email_sec' AND users.password = '$password_sec'";

    // Try to grab the user with above credentials
    $execute_check_email_password_query = fetch($check_email_password_query);

    if(count($execute_check_email_password_query) > 0)
    {
        $_SESSION['first_name_login'] = $execute_check_email_password_query[0]['first_name'];
        $_SESSION['last_name_login'] = $execute_check_email_password_query[0]['last_name'];
        header('Location: ../logged-in.php');
    }
    else
    {

        $_SESSION['login_errors'][] = 'Your email/password combination was not found. Please try again.';
        header('Location: ../index.php');

    }

}


// If registered form submitted call the register_user function with
// $_POST as the argument AND pass in the $connection variable to run MySQL queries
if(isset($_POST['action']) && $_POST['action'] == 'register')
{
    register_user($_POST, $connection);
}

if(isset($_POST['action']) && $_POST['action'] == 'login')
{
    login_user($_POST, $connection);
}



?>