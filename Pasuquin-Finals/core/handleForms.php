<?php
    require_once 'dbConfig.php';
    require_once 'models.php';


    function handleRegistrationForm() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
            error_log("POST data: " . print_r($_POST, true));  // Debugging line to check form data
    
            $email = trim($_POST['email']);
            $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);  // Hash the password
            $role = $_POST['role']; 
    
            if ($role == 'applicant') {
                $fullName = $_POST['full_name'];
                $phone = $_POST['phone'];  
                $address = $_POST['address'];  
                $skills = $_POST['skills'];
               
                if (empty($phone) || empty($address)) {
                    $_SESSION['error'] = "Phone and Address are required for applicants.";
                    header("Location: register.php");
                    exit();
                }
                   
                if (registerUser($email, $password, $role, $fullName, $phone, $address, $skills)) {
                    $_SESSION['message'] = "Registration successful! You can now log in.";
                    header("Location: login.php");
                    exit();
                }
            } elseif ($role == 'employer') {
                $companyName = $_POST['company_name'];
                $contactPerson = $_POST['contact_person'];
                $phone = $_POST['phone'];  
                $address = $_POST['address'];  
    
                if (empty($phone) || empty($address)) {
                    $_SESSION['error'] = "Phone and Address are required for employers.";
                    header("Location: register.php");
                    exit();
                }
    
                if (registerUser($email, $password, $role, null, $phone, $address, null, $companyName, $contactPerson)) {
                    $_SESSION['message'] = "Registration successful! You can now log in.";
                    header("Location: login.php");
                    exit();
                }
            }
            
            $_SESSION['error'] = "Error registering user. Please try again.";
            header("Location: register.php");
            exit();
        }
    }


    function handleLoginForm() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $user = getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                if ($user['role'] == 'applicant') {
                    header("Location: /Pasuquin-Finals/applicant/dashboard_applicant.php");
                    exit();
                } elseif ($user['role'] == 'employer') {
                    header("Location: /Pasuquin-Finals/hr/dashboard_employer.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Invalid email or password.";
            }
        }
    }


    function handleJobCreation(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_job'])) {

            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
                $_SESSION['error'] = "You must logged in as an employer to post a job.";
                header("Location: ../Login.php");
                exit();
            }

            $employer_id = $_SESSION['user_id'];
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $requirements = isset($_POST['requirements']) ? trim($_POST['requirements']) : '';
            $location = trim($_POST['location']);
            $salary_range = trim($_POST['salary_range']);

            if (empty($title) || empty($description) || empty($requirements) || empty($location) || empty($salary_range)) {
                $_SESSION['error'] = "All fields are required!";
                header("Location: /Pasuquin-Finals/hr/create_jobs.php");
                exit();
            }
            
            if (createJob($employer_id, $title, $description, $requirements, $location, $salary_range)) {
                $_SESSION['message'] = "Job posted Successfully!";
                header("Location: /Pasuquin-Finals/hr/dashboard_employer.php");
                exit();
            } else {
                $_SESSION['error'] = "There was an error posting the job. Please try again.";
                header("Location: /Pasuquin-Finals/hr/create_jobs.php");
                exit();
            }
        }
    }


    function handleEditJobForm() {
        global $pdo;
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_job'])) {
            $job_id = $_POST['job_id'];
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $requirements = trim($_POST['requirements']);
            $location = trim($_POST['location']);
            $salary_range = trim($_POST['salary_range']);
    
            // Check if all fields are filled
            if (empty($title) || empty($description) || empty($location) || empty($salary_range)) {
                $_SESSION['error'] = "All fields are required.";
                header("Location: /Pasuquin-Finals/hr/edit_job.php?job_id=$job_id");
                exit();
            }
    
            // Fetch job by ID and check ownership
            $job = getJobById($job_id);
            if (!$job || $job['employer_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = "You are not authorized to edit this job.";
                header("Location: /Pasuquin-Finals/hr/manage_jobs.php");
                exit();
            }
    
            // Update job in the database
            $isUpdated = updateJob($job_id, $title, $description, $requirements, $location, $salary_range);
            if ($isUpdated) {
                $_SESSION['message'] = "Job updated successfully.";
                header("Location: /Pasuquin-Finals/hr/manage_jobs.php");
                exit();
            } else {
                $_SESSION['error'] = "An error occurred while updating the job. Please try again.";
                header("Location: /Pasuquin-Finals/hr/edit_job.php?job_id=$job_id");
                exit();
            }
        }
    }


    function handleJobSearch() {
        $searchTerm = "";
        $jobs = [];
    
        if (isset($_POST['search'])) {
            $searchTerm = $_POST['searchTerm'];
            $jobs = getJobs($searchTerm); 
        } else {
            $jobs = getJobs(); 
        }
        return ['searchTerm' => $searchTerm, 'jobs' => $jobs];
    }
     
    
        function handleJobApplication($job_id, $applicant_id, $cover_letter_file, $resume_file) {
            global $pdo;
            
            // Check if the user is an applicant
            $applicantCheck = $pdo->prepare("SELECT COUNT(*) FROM applicants WHERE id = :applicant_id");
            $applicantCheck->execute([':applicant_id' => $applicant_id]);
            $applicantExists = $applicantCheck->fetchColumn();
            
            if (!$applicantExists) {
                return [
                    'status' => 'error',
                    'message' => 'Applicant does not exist. Please register first.'
                ];
            }
            
            // Handle file uploads
            $coverLetterFilePath = '';
            $resumeFilePath = '';
            
            if ($cover_letter_file['error'] === UPLOAD_ERR_OK) {
                $coverLetterTargetDir = '../uploads/cover_letters/';
                $coverLetterFileName = basename($cover_letter_file['name']);
                $coverLetterFilePath = $coverLetterTargetDir . $coverLetterFileName;
                
                if (!move_uploaded_file($cover_letter_file['tmp_name'], $coverLetterFilePath)) {
                    return [
                        'status' => 'error',
                        'message' => 'Error uploading the cover letter file.'
                    ];
                }
            } else {
                return [
                    'status' => 'error',
                    'message' => 'No cover letter uploaded or invalid file.'
                ];
            }
        
            if ($resume_file['error'] === UPLOAD_ERR_OK) {
                $resumeTargetDir = '../uploads/resumes/';
                $resumeFileName = basename($resume_file['name']);
                $resumeFilePath = $resumeTargetDir . $resumeFileName;
                
                if (!move_uploaded_file($resume_file['tmp_name'], $resumeFilePath)) {
                    return [
                        'status' => 'error',
                        'message' => 'Error uploading the resume file.'
                    ];
                }
            } else {
                return [
                    'status' => 'error',
                    'message' => 'No resume uploaded or invalid file.'
                ];
            }
        
            // Submit the application to the database
            $result = submitAplication($job_id, $applicant_id, $coverLetterFilePath, $resumeFilePath);
            return $result;
        }


        function handleApplicationStatusForm() {

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
                $applicationId = $_POST['application_id']; // Corrected variable name
                $newStatus = $_POST['status'];
        
                if (updateApplicationStatus($applicationId, $newStatus)) {
                    $_SESSION['message'] = "Application status updated successfully.";
                } else {
                    $_SESSION['error'] = "Failed to update application status. Please try again.";
                }
        
                header("Location: manage_applications.php");
                exit();
            }
        }
    
?>
