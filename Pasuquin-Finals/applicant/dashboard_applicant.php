<?php
    require_once '../core/dbConfig.php';
    require_once '../core/handleForms.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'applicant') {
        header("Location: ../login.php");
        exit();
    }

    $result = handleJobSearch();
    $searchTerm = $result['searchTerm'];
    $jobs = $result['jobs']
?>
<!DOCTYPE html>
<lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
    <style>
        body, h1, h3, p {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        h1 {
            font-size: 24px;
        }

        form {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 60%;
            margin-right: 10px;
        }

        button[type="submit"] {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-btn {
            background-color: #f44336; 
            color: white;
        }

        .logout-btn:hover {
            background-color: #d32f2f; 
            transform: scale(1.05); 
        }

        .logout-btn:active {
            background-color: #c62828; 
        }

        .jobs-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .job-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .job-card h3 {
            font-size: 20px;
            color: #4CAF50;
        }

        .job-card p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .job-card .apply-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        .job-card .apply-btn:hover {
            background-color: #0b79d0;
        }

        .jobs-container p {
            text-align: center;
            color: #666;
        }

        @media screen and (max-width: 768px) {
            .jobs-container {
                flex-direction: column;
                align-items: center;
            }

            input[type="text"] {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to your dashboard, <?php echo $_SESSION['user_email']; ?></h1>
        <a href="../logout.php" class="btn logout-btn">Logout</a>
    </header>
        <!--Search Bar-->
        <form action="dashboard_applicant.php" method="POST">
            <input type="text" name="searchTerm" placeholder="Search for jobs..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" name="search">Search</button>
        </form>
        <!--Job Displays-->
        <div class="jobs-container">
            <?php if (!empty($jobs)): ?>
                <?php foreach ($jobs as $job): ?>
                    <div class="job-card">
                        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
                        <p><strong>Requirements:</strong>
                            <?php
                                    if ($job['requirements']) {
                                        $requirements = json_decode($job['requirements'], true);
                                        if ($requirements) {
                                            echo implode(', ', $requirements);
                                        } else {
                                            echo htmlspecialchars($job['requirements']);
                                        }
                                    } else {
                                        echo 'No specific requirements listed.';
                                    }
                            ?>
                        </p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                        <p><strong>Salary Range:</strong> <?php echo htmlspecialchars($job['salary_range']); ?></p>
                        <a href="apply_job.php?job_id=<?php echo $job['id']; ?>" class="apply-btn">Apply Now</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No jobs found. Please try a different search term or check back later.</p>
            <?php endif; ?>                        
        </div>
    </main>
</body>
</html>