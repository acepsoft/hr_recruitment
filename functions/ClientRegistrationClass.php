<?php
class ClientRegistrationClass
{
    private $db;
    private $utilities;

    public function __construct($customDB)
    {
        // Initialize the CustomDB connection with the necessary credentials
        $this->db = $customDB;

        // Initialize AppUtilities with the same database connection for use
        $this->utilities = new AppUtilities($this->db);
    }

    /**
     * Main method to handle form submissions.
     */
    public function handleRequest()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'save') {
            return json_encode($this->saveClient());
        }
        else if (isset($_POST['action']) && $_POST['action'] == 'saverecruiter') {
            return json_encode($this->saveRecruiters());
        }
        else if (isset($_POST['action']) && $_POST['action'] == 'user_login') {
            return json_encode($this->login());
        }
        return json_encode(['success' => false, 'message' => 'Invalid request.']);
    }

    /**
     * Save a new client to the database.
     */
    private function saveClient()
    {
        $company_name = $this->utilities->htmlCode(trim($_POST['company_name']));
        $contact_person = $this->utilities->htmlCode(trim($_POST['contact_person']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $contact_number = $this->utilities->htmlCode(trim($_POST['contact_number']));
        $company_category = $this->utilities->htmlCode(trim($_POST['company_category']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }

        if (!preg_match('/^\d+$/', $contact_number)) {
            return ['success' => false, 'message' => 'The contact number must contain only numbers.'];
        }

        if ($email && $password) {
            if ($this->emailExists($email, 'clients')) {
                return ['success' => false, 'message' => 'This email address is already registered.'];
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO clients (company_name, contact_person, email, contact_number, company_category, password) 
                      VALUES (:company_name, :contact_person, :email, :contact_number, :company_category, :password)";
            $params = [
                ':company_name' => $company_name,
                ':contact_person' => $contact_person,
                ':email' => $email,
                ':contact_number' => $contact_number,
                ':company_category' => $company_category,
                ':password' => $hashed_password
            ];

            try {
                $this->db->executeQuery($query, $params);
                //todo send email
                return ['success' => true, 'message' => 'Registration completed successfully. <br>A confirmation email has been sent to your address.'];
            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
            }
        } else {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
    }
    /**
     * Save a new Recruiters to the database.
     */
    private function saveRecruiters()
    {
        $name = $this->utilities->htmlCode(trim($_POST['name']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $contact_number = $this->utilities->htmlCode(trim($_POST['contact_number']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }

        if (!preg_match('/^\d+$/', $contact_number)) {
            return ['success' => false, 'message' => 'The contact number must contain only numbers.'];
        }

        if ($email && $password) {
            if ($this->emailExists($email, 'recruiters')) {
                return ['success' => false, 'message' => 'This email address is already registered.'];
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO recruiters (name, email, contact_number, password) 
                      VALUES (:name, :email, :contact_number, :password)";
            $params = [
                ':name' => $name,
                ':email' => $email,
                ':contact_number' => $contact_number,
                ':password' => $hashed_password
            ];

            try {
                $this->db->executeQuery($query, $params);
                //todo send email
                return ['success' => true, 'message' => 'Registration completed successfully. <br>A confirmation email has been sent to your address.'];
            } catch (Exception $e) {
                return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
            }
        } else {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
    }
    private function login(){
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);
        $user_type = $_POST['user_type'] ?? '';

        if (!$email || !$password || !$user_type) {
            $this->respond(false, 'Invalid login details.');
        }

        $user_table = $user_type === 'Recruiters' ? 'recruiters' : 'clients';

        $query = "SELECT * FROM $user_table WHERE email = :email";
        $params = [':email' => $email];

        $result = $this->db->executeQuery($query, $params);

        if (!empty($result) && password_verify($password, $result[0]['password'])) {
            //todo save in session and redirect
            return ['success' => true, 'message' => 'Successfully logged in. Welcome.'];
        } else {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
    }

    /**
     * Check if the email already exists in the database.
     */
    private function emailExists($email, $table)
    {
        $query = "SELECT id FROM $table WHERE email = :email";
        $params = [':email' => $email];
        $result = $this->db->executeQuery($query, $params);
        return !empty($result);
    }

    // Placeholder methods for other functionalities
    private function serveFile()
    {
        // Existing file handling code
    }

    private function getPhotos()
    {
        // Existing photo retrieval code
    }

    private function deleteProduct()
    {
        // Existing delete product code
    }

    private function viewProducts()
    {
        // Existing view products code
    }
}
?>