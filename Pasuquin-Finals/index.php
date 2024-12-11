<?php
    require_once 'core/dbconfig.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h1>Welcome to Job Hire Platform</h1>
            <p>Your one-stop solution for conncecting applicants and employers.</p>
        </section>
        <section>
            <h2>Features</h2>
            <ul>
                <li>Browse job listings and find your dream job.</li>
                <li>Post job openings and find the perfect candidate.</li>
                <li>Seamlessly connect with applicants and employers.</li>
            </ul>
        </section>
        <section>
            <h2>Get Started</h2>
            <p>
                <a href="register.php" class="btn">Register Now</a>
                or
                <a href="login.php" class="btn">Login</a> if ypu already have an account.
            </p>
        </section>
    </main>
</body>
</html>