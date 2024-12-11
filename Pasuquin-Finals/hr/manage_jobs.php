<?php
    require_once '../core/dbConfig.php';
    require_once '../core/models.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
        header("Location: ../login.php");
        exit();
    }

    $jobs = getJobsByEmployerId($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-top: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            text-align: center;
            font-size: 1.2em;
            color: #e74c3c;
        }

        .action-links {
            text-align: center;
            margin-top: 20px;
        }

        .action-links a {
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .action-links a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Manage Your Job Postings</h1>
    <?php if (isset($jobs) && count($jobs) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['title']); ?></td>
                        <td><?php echo htmlspecialchars($job['location']); ?></td>
                        <td><?php echo htmlspecialchars($job['salary_range']); ?></td>
                        <td>
                            <a href="edit_job.php?job_id=<?php echo $job['id']?>">Edit</a> |
                            <a href="delete_job.php?job_id=<?php echo $job['id']?>" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You Have not posted any jobs yet.</p>
    <?php endif; ?>
    <div class="action-links">
        <a href="create_jobs.php">Post a New Job</a>
    </div>
</body>
</html>