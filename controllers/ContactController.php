<?php

require_once '../config/database.php';
require_once './MailController.php';
require_once '../helpers/recaptcha.php';

// Create a new instance of the database class
$db = new Database();

if (isset($_POST['submit'])) 
{
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $recaptcha_data = $_POST['g-recaptcha-response'];

    if (!validate_recaptcha($recaptcha_data)) 
    {
        session_start();
        $_SESSION['error_message'] = 'Check the checkbox in recaptcha';
        
        $_SESSION["fullname"] = $fullname;
        $_SESSION["email"] = $email;
        $_SESSION["mobile_number"] = $mobile_number;
        $_SESSION["subject"] = $subject;
        $_SESSION["body"] = $body;

        header('Location: ../');
        exit;
    }

    // Get database connection
    $conn = $db->getConnection();

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO tickets (fullname, email, mobile_number, subject, body) VALUES (?, ?, ?, ?, ?)");

    // Bind the parameter values to the prepared statement
    $stmt->bind_param("sssss", $fullname, $email, $mobile_number, $subject, $body);

    // Execute the prepared statement
    $result = $stmt->execute();

    // Create a new instance of the MailController class
    $mail = new MailController($fullname, $email,$subject, $body);
    $mail_response = $mail->send(); // Send the mail

    // Check if the query was successful
    if ($result && $mail_response) 
    {
        // Clear all the session values
        session_destroy();

        // Set a success message
        session_start();
        $_SESSION['success_message'] = 'Your message has been sent successfully.';
        session_write_close();
    } 
    else 
    {
        // Set an error message
        session_start();
        $_SESSION['error_message'] = 'Error inserting data: ' . mysqli_error($conn);
        session_write_close();
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();

    // Redirect to index file
    header('Location: ../');
    exit; // Stop executing the current script
}