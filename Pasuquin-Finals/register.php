<?php
    require_once 'core/dbConfig.php';
    require_once 'core/handleForms.php';

    handleRegistrationForm(); // Handles the form submission and registration process

    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : '';

    unset($_SESSION['message']);
    unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        #applicant-fields, #employer-fields {
            display: none; /* Initially hide both sections */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if ($message): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="applicant">Applicant</option>
                    <option value="employer">Employer</option>
                </select>
            </div>
            <!-- Applicant only fields -->
            <div id="applicant-fields">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name" required>
                </div>
                <div class="form-group">
                    <label for="phone_applicant">Phone</label>
                    <input type="text" name="phone" id="phone_applicant" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" required>
                </div>
                <div class="form-group">
                    <label for="skills">Skills</label>
                    <textarea name="skills" id="skills"></textarea>
                </div>
            </div>

            <!-- Employer only fields -->
            <div id="employer-fields">
                <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" name="company_name" id="company_name" required>
            </div>
                <div class="form-group">
                    <label for="contact_person">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" required>
                </div>
                <div class="form-group">
                    <label for="phone_employer">Phone</label>
                    <input type="text" name="phone" id="phone_employer" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" required>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" name="register">Register</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;

            if (role === 'applicant') {
                // Show applicant fields, hide employer fields
                document.getElementById('applicant-fields').style.display = 'block';
                document.getElementById('employer-fields').style.display = 'none';

                // Disable employer fields to prevent validation
                const employerFields = document.querySelectorAll('#employer-fields input, #employer-fields select');
                employerFields.forEach(field => {
                    field.disabled = true;
                });

        // Enable applicant fields for validation
                const applicantFields = document.querySelectorAll('#applicant-fields input, #applicant-fields select');
                applicantFields.forEach(field => {
                    field.disabled = false;
                });

            } else if (role === 'employer') {
                // Show employer fields, hide applicant fields
                document.getElementById('applicant-fields').style.display = 'none';
                document.getElementById('employer-fields').style.display = 'block';

                // Disable applicant fields to prevent validation
                const applicantFields = document.querySelectorAll('#applicant-fields input, #applicant-fields select');
                applicantFields.forEach(field => {
                    field.disabled = true;
            });

                // Enable employer fields for validation
                const employerFields = document.querySelectorAll('#employer-fields input, #employer-fields select');
                employerFields.forEach(field => {
                    field.disabled = false;
            });
        }
    });
    </script> 
</body>
</html>
