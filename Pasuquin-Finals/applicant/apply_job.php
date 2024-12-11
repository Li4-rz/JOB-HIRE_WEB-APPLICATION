<?php
require_once '../core/dbConfig.php';
require_once '../core/models.php';
require_once '../core/handleForms.php';

// Check if user is logged in as applicant
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'applicant') {
    header("Location: ../login.php");
    exit();
}

// Get the applicant's ID from the applicants table
$user_id = $_SESSION['user_id'];
$applicantQuery = $pdo->prepare("SELECT id FROM applicants WHERE user_id = :user_id");
$applicantQuery->execute([':user_id' => $user_id]);
$applicant = $applicantQuery->fetch();

if (!$applicant) {
    $_SESSION['error'] = "Applicant profile not found. Please complete your profile first.";
    header("Location: dashboard_applicant.php");
    exit();
}

$applicant_id = $applicant['id'];  // This is the applicant_id

// Check if job_id is provided
if (!isset($_GET['job_id'])) {
    $_SESSION['error'] = "Invalid job ID.";
    header("Location: dashboard_applicant.php");
    exit();
}

$job_id = $_GET['job_id'];
$job = getJobById($job_id);

// Ensure $job is not null and is an array
if ($job === null || !is_array($job)) {
    $_SESSION['error'] = "Job not found.";
    header("Location: dashboard_applicant.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cover_letter_file = $_FILES['cover_letter'];
    $resume_file = $_FILES['resume'];

    // Process the job application
    $result = handleJobApplication($job_id, $applicant_id, $cover_letter_file, $resume_file);  // Use applicant_id

    if ($result['status'] === 'success') {
        $_SESSION['message'] = $result['message'];
        header("Location: dashboard_applicant.php");
        exit();
    } else {
        $_SESSION['error'] = $result['message'];
        header("Location: apply_job.php?job_id=$job_id");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007BFF;
            color: white;
            text-align: center;
            padding: 20px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        header a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            background-color: #4CAF50;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
        }

        header a:hover {
            background-color: #004085;
        }

/* Main content */
        main {
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

/* Success and error messages */
        .success, .error {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

/* Form styles */
        form div {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
        }

        form input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: 14px;
        }

        form input[type="file"]:focus {
            border-color: #007BFF;
            background-color: #e6f7ff;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #218838;
        }

        form button:active {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
    <header>
        <h1>Apply for Job: <?php echo isset($job['title']) ? htmlspecialchars($job['title']) : 'Unknown Job'; ?></h1>
        <a href="dashboard_applicant.php">Back to Dashboard</a>
    </header>

    <main>
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
        
        <form action="apply_job.php?job_id=<?php echo $job_id; ?>" method="POST" enctype="multipart/form-data">
            <div>
                <label for="cover_letter">Cover Letter (PDF or DOCX):</label>
                <input type="file" name="cover_letter" id="cover_letter" accept=".pdf, .docx" required>
            </div>
            <div>
                <label for="resume">Resume (PDF or DOCX):</label>
                <input type="file" name="resume" id="resume" accept=".pdf, .docx" required>
            </div>
            <div>
                <button type="submit" name="apply">Apply</button>
            </div>
        </form>
    </main>
</body>
</html>