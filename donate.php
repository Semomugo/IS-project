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
    <title>Donate - KEMOS DONATION</title>
    <link rel="stylesheet" href="donate.css">
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
                <li><a href="donate.php" class="active">Donate</a></li>
                <li><a href="contact.php">Contact Us</a></li>
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
        <!-- Login/Register Buttons for non-logged in users -->
        <div class="auth-buttons">
            <a href="login.php" class="auth-btn login-btn">Login</a>
            <a href="register.php" class="auth-btn register-btn">Register</a>
        </div>
        <?php endif; ?>
    </header>

    <section class="donation-hero">
        <div class="overlay">
            <h1>Your <span>Generosity</span> Makes a Difference</h1>
            <p>Every donation helps us support communities in need and create lasting change.</p>
        </div>
    </section>

    <section class="donation-form-section">
        <div class="container">
            <h2 class="section-title">Make a Donation</h2>
            <div class="donation-container">
                <div class="donation-info">
                    <h3>Why Donate?</h3>
                    <p>Your contribution helps us provide essential services to communities in need:</p>
                    <ul>
                        <li><i class="fas fa-utensils"></i> Food and groceries for families</li>
                        <li><i class="fas fa-tshirt"></i> Clothing and shoes for those in need</li>
                        <li><i class="fas fa-book"></i> Educational materials for students</li>
                        <li><i class="fas fa-hand-holding-medical"></i> Medical supplies and healthcare</li>
                    </ul>
                    <div class="donation-impact">
                        <p>Your donation directly impacts lives:</p>
                        <div class="impact-examples">
                            <div class="impact-example">
                                <span>$25</span>
                                <p>Provides meals for a family for a week</p>
                            </div>
                            <div class="impact-example">
                                <span>$50</span>
                                <p>Supplies school materials for 5 children</p>
                            </div>
                            <div class="impact-example">
                                <span>$100</span>
                                <p>Funds essential medical supplies</p>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="donationForm" class="donation-form">
                    <div class="form-group">
                        <label for="firstName">First Name*</label>
                        <input type="text" id="firstName" name="firstName" required>
                        <span class="error-message" id="firstNameError"></span>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name*</label>
                        <input type="text" id="lastName" name="lastName" required>
                        <span class="error-message" id="lastNameError"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address*</label>
                        <input type="email" id="email" name="email" required>
                        <span class="error-message" id="emailError"></span>
                    </div>
                    <div class="form-group">
                        <label for="phone">Mobile Number*</label>
                        <input type="tel" id="phone" name="phone" required>
                        <span class="error-message" id="phoneError"></span>
                    </div>
                    <div class="form-group">
                        <label for="country">Country*</label>
                        <select id="country" name="country" required>
                            <option value="">Select Country</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Uganda">Uganda</option>
                            <option value="Tanzania">Tanzania</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="South Africa">South Africa</option>
                            <option value="USA">United States</option>
                            <option value="UK">United Kingdom</option>
                            <option value="Canada">Canada</option>
                            <option value="Other">Other</option>
                        </select>
                        <span class="error-message" id="countryError"></span>
                    </div>
                    <div class="form-group">
                        <label for="state">State/Province*</label>
                        <input type="text" id="state" name="state" required>
                        <span class="error-message" id="stateError"></span>
                    </div>
                    <div class="form-group full-width">
                        <label for="address">Street Address*</label>
                        <input type="text" id="address" name="address" required>
                        <span class="error-message" id="addressError"></span>
                    </div>
                    <div class="form-group full-width">
                        <label for="amount">Donation Amount*</label>
                        <div class="amount-options">
                            <div class="amount-option">
                                <input type="radio" id="amount25" name="amount" value="25">
                                <label for="amount25">$25</label>
                            </div>
                            <div class="amount-option">
                                <input type="radio" id="amount50" name="amount" value="50">
                                <label for="amount50">$50</label>
                            </div>
                            <div class="amount-option">
                                <input type="radio" id="amount100" name="amount" value="100">
                                <label for="amount100">$100</label>
                            </div>
                            <div class="amount-option">
                                <input type="radio" id="amountCustom" name="amount" value="custom">
                                <label for="amountCustom">Custom</label>
                            </div>
                        </div>
                        <div id="customAmountContainer" class="custom-amount-container">
                            <span class="currency-symbol">$</span>
                            <input type="number" id="customAmount" name="customAmount" min="1" placeholder="Enter amount">
                        </div>
                        <span class="error-message" id="amountError"></span>
                    </div>
                    <div class="form-group full-width">
                        <button type="submit" class="donate-submit-btn">Complete Donation</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="donation-faq">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is my donation tax-deductible?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, KEMOS DONATION is a registered non-profit organization, and all donations are tax-deductible to the extent allowed by law.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How is my donation used?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Your donation directly supports our programs providing food, clothing, educational materials, and other essential services to communities in need.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Can I make a recurring donation?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, you can set up a recurring donation by contacting our team at info@kemosdonation.org.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is my payment information secure?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we use industry-standard encryption and security measures to protect your personal and payment information.</p>
                    </div>
                </div>
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

    <script src="donate.js"></script>
</body>
</html>

