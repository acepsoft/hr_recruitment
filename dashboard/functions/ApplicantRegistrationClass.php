<?php

class ApplicantRegistrationClass
{
    // Properties for applicant details
    private string $first_name;
    private string $last_name;
    private string $email;
    private string $phone;
    private string $address;
    private string $state;
    private string $zip_code;
    private string $country;
    private string $resume_file;
    private string $social_network;
    private string $status;
    private int $rating;
    private string $employee_status;
    private string $visa_status;

    // Utilities
    private $utilities;
    private array $allowed_file_types = ['pdf', 'doc', 'docx'];
    private int $max_file_size = 10485760; // Now allows files up to 10 MB

    // Constructor to initialize applicant data and utilities
    public function __construct($customDB)
    {
        // Initialize the CustomDB connection with the necessary credentials
        $this->db = $customDB;

        // Initialize AppUtilities with the same database connection for use
        $this->utilities = new AppUtilities($this->db);


    }

    public function validateFile(array $file): bool
    {
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        return in_array($file_extension, $this->allowed_file_types) && $file['size'] <= $this->max_file_size && $file['error'] === 0;
    }

    public function processFileUpload(array $file): string
    {
        $upload_directory = __DIR__ . '/../uploads/';
        $unique_file_name = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $target_file_path = $upload_directory . $unique_file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file_path)) {
            return $unique_file_name;
        }

        throw new RuntimeException("Failed to upload file.");
    }
    public function saveApplicant(array $data): bool
    {
        try {
            // Assign and sanitize user inputs
            $this->first_name = $this->utilities->htmlCode($data['first_name'] ?? '');
            $this->last_name = $this->utilities->htmlCode($data['last_name'] ?? '');
            $this->email = $this->utilities->htmlCode($data['email'] ?? '');
            $this->phone = $this->utilities->htmlCode($data['phone'] ?? '');
            $this->address = $this->utilities->htmlCode($data['address'] ?? '');
            $this->state = $this->utilities->htmlCode($data['state'] ?? '');
            $this->zip_code = $this->utilities->htmlCode($data['zip_code'] ?? '');
            $this->country = $this->utilities->htmlCode($data['country'] ?? '');
            $this->social_network = $this->utilities->htmlCode($data['social_network'] ?? '');
            $this->status = $this->utilities->htmlCode($data['status'] ?? '');

            // Process file input for resume
            if (isset($data['resume_file']) && !empty($data['resume_file']) && $this->validateFile($data['resume_file'])) {
                $this->resume_file = $this->processFileUpload($data['resume_file']);
            } elseif (!isset($data['resume_file']) || empty($data['resume_file'])) {
                $this->resume_file = '';
            } else {
                throw new InvalidArgumentException("Invalid file upload for resume.");
            }

            $this->rating = (int) ($data['rating'] ?? 0);
            $this->employee_status = $this->utilities->htmlCode($data['employee_status'] ?? '');
            $this->visa_status = $this->utilities->htmlCode($data['visa_status'] ?? '');
            $query = "
            INSERT INTO applicants 
            (first_name, last_name, email, phone, address, state, zip_code, country, resume_file, social_network, status, rating, employee_status, visa_status) 
            VALUES 
            (:first_name, :last_name, :email, :phone, :address, :state, :zip_code, :country, :resume_file, :social_network, :status, :rating, :employee_status, :visa_status)";

            $params = [
                ':first_name' => $this->first_name,
                ':last_name' => $this->last_name,
                ':email' => $this->email,
                ':phone' => $this->phone,
                ':address' => $this->address,
                ':state' => $this->state,
                ':zip_code' => $this->zip_code,
                ':country' => $this->country,
                ':resume_file' => $this->resume_file,
                ':social_network' => $this->social_network,
                ':status' => $this->status,
                ':rating' => $this->rating,
                ':employee_status' => $this->employee_status,
                ':visa_status' => $this->visa_status,
            ];
            $this->db->executeQuery($query, $params);
            return true;
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            throw new RuntimeException("Error saving applicant data, please try again later. ".$e->getMessage());
        }
    }
}