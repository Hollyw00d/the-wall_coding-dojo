<?php
session_start();
require_once('includes/new-connection.php');

// Set default timezone as Los Angeles, CA, USA
date_default_timezone_set('America/Los_Angeles');

// Select query to display all data from users table
$select_query = "SELECT * FROM users";
// Fetch function to display all data
$users = fetch($select_query);

/*
Redirect peeps back to the home page if:
- The registration form first_name, last_name session variables aren't set
- OR, the login_success session variable isn't set
*/
if(!isset($_SESSION['user_id']))
{
    header('Location: index.php');
}

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
    if(isset($_SESSION['user_id'])) {
    ?>
        <p>You've logged in! Welcome back <?php echo $_SESSION['first_name_login'] . ' ' . $_SESSION['last_name_login']; ?>.</p>
        <p>User ID: <?php echo $_SESSION['user_id']; ?></p>

    <?php
    }
    ?>

    <h2>Post a Message</h2>

    <form id="message-form" action="includes/process.php" method="post">
        <input type="hidden" name="action" value="message"/>
        <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']; ?>"/>
        <textarea name="post_message"></textarea>
        <p><input type="submit" value="Post a Message"/></p>
    </form>

    <div class="errors">

        <?php
        if(isset($_SESSION['message_errors']))
        {

            foreach($_SESSION['message_errors'] as $error)
            {
                echo "<p>$error</p>";
            }

            unset($_SESSION['message_errors']);
        }
        ?>

    </div>

    <?php
    // Display messages if any exist in database
    //$messages_query = "SELECT * FROM messages";

    $messages_query = "SELECT messages.message, messages.updated_at, messages.id, users.first_name, users.last_name FROM messages LEFT JOIN users ON messages.user_id = users.id";

    $messages = fetch($messages_query);

    if($messages != NULL) {
    ?>

        <div id="messages-container">

            <h2>Messages</h2>

            <?php
            foreach($messages as $message)
            {
            ?>

                <div class="message"><strong><?php echo $message['first_name'] . ' ' . $message['last_name'] . ' &mdash; ' . date('F j Y', strtotime($message['updated_at'])); ?></strong><br /><?php echo $message['message']; ?>&nbsp;<br />


                      <div class="comment">

                        <?php
                        // Select query display data from the comments table
                        // with the correct message_id value

                        // $comments_query = "SELECT messages.message, comments.message_id, comments.comment FROM messages LEFT JOIN comments ON messages.id = comments.message_id WHERE comments.message_id = {$message['id']}";

                        $comments_query = "SELECT users.first_name, users.last_name, messages.message, messages.id, comments.comment, comments.id, comments.updated_at FROM users LEFT JOIN messages ON users.id = messages.user_id LEFT JOIN comments ON messages.id = comments.message_id WHERE comments.message_id = {$message['id']}";

                        // Fetch function to display all data
                        $comments = fetch($comments_query);

                        foreach($comments as $comment)
                        {
                        ?>
                            <p><strong><?php echo $comment['first_name'] . ' ' . $comment['last_name']; ?> &mdash; <?php echo date('F j Y', strtotime($comment['updated_at'])); ?></strong><br /><?php echo $comment['comment']; ?></p>
                        <?php
                        }
                        ?>

                          <form id="comment-form" action="includes/process.php" method="post">
                              <input type="hidden" name="action" value="comment"/>
                              <input type="hidden" name="id" value="<?php echo $message['id']; ?>"/>
                              <strong>Post a Comment</strong><br />
                              <textarea name="post_comment"></textarea><br />
                              <input type="submit" value="Post a Comment"/>
                          </form>

                      </div>

                </div>
            <?php
            }
            ?>

        </div>

    <?php
    }
    ?>

</div>

</body>
</html>