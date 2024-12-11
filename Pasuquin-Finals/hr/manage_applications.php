<?php
    require_once '../core/dbConfig.php';
    require_once '../core/models.php';
    require_once '../core/handleForms.php';


    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
        header("Location: ../login.php");
        exit();
    }

    $employerId = getEmployerIdByUserId($_SESSION['user_id']);
    $applications = getApplicationsByEmployer($employerId);

    handleApplicationStatusForm();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        header {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .btn.accept {
            background-color: #4CAF50;
            color: white;
        }
        .btn.decline {
            background-color: #f44336;
            color: white;
        }
    </style>
    <script>
        function confirmAction(button, action) {
            
            const confirmation = confirm(`Are you sure you want to ${action} this application?`);
    
            if (confirmation) {
                return true;
            } else {
                return false;
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Job Applications</h1>
    </header>
    <?php if (isset($_SESSION['message'])): ?>
        <div style="color: green; text-align: center;">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div style="color: red; text-align: center;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Job Title</th>
                <th>Status</th>
                <th>Applied At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($applications)): ?>
                <?php foreach ($applications as $application):  ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($application['status']); ?></td>
                        <td><?php echo htmlspecialchars($application['applied_at']); ?></td>
                        <td>
                            <form action="manage_applications.php" method="POST" style="display: inline;">
                                <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" name="update_status" class="btn accept" onclick="return confirmAction(this, 'accept')">Accept</button>
                            </form>
                            <form action="manage_applications.php" method="POST" style="display: inline;">
                                <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" name="update_status" class="btn decline" onclick="return confirmAction(this, 'decline')">Decline</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No Applications Found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>