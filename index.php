<?php
session_start();

// Get error messages from session
$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? '', // Fixed: was using login_error for both
];

// Get active form from session
$activeForm = $_SESSION['active_form'] ?? 'login';

// Clear session variables AFTER retrieving them
if (isset($_SESSION['login_error'])) unset($_SESSION['login_error']);
if (isset($_SESSION['register_error'])) unset($_SESSION['register_error']);
if (isset($_SESSION['active_form'])) unset($_SESSION['active_form']);
// Don't unset all session data with session_unset() as it would log out the user

// Helper functions
function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : ''; // Fixed: parameter name case mismatch
}
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="auth.php" method="post"> <!-- Changed to auth.php to match previous file -->
                <h2>Login</h2>
                <?= showError($errors['login']); ?>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <p>Don't Have an Account? <a href="#" class="form-switch" data-form="register-form">Register</a></p>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="auth.php" method="post"> <!-- Changed to auth.php to match previous file -->
                <h2>Register</h2>
                <?= showError($errors['register']); ?>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role" required>
                    <option value="">---Select Role---</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="register">Register</button>
                <p>Already Have an Account? <a href="#" class="form-switch" data-form="login-form">Log in</a></p>
            </form>
        </div>
    </div>

    <!-- Added JavaScript for form switching -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show the active form on page load
        const activeForm = '<?= $activeForm ?>';
        document.querySelectorAll('.form-box').forEach(form => {
            form.classList.remove('active');
        });
        document.getElementById(activeForm + '-form')?.classList.add('active');
        
        // Add click handlers for form switching
        document.querySelectorAll('.form-switch').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetForm = this.getAttribute('data-form');
                
                // Hide all forms
                document.querySelectorAll('.form-box').forEach(form => {
                    form.classList.remove('active');
                });
                
                // Show target form
                document.getElementById(targetForm).classList.add('active');
            });
        });
    });
    </script>
</body>

</html>