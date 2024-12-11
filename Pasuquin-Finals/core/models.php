<?php
	require_once 'dbConfig.php';


	function registerUser($email, $password, $role, $fullName = null, $phone = null, $address = null, $skills = null, $companyName = null, $contactPerson = null) {
        global $pdo;
    
        $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
    
        if ($stmt->execute()) {
            $userId = $pdo->lastInsertId();
    
            if ($role == 'applicant') {
                if (!insertApplicantData($userId, $fullName, $phone, $address, $skills)) {
                    return false;
                }
            } elseif ($role == 'employer') {
                if (!insertEmployerData($userId, $companyName, $contactPerson, $phone, $address)) {
                    return false;
                }
            }
    
            return true; 
        } else {
            return false; 
        }
    }
    


    function insertApplicantData($userId, $fullName, $phone, $address, $skills) {
        global $pdo;
            
        error_log("Inserting applicant data: userId=$userId, fullName=$fullName, phone=$phone, address=$address, skills=$skills");
           
        if (empty($phone) || empty($address)) {
            error_log('Error: Phone and Address are required for applicants.');
            return false;
        }
            
        $sql = "INSERT INTO applicants (user_id, full_name, phone, address, skills) VALUES (:user_id, :full_name, :phone, :address, :skills)";
        $stmt = $pdo->prepare($sql);
            
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':skills', $skills);
           
        if ($stmt->execute()) {
            error_log('Successfully inserted applicant data.');
            return true;
        } else {
            error_log('Error inserting applicant data: ' . implode(', ', $stmt->errorInfo()));
            return false;
        }
    }


    function insertEmployerData($userId, $companyName, $contactPerson, $phone, $address) {
        global $pdo;
            
        error_log("Inserting employer data: userId=$userId, companyName=$companyName, contactPerson=$contactPerson, phone=$phone, address=$address");
           
        if (empty($companyName) || empty($contactPerson)) {
            error_log('Error: Company Name and Contact Person are required for employers.');
            return false;
        }
            
        $sql = "INSERT INTO employers (user_id, company_name, contact_person, phone, address) VALUES (:user_id, :company_name, :contact_person, :phone, :address)";
        $stmt = $pdo->prepare($sql);
     
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':contact_person', $contactPerson);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
    
        if ($stmt->execute()) {
            error_log('Successfully inserted employer data.');
            return true;
        } else {
            error_log('Error inserting employer data: ' . implode(', ', $stmt->errorInfo()));
            return false;
        }
    }


	function getUserByEmail($email) {
		global $pdo;

		$sql = "SELECT id, email, password, role FROM users WHERE email = :email";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}


    function createJob($employer_id, $title, $description, $requirements, $location, $salary_range) {
        global $pdo;

        $sql="INSERT INTO jobs (employer_id, title, description, requirements, location, salary_range) VALUES (:employer_id, :title, :description, :requirements, :location, :salary_range)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employer_id', $employer_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':requirements', $requirements);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':salary_range', $salary_range);

        return $stmt->execute();
    }


    function getJobById($jobId) {
        global $pdo;
    
        $sql = "SELECT * FROM jobs WHERE id = :jobId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobId', $jobId);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            echo "No job found for ID: " . $jobId;  // Debugging line
        }
        return $result;
    }


    function getJobsByEmployerId($employer_id) {
        global $pdo;

        $sql = "SELECT * FROM jobs WHERE employer_id = :employer_id ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employer_id', $employer_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    function updateJob($job_id, $title, $description, $requirements, $location, $salary_range) {
        global $pdo;
    
        $sql = "UPDATE jobs 
                SET title = :title, 
                    description = :description, 
                    requirements = :requirements, 
                    location = :location, 
                    salary_range = :salary_range 
                WHERE id = :jobId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobId', $job_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':requirements', $requirements);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':salary_range', $salary_range);
    
        return $stmt->execute();
    }


    function deleteJob($jobId) {
        global $pdo;

        $sql = "DELETE FROM jobs WHERE id = :jobId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobId', $jobId);

        return $stmt->execute();
    }


    function getJobs($searchTerm = "") {
        global $pdo;

        if ($searchTerm) {
            $sql = "SELECT * FROM jobs WHERE title LIKE :searchTerm";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':searchTerm', "%" . $searchTerm . "%");
        } else {
            $sql = "SELECT * FROM jobs";
            $stmt = $pdo->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    function checkApplicantExists($applicant_id) {
        global $pdo;
        $query = "SELECT COUNT(*) FROM applicants WHERE id = :applicant_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':applicant_id' => $applicant_id]);
        return $stmt->fetchColumn() > 0;
    }

    function submitAplication($job_id, $applicant_id, $cover_letter, $resume_file_path) {
        global $pdo;
    
        // Check if the applicant exists in the applicants table
        if (!checkApplicantExists($applicant_id)) {
            return [
                'status' => 'error',
                'message' => 'Applicant does not exist. Please register first.'
            ];
        }
    
        // Check if the job exists in the jobs table
        $jobCheck = $pdo->prepare("SELECT COUNT(*) FROM jobs WHERE id = :job_id");
        $jobCheck->execute([':job_id' => $job_id]);
        $jobExists = $jobCheck->fetchColumn();
        
        if (!$jobExists) {
            return [
                'status' => 'error',
                'message' => 'Job does not exist. Please check the job ID.'
            ];
        }
    
        // Insert application data into the database
        try {
            $sql = "INSERT INTO applications (job_id, applicant_id, cover_letter, resume) 
                    VALUES (:job_id, :applicant_id, :cover_letter, :resume)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':job_id', $job_id);
            $stmt->bindValue(':applicant_id', $applicant_id);
            $stmt->bindValue(':cover_letter', $cover_letter);
            $stmt->bindValue(':resume', $resume_file_path);
            
            if ($stmt->execute()) {
                return [
                    'status' => 'success',
                    'message' => 'Application submitted successfully!'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to submit your application. Please try again.'
                ];
            }
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }


    function getEmployerIdByUserId($userId) {
        global $pdo;

        $sql = "SELECT id FROM employers WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchColumn();
    }


    function getApplicationsByEmployer($employerId) {
        global $pdo;

        $sql = "SELECT
                    a.id,
                    a.status,
                    a.applied_at,
                    j.title as job_title,
                    ap.full_name
                FROM applications a
                INNER JOIN jobs j ON a.job_id = j.id
                INNER JOIN applicants ap ON a.applicant_id = ap.id
                WHERE j.employer_id = :employerId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':employerId', $employerId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    function updateApplicationStatus($applicationId, $status) {
        global $pdo;
    
        $sql = "UPDATE applications 
                SET status = :status 
                WHERE id = :applicationId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':applicationId', $applicationId);
    
        if ($stmt->execute()) {
            return true;
        } else {
            $error = $stmt->errorInfo();
            error_log("Database error: " . print_r($error, true));
            return false;
        }
    }
?>