<?php 
    require_once 'core/dbConfig.php';
    require_once 'core/handleForms.php';

    handleLoginForm();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9; /* Light background */
            color: #333; /* Dark text */
        }

        header {
            background-color: #007BFF; /* Blue background */
            color: white;
            padding: 1rem 0;
            text-align: center;
            margin-bottom: 15rem;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        main {
            padding: 2rem;
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            text-align: left;
            font-size: 1rem;
        }

        input[type="email"], input[type="password"] {
            padding: 0.5rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 0.75rem;
            font-size: 1rem;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue */
        }

        .error {
            color: red;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 0.9rem;
            margin-top: 1.5rem;
        }

        p a {
            color: #007BFF;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='error'>" .$_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
        ?>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Dont have an account? <a href="register.php">Register here</a></p>
    </main>
</body>
</html>