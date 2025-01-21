<?php include_once 'includes/header.php'; ?>

<main class="main">
    <!-- Page Title -->
    <div class="page-title">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-10">
                        <div class="container">
                            <h2 class="text-center">Login</h2>
                            <p class="form-description text-center mb-4">
                                Please fill in your login details to access your account.
                            </p>
                            <div class="row align-items-center">
                                <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                    <img src="assets/img/hero-img.png" class="img-fluid animated" alt="Login">
                                </div>
                                <div class="col-lg-6">
                                    <?php if (!isset($responseData['success']) || !$responseData['success']): ?>
                                        <form action="" method="post" id="login-form">
                                            <input type="hidden" name="action" value="user_login">

                                            <!-- Email Address -->
                                            <div class="mb-3">
                                                <label for="email" class="form-label text-start w-100">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($oldValues['email'] ?? '', ENT_QUOTES); ?>">
                                            </div>

                                            <!-- Password -->
                                            <div class="mb-3">
                                                <label for="password" class="form-label text-start w-100">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                            </div>

                                            <!-- User Type -->
                                            <div class="mb-3">
                                                <label class="form-label text-start w-100">Log in as:</label>
                                                <div class="d-inline-block">
                                                    <input type="radio" id="Recruiters" name="user_type" value="Recruiters" checked="checked" required>
                                                    <label for="Recruiters">Recruiters</label>
                                                </div>
                                                <div class="d-inline-block">
                                                    <input type="radio" id="client" name="user_type" value="client" required>
                                                    <label for="client">Client</label>
                                                </div>
                                            </div>

                                            <!-- Form Submission -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Log In</button>
                                            </div>
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
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>