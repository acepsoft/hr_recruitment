<?php include 'includes/header.php';
require_once 'functions/ApplicantRegistrationClass.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Prepare the input data
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'state' => $_POST['state'] ?? '',
            'zip_code' => $_POST['zip_code'] ?? '',
            'country' => $_POST['country'] ?? '',
            'social_network' => $_POST['social_network'] ?? '',
            'status' => $_POST['status'] ?? '',
            'resume_file' => $_FILES['resume'] ?? null,
            'rating' => $_POST['rating'] ?? 0,
            'employee_status' => $_POST['employee_status'] ?? '',
            'visa_status' => $_POST['visa_status'] ?? '',
        ];

        // Create an instance of AppUtilities and pass it to the class
        $applicant = new ApplicantRegistrationClass($customDB);
        $applicant->saveApplicant($data);

        // Successful save
        $responseData = ['success' => true, 'message' => 'Applicant processed successfully!'];
    } catch (Exception $e) {
        // Handle exceptions gracefully
        $responseData = ['success' => false, 'message' => $e->getMessage()];
    }
}
?>
<main class="main">
    <!-- Page Title -->
    <div class="page-title">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-10">
                        <div class="container">
                            <h1>New Applicant</h1>
                            <hr/>
                            <?php if (isset($responseData)){ ?>
                                <div class="alert alert-<?php echo $responseData['success'] ? 'success' : 'danger'; ?>">
                                    <?php echo $responseData['message']; ?>
                                </div>
                            <?php }
                            if(isset($responseData['success'])&&!$responseData['success']||!isset($responseData)){?>
                            <h5>Please fill in the required details below.</h5>
                            <form method="POST" action="new_applicant.php" enctype="multipart/form-data">
                                <!-- Basic Information -->
                                <div class="mb-3">
                                    <h5>Basic Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6 text-lg-start">
                                            <label class="form-label text-dark-blue">First Name</label>
                                            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($data['first_name'] ?? '', ENT_QUOTES); ?>" required>
                                        </div>
                                        <div class="col-md-6 text-lg-start">
                                            <label class="form-label text-dark-blue">Last Name</label>
                                            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($data['last_name'] ?? '', ENT_QUOTES); ?>" required>
                                        </div>
                                        <div class="col-md-6 text-lg-start">
                                            <label class="form-label text-dark-blue">Address</label>
                                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($data['address'] ?? '', ENT_QUOTES); ?>">
                                        </div>
                                        <div class="col-md-2 text-lg-start">
                                            <label class="form-label text-dark-blue">State</label>
                                            <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars($data['state'] ?? '', ENT_QUOTES); ?>">
                                        </div>
                                        <div class="col-md-2 text-lg-start">
                                            <label class="form-label text-dark-blue">Zip Code</label>
                                            <input type="text" class="form-control" name="zip_code" value="<?php echo htmlspecialchars($data['zip_code'] ?? '', ENT_QUOTES); ?>">
                                        </div>
                                        <div class="col-md-2 text-lg-start">
                                            <label class="form-label text-dark-blue">Country</label>
                                            <input type="text" class="form-control" name="country" value="<?php echo htmlspecialchars($data['country'] ?? '', ENT_QUOTES); ?>">
                                        </div>
                                        <div class="col-md-6 text-lg-start">
                                            <label class="form-label text-dark-blue">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($data['email'] ?? '', ENT_QUOTES); ?>" required>
                                        </div>
                                        <div class="col-md-6 text-lg-start">
                                            <label class="form-label text-dark-blue">Phone #</label>
                                            <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($data['phone_number'] ?? '', ENT_QUOTES); ?>">
                                        </div>
                                        <div class="col-md-12 text-lg-start">
                                            <label class="form-label text-dark-blue">Social Network</label>
                                            <input type="text" class="form-control" name="social_network" value="<?php echo htmlspecialchars($data['social_network'] ?? '', ENT_QUOTES); ?>">
                                        </div>

                                        <div class="mb-3 text-lg-start">
                                            <label class="form-label text-dark-blue">Upload Resume</label>
                                            <div class="drag-drop-area"
                                                 style="border: 2px dashed #ccc; padding: 1rem; text-align: center; cursor: pointer;">
                                                <p>Drag & Drop File Here or Click to Upload</p>
                                                <input type="file" class="form-control" name="resume" id="resume-upload"
                                                       style="opacity: 0; position: absolute; width: 100%; top: 0; left: 0;"
                                                       accept=".pdf, .doc, .docx"/>
                                                <p id="file-name" style="margin-top: 1rem; font-weight: bold; color: #007bff;"></p>
                                            </div>
                                            <small class="form-text text-muted">Only PDF and Word Documents are accepted.</small>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <!-- Qualifications -->
                                <div class="mb-3">
                                    <h5>Qualifications</h5>
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="form-label text-dark-blue">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="new" <?php echo (isset($data['status']) && $data['status'] === 'new') ? 'selected' : ''; ?>>New</option>
                                                
                                                <option value="cleared" <?php echo (isset($data['status']) && $data['status'] === 'cleared') ? 'selected' : ''; ?>>Cleared</option>
                                                
                                                <option value="assigned_interviewed" <?php echo (isset($data['status']) && $data['status'] === 'assigned_interviewed') ? 'selected' : ''; ?>>Assigned Interviewed</option>
                                                
                                                <option value="rejected" <?php echo (isset($data['status']) && $data['status'] === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                                
                                                <option value="interview_completed" <?php echo (isset($data['status']) && $data['status'] === 'interview_completed') ? 'selected' : ''; ?>>Interview Completed</option>
                                                
                                                <option value="accepted" <?php echo (isset($data['status']) && $data['status'] === 'accepted') ? 'selected' : ''; ?>>Accepted</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label text-dark-blue">Rating</label>
                                            <select class="form-select" name="rating">
                                                <option value="1" <?php echo (isset($data['rating']) && $data['rating'] == '1') ? 'selected' : ''; ?>>1 - Poor</option>
                                                
                                                <option value="2" <?php echo (isset($data['rating']) && $data['rating'] == '2') ? 'selected' : ''; ?>>2 - Fair</option>
                                                
                                                <option value="3" <?php echo (isset($data['rating']) && $data['rating'] == '3') ? 'selected' : ''; ?>>3 - Good</option>
                                                
                                                <option value="4" <?php echo (isset($data['rating']) && $data['rating'] == '4') ? 'selected' : ''; ?>>4 - Very Good</option>
                                                
                                                <option value="5" <?php echo (isset($data['rating']) && $data['rating'] == '5') ? 'selected' : ''; ?>>5 - Excellent</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label text-dark-blue">Employee Status</label>
                                            <select class="form-select" name="employee_status">
                                                <option value="current" <?php echo (isset($data['employee_status']) && $data['employee_status'] === 'current') ? 'selected' : ''; ?>>Current Employee</option>
                                                
                                                <option value="past" <?php echo (isset($data['employee_status']) && $data['employee_status'] === 'past') ? 'selected' : ''; ?>>Past Employee</option>
                                                
                                                <option value="direct" <?php echo (isset($data['employee_status']) && $data['employee_status'] === 'direct') ? 'selected' : ''; ?>>Direct Placement</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label text-dark-blue">Visa Status</label>
                                            <select class="form-select" name="visa_status">
                                                <option value="us_citizen" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'us_citizen') ? 'selected' : ''; ?>>U.S. Citizen</option>
                                                
                                                <option value="green_card" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'green_card') ? 'selected' : ''; ?>>Green Card Holder</option>
                                                
                                                <option value="h1b" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'h1b') ? 'selected' : ''; ?>>H-1B</option>

                                                <option value="h4_ead" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'h4_ead') ? 'selected' : ''; ?>>
                                                    H-4 EAD
                                                </option>
                                                <option value="l1" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'l1') ? 'selected' : ''; ?>>
                                                    L-1 Visa
                                                </option>
                                                <option value="l2_ead" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'l2_ead') ? 'selected' : ''; ?>>
                                                    L-2 EAD
                                                </option>
                                                <option value="tn_permit" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'tn_permit') ? 'selected' : ''; ?>>
                                                    TN Permit
                                                </option>
                                                <option value="opt" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'opt') ? 'selected' : ''; ?>>
                                                    OPT
                                                </option>
                                                <option value="cpt" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'cpt') ? 'selected' : ''; ?>>
                                                    CPT
                                                </option>
                                                <option value="asylum_ead" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'asylum_ead') ? 'selected' : ''; ?>>
                                                    Asylum EAD
                                                </option>
                                                <option value="tps_ead" <?php echo (isset($data['visa_status']) && $data['visa_status'] === 'tps_ead') ? 'selected' : ''; ?>>
                                                    TPS EAD
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        const dragDropArea = document.querySelector(".drag-drop-area");
                                        const fileInput = document.getElementById("resume-upload");
                                        const fileNameDisplay = document.getElementById("file-name");

                                        dragDropArea.addEventListener("click", function (event) {
                                            event.stopPropagation();
                                            if (event.target !== fileInput) {
                                                fileInput.click();
                                            }
                                        });

                                        dragDropArea.addEventListener("dragover", function (event) {
                                            event.preventDefault();
                                            dragDropArea.style.borderColor = "#007bff";
                                        });

                                        dragDropArea.addEventListener("dragleave", function () {
                                            dragDropArea.style.borderColor = "#ccc";
                                        });

                                        dragDropArea.addEventListener("drop", function (event) {
                                            event.preventDefault();
                                            dragDropArea.style.borderColor = "#ccc";
                                            const files = event.dataTransfer.files;
                                            if (files.length > 0) {
                                                fileInput.files = files;
                                                fileNameDisplay.textContent = files[0].name;
                                            }
                                        });

                                        fileInput.addEventListener("change", function () {
                                            if (fileInput.files.length > 0) {
                                                fileNameDisplay.textContent = fileInput.files[0].name;
                                            }
                                        });
                                    });
                                </script>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
