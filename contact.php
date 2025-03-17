<?php
// Start session to access session variables
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Get user data from session if logged in
if ($isLoggedIn) {
    $userName = $_SESSION['name'];
    $userEmail = $_SESSION['email'];
    $userRole = $_SESSION['role'] ?? 'user';

    // Connect to database to get fresh user data
    require_once 'config.php'; // Make sure this file has your database connection

    // Get user data from database
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        // Update session data with fresh database data
        $userName = $userData['name']; // Use the name from database
        $_SESSION['name'] = $userName; // Update session
        
        // If you have a profile_pic column in your users table, you can use it here
        $userProfilePic = $userData['profile_pic'] ?? null;
    }
    $stmt->close();

    // Generate profile image URL (using UI Avatars as a fallback if no profile pic)
    $profileImageUrl = $userProfilePic ? "uploads/profiles/" . $userProfilePic : 
                      "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=0052cc&color=fff";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - KEMOS DONATION</title>
    <link rel="stylesheet" href="contact2.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* User profile styles */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            position: relative;
        }
        
        .username {
            font-weight: 500;
            color: #333;
        }
        
        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }
        
        .logout-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: var(--dark-blue);
        }
        
        /* User dropdown menu */
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 200px;
            z-index: 100;
            margin-top: 10px;
            display: none;
        }
        
        .user-info:hover .user-dropdown {
            display: block;
        }
        
        .user-dropdown-item {
            padding: 12px 15px;
            display: block;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
        }
        
        .user-dropdown-item:hover {
            background-color: var(--light-blue);
        }
        
        .user-dropdown-item i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .user-role {
            font-size: 12px;
            background-color: var(--light-blue);
            color: var(--primary-color);
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 5px;
        }
        
        /* Login/Register buttons for non-logged in users */
        .auth-buttons {
            display: flex;
            gap: 10px;
        }
        
        .auth-btn {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .login-btn {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .login-btn:hover {
            background-color: var(--light-blue);
        }
        
        .register-btn {
            background-color: var(--primary-color);
            color: white;
        }
        
        .register-btn:hover {
            background-color: var(--dark-blue);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 15px;
            }
            
            .user-profile, .auth-buttons {
                margin-top: 15px;
                width: 100%;
                justify-content: center;
            }
            
            .user-dropdown {
                right: 50%;
                transform: translateX(50%);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="/placeholder.svg" alt="">
            KEMOS DONATION
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="donate.php">Donate</a></li>
                <li><a href="contact.php" class="active">Contact Us</a></li>
            </ul>
        </nav>
        
        <?php if ($isLoggedIn): ?>
        <!-- User Profile Section -->
        <div class="user-profile">
            <div class="user-info">
                <span class="username">
                    <?= htmlspecialchars($userName) ?>
                    <span class="user-role"><?= htmlspecialchars(ucfirst($userRole)) ?></span>
                </span>
                <img src="<?= $profileImageUrl ?>" alt="<?= htmlspecialchars($userName) ?>" class="profile-icon">
                
                <!-- Dropdown Menu -->
                <div class="user-dropdown">
                    <a href="profile.php" class="user-dropdown-item">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <a href="donations.php" class="user-dropdown-item">
                        <i class="fas fa-donate"></i> My Donations
                    </a>
                    <a href="settings.php" class="user-dropdown-item">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="logout.php" class="user-dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Donate Now button for non-logged in users -->
        <a href="donate.php" class="fundraiser-btn">Donate Now</a>
        <?php endif; ?>
    </header>

    <section class="hero">
        <div class="overlay">
            <h1>Get in <span>Touch</span></h1>
            <p>We're here to help. Reach out to us with any questions, feedback, or partnership inquiries.</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="left">
                <h2>Contact Us</h2>
                <p>Fill in the form to get in touch with our team. We'll respond as soon as possible.</p>
                <div class="contact-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>123 Charity Lane, Nairobi, Kenya</p>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <p>+254 123 456 789</p>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <p>info@kemosdonation.org</p>
                    </div>
                </div>
                <img src="/placeholder.svg?height=300&width=400&text=Contact" alt="Customer Service">
            </div>
            <div class="right">
                <form id="contactForm">
                    <h3>Send us a message</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" placeholder="Your first name" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" placeholder="Your last name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" placeholder="Your email address" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Company (Optional)</label>
                        <input type="text" id="company" placeholder="Your company name">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" placeholder="Please share what you want us to help with" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Submit Message</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About KEMOS</h3>
                <p>We're on a mission to transform how people give and make a positive impact in communities worldwide.</p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/login/" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://x.com/?lang=en" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://ke.linkedin.com/" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Charity Lane, Nairobi, Kenya</p>
                <p><i class="fas fa-phone"></i> +254 123 456 789</p>
                <p><i class="fas fa-envelope"></i> info@kemosdonation.org</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2025 KEMOS DONATION. All rights reserved.</p>
        </div>
    </footer>

    <script src="contact.js"></script>
</body>
</html>

