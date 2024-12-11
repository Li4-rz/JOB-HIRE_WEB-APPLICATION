<?php
    require_once '../core/dbConfig.php';
    require_once '../core/handleForms.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
        header("Location: ../login.php");
        exit();
    }

    handleJobCreation();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 2em;
            color: #333;
        }

        main {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .success, .error {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .success {
            background-color: #28a745;
            color: white;
        }

        .error {
            background-color: #dc3545;
            color: white;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        a.btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        a.btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <header>
        <h1>Create a New Job</h1>
    </header>
    <main>
        <h2>Post a New Job</h2>
        <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='success'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }

            if (isset($_SESSION['error'])) {
                echo "<div class='error'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
        ?>
        <form action="create_jobs.php" method="POST">
            <label for="title">Job Title:</label>
            <input type="text" name="title" id="title" required>
            <label for="description">Job Description:</label>
            <textarea name="description" id="description" rows="5" required></textarea>
            <label for="requirements">Requirements:</label>
            <textarea name="requirements" id="requirements" rows="5" required></textarea>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>
            <label for="salary_range">Salary Range:</label>
            <input type="text" name="salary_range" id="salary_range" required>
            <button type="submit" name="create_job">Post Job</button>
        </form>
        <a href="dashboard_employer.php" class="btn">Back to Dashboard</a>
    </main>
</body>
</html>