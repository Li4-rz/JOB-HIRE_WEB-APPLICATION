<?php
    require_once '../core/dbConfig.php';
    require_once '../core/handleForms.php';

    handleEditJobForm();
    
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
        $_SESSION['error'] = "You must be logged in as an employer to edit a job.";
        header("Location: ../login.php");
        exit();
    }

    if (isset($_GET['job_id'])) {
        $job_id = $_GET['job_id'];
        $job = getJobById($job_id);

        if (!$job || $job['employer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "You are not authorized to edit this job.";
            header("Location: /Pasuquin-Finals/hr/manage_jobs.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid job ID.";
        header("Location: /Pasuquin-Finals/hr/manage_jobs.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <style>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #0044cc;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
        }

        .error {
            color: red;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fdd;
            border: 1px solid #fbb;
            border-radius: 4px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
        }

        input[type="text"], textarea {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            margin-top: 20px;
            text-decoration: none;
            color: #0044cc;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Job</h1>
    </header>
    <main>
        <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='error'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
        ?>
        <form action="edit_job.php" method="POST">
            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>">
            <label for="title">Job Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
            <label for="description">Job Description:</label>
            <textarea name="description" id="description" rows="5"><?php echo htmlspecialchars($job['description']); ?></textarea>
            <label for="requirements">Job Requirements:</label>
            <textarea name="requirements" id="requirements" rows="5"><?php echo htmlspecialchars($job['requirements']); ?></textarea>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
            <label for="salary_range">Salary Range:</label>
            <input type="text" name="salary_range" id="salary_range" value="<?php echo htmlspecialchars($job['salary_range']); ?>" required>
            <button type="submit" name="edit_job">Save Changes</button>
        </form>
        <a href="/Pasuquin-Finals/hr/manage_jobs.php">Back to Job Management</a>
    </main>
</body>
</html>