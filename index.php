<?php 
    
    require_once './vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    session_start();   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form - Assessment</title>
    <link rel="stylesheet" href="./assets/style.css">

    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>

    <div class="form-wrapper">
        <?php

            // Check for a success message
            if (isset($_SESSION['success_message'])) 
            {
                echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }

            // Check for an error message
            if (isset($_SESSION['error_message'])) 
            {
                echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']);
            }
        ?>
        <form method="POST" action="./controllers/ContactController.php">
            <div class="form-group">
                <label for="fullname">Fullname</label>
                <input type="text" name="fullname" required value="<?php echo $_SESSION['fullname'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="fullname">Email</label>
                <input type="email" name="email" required value="<?php echo $_SESSION['email'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="fullname">Mobile number</label>
                <input type="text" name="mobile_number" required value="<?php echo $_SESSION['mobile_number'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="fullname">Subject</label>
                <input type="text" name="subject" required value="<?php echo $_SESSION['subject'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="fullname">Body</label>
                <textarea name="body" rows="4" required><?php echo $_SESSION['body'] ?? '' ?></textarea>
            </div>

            <div id="captcha" class="g-recaptcha" data-sitekey="<?php echo $_ENV['RECAPTCHA_KEY'] ?>"></div>
            <div>
                <button type="submit" name="submit" id="submitBtn">Submit</button>
            </div>
            
        </form> 
    </div>
    <script src="./assets/script.js"></script>
</body>
</html>