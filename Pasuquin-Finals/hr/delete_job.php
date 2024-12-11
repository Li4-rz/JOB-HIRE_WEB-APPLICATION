<?php
require_once '../core/dbConfig.php';
require_once '../core/handleForms.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
    $_SESSION['error'] = "You must be logged in as an employer to delete a job.";
    header("Location: ../login.php");
    exit(); 
}

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    $job = getJobById($job_id);

    if (!$job || $job['employer_id'] != $_SESSION['user_id']) {
        $_SESSION['error'] = "You are not authorized to delete this job.";
        header("Location: manage_jobs.php");
        exit();
    }

    if (deleteJob($job_id)) {
        $_SESSION['message'] = "Job successfully deleted.";
    } else {
        $_SESSION['error'] = "Failed to delete the job. Please try again.";
    }
} else {
    $_SESSION['error'] = "Invalid job ID.";
}

header("Location: manage_jobs.php");
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Job</title>
</head>
<body>
    <header>
        <h1>Delete Job</h1>
    </header>
    <main>
        <?php
            // Display success or error messages
            if (isset($_SESSION['message'])) {
                echo "<div class='success'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }

            if (isset($_SESSION['error'])) {
                echo "<div class='error'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
        ?>

        <p><a href="manage_jobs.php">Back to Manage Jobs</a></p>
    </main>
</body>
</html>
