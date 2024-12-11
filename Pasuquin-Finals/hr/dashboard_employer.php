<?php
    require_once '../core/dbConfig.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
        header("Location: ../login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <style>
        header {
            background-color: #007BFF;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        main {
            text-align: center;
            padding: 2rem;
        }

        h2 {
            margin-bottom: 1.5rem;
        }

        .dashboard-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .dashboard-actions .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .dashboard-actions .btn:hover {
            background-color: #0056b3;
        }

        .dashboard-actions .logout {
            background-color: #dc3545;
        }

        .dashboard-actions .logout:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>
    <header>
        <h1>HR Dashboard</h1>
    </header>
    <main>
        <h2>Welcome, <?php echo $_SESSION['user_email']; ?></h2>
        <div class="dashboard-actions">
            <a href="create_jobs.php" class="btn">Post a New Job</a>
            <a href="manage_jobs.php" class="btn">Manage Job Postings</a>
            <a href="manage_applications.php" class="btn">View Applicantions</a>
            <a href="../logout.php" class="btn">Logout</a>
        </div>
    </main>    
</body>
</html>