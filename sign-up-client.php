<?php include_once 'includes/header.php'; ?>
<main class="main">
    <!-- Page Title -->
    <div class="page-title">
        <div class="heading">
            <div class="container">
            <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-10">
                <div class="container">
                    <h2 class="text-center">Company Sign Up</h2>
                    <p class="form-description text-center mb-4">
                        Please fill
                        in the required details to set up your company account.
                    </p>
                    <div class="row align-items-center">
                        <div class="col-lg-6 d-flex justify-content-center align-items-center">
                            <img src="assets/img/hero-img.png" class="img-fluid animated" alt="">
                        </div>
                        <div class="col-lg-6">
                            <?php if (!isset($responseData['success']) || !$responseData['success']): ?>
                            <form action="" method="post" id="registration-form" onsubmit="return validateForm();">
                                <input type="hidden" name="action" value="save">
                                <div class="mb-3">
                                    <label for="company-name" class="form-label text-start w-100">Company Name</label>
                                    <input type="text" class="form-control" id="company-name" name="company_name" required value="<?php echo htmlspecialchars($oldValues['company_name'] ?? '', ENT_QUOTES); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="contact-person" class="form-label text-start w-100">Contact Person</label>
                                    <input type="text" class="form-control" id="contact-person" name="contact_person" required value="<?php echo htmlspecialchars($oldValues['contact_person'] ?? '', ENT_QUOTES); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label text-start w-100">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($oldValues['email'] ?? '', ENT_QUOTES); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="contact-number" class="form-label text-start w-100">Contact Number</label>
                                    <input type="text" class="form-control" id="contact-number" name="contact_number" required value="<?php echo htmlspecialchars($oldValues['contact_number'] ?? '', ENT_QUOTES); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_category" class="form-label text-start w-100">Company Category</label>
                                    <?php echo $appUtilities->getSelect('company_categories', 'category', 'company_category', $oldValues['company_category'] ?? ''); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label text-start w-100">Password</label>
                                    <input type="password" class="form-control pr-password" id="password" name="password" required value="<?php echo htmlspecialchars($oldValues['password'] ?? '', ENT_QUOTES); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="confirm-password" class="form-label text-start w-100">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" required value="<?php echo htmlspecialchars($oldValues['confirm_password'] ?? '', ENT_QUOTES); ?>">
                                </div>
                                <div id="res" class="alert alert-danger" style="display:none;"></div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Sign Up</button>
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $(".pr-password").passwordRequirements(); // Ensure this is your intended ID
                                    });

                                    function validateForm() {
                                        $("#res").hide();
                                        let pass = $("#password").val();
                                        let pass2 = $("#confirm-password").val();

                                        if(!$("#password").getpasswordSuccessful()){ // Ensure this function exists and works
                                            $("#res").html('Password is not valid.');
                                            $("#res").show();
                                            $("#password").focus();
                                            return false;
                                        }
                                        else if ($.trim(pass) === '') {
                                            $("#res").html('Please enter a password.');
                                            $("#res").show();
                                            $("#password").focus();
                                            return false;
                                        }
                                        else if(pass !== pass2){
                                            $("#res").html('Passwords do not match.');
                                            $("#res").show();
                                            return false;
                                        }
                                        return true;
                                    }
                                </script>
                            </form>
                            <?php endif; ?>

                            <?php if (isset($responseData)): ?>
                                <div class="alert alert-<?php echo $responseData['success'] ? 'success' : 'danger'; ?>">
                                    <?php echo $responseData['message']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
  </main>

  <?php include_once 'includes/footer.php'; ?>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
<script src="assets/js/main.js"></script>
<script src="assets/js/jquery.passwordRequirements.min.js"></script>
<script>
      function aosInit() {
          AOS.init({
              duration: 600,
              easing: 'ease-in-out',
              once: true,
              mirror: false
          });
      }
      window.addEventListener('load', aosInit);
  </script>
</body>

</html>