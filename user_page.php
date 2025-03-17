<?php
// Start session to access session variables
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// If not logged in, redirect to login page
if (!$isLoggedIn) {
    header("Location: login.php");
    exit();
}

// Get user data from session
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - KEMOS DONATION</title>
    <link rel="stylesheet" href="home.css">
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
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 15px;
            }
            
            .user-profile {
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
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="donate.php">Donate</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
        
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
    </header>

    <section class="hero">
        <div class="overlay">
            <h1>Welcome, <span><?= htmlspecialchars($userName) ?></span>!</h1>
            <p>Thank you for being part of our mission to support communities in need.</p>
            <a href="donate.php" class="cta-btn">Make a Donation</a>
        </div>
    </section>

    <section class="featured-donations">
        <h2 class="section-title">Featured Donations</h2>
        <div class="project-grid">
            <div class="project-card">
                <img src="https://wallpaperaccess.com/full/4219101.jpg" width="300" height="200" alt="Food and Groceries">
                <h3>Food and Groceries</h3>
                <p>Providing nutritious meals to orphanages and families in need.</p>
                <a href="about-us.php" class="learn-more">Learn More</a>
            </div>
            <div class="project-card">
                <img src="https://wallpapercave.com/wp/wp2595327.jpg" width="300" height="200" alt="Clothing and Shoes">
                <h3>Clothing and Shoes</h3>
                <p>Supplying essential clothing to those who lack proper attire.</p>
                <a href="about-us.php" class="learn-more">Learn More</a>
            </div>
            <div class="project-card">
                <img src="https://wallpaperbat.com/img/135836994-educational-background-wallpaper.jpg" width="300" height="200"  alt="Educational Materials">
                <h3>Educational Materials</h3>
                <p>Equipping students with necessary school supplies and resources.</p>
                <a href="about-us.php" class="learn-more">Learn More</a>
            </div>
        </div>
    </section>

    <section class="impact">
        <h2 class="section-title">Our Impact</h2>
        <div class="impact-grid">
            <div class="impact-item">
                <i class="fas fa-users"></i>
                <h3>10,000+</h3>
                <p>Lives Impacted</p>
            </div>
            <div class="impact-item">
                <i class="fas fa-globe-africa"></i>
                <h3>50+</h3>
                <p>Communities Served</p>
            </div>
            <div class="impact-item">
                <i class="fas fa-hand-holding-heart"></i>
                <h3>$1M+</h3>
                <p>Donations Raised</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About KEMOS</h3>
                <p>We're on a mission to transform how people give and make a positive impact in communities worldwide.</p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/login/"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://x.com/?lang=en"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                    <a href="https://ke.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
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

    <script src="script.js"></script>
</body>
</html>