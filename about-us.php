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
    <title>About Us - KEMOS DONATION</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="about-us.css">
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
                <li><a href="about-us.php" class="active">About Us</a></li>
                <li><a href="donate.php">Donate</a></li>
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

    <section class="hero about-hero">
        <div class="overlay">
            <h1>About <span>Kemos</span> Donation</h1>
            <p>Learn about our journey, mission, and the impact we're making in communities across Kenya.</p>
        </div>
    </section>

    <section class="our-story">
        <h2 class="section-title">Our Story</h2>
        <div class="story-content">
            <div class="story-text">
                <p>Kelvin Wandera and Moses Mugo saw a major problem in Kenya's charitable sector—many donors were contributing money to causes like Moraya Children's Home, but there was little to no transparency about how those funds were being used.</p>  
                <p>Without clear records or accountability, donors were left in the dark, unsure if their contributions were truly making a difference. Determined to address this issue, they set out to create a web-based system that would track and display donation usage in real-time.</p>  
                <p>What started as an idea to bring clarity to a single orphanage has now grown into a broader mission to ensure transparency and trust in charitable giving across Kenya.</p>
            </div>
            <div class="story-image">
                <img src="https://images.pexels.com/photos/19167495/pexels-photo-19167495/free-photo-of-a-sign-that-says-vote-here-and-a-blue-door.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Kemos Donation volunteers">
            </div>
        </div>
    </section>

    <section class="mission-vision">
        <h2 class="section-title">Our Mission & Vision</h2>
        <div class="mission-vision-content">
            <div class="mission">
                <h3><i class="fas fa-bullseye"></i> Our Mission</h3>
                <p>Our mission is to bring transparency and accountability to charitable donations in Kenya by providing a reliable platform for NGOs that lack clear financial reporting.</p>  
                <p>We aim to bridge the gap between donors and NGOs by ensuring that every contribution is tracked and utilized effectively, fostering trust and maximizing the impact of donations.</p>  
                <p>Through our web-based system, we empower organizations to be more transparent while giving donors the confidence that their generosity is truly making a difference.</p>
            </div>
            <div class="vision">
                <h3><i class="fas fa-eye"></i> Our Vision</h3>
                <p>Our vision is to transform the charitable sector in Kenya by setting a new standard for transparency and accountability in NGO donations.</p>  
                <p>We envision a future where every donor can track how their contributions are spent, ensuring that funds reach the intended beneficiaries and create meaningful change.</p>  
                <p>By leveraging technology, we strive to build a culture of trust and efficiency in charitable giving, empowering NGOs to operate with integrity and donors to give with confidence.</p>
            </div>
        </div>
    </section>

    <section class="featured-donations">
        <h2 class="section-title">Our Donation Programs</h2>
        <p style="text-align: center; max-width: 800px; margin: 0 auto 30px;">Our donation programs are carefully designed to address the most pressing needs in the communities we serve. Each program focuses on a specific area of need, ensuring that our resources are used effectively and efficiently.</p>
        
        <div class="project-grid">
            <div class="project-card">
                <img src="https://wallpaperaccess.com/full/4219101.jpg" alt="Food and Groceries">
                <h3>Food and Groceries</h3>
                <p>Providing nutritious meals to orphanages and families in need.</p>
                <div class="donation-details">
                    <h4>What We Provide:</h4>
                    <ul>
                        <li>Monthly food packages for families</li>
                        <li>Weekly fresh produce deliveries to orphanages</li>
                        <li>Emergency food relief during crises</li>
                        <li>Nutritional supplements for children</li>
                    </ul>
                    
                    <h4>Impact:</h4>
                    <div class="donation-stats">
                        <div class="stat">
                            <h5>5,000+</h5>
                            <p>Meals Provided Monthly</p>
                        </div>
                        <div class="stat">
                            <h5>200+</h5>
                            <p>Families Supported</p>
                        </div>
                        <div class="stat">
                            <h5>20+</h5>
                            <p>Orphanages Served</p>
                        </div>
                    </div>
                </div>
                <a href="donate.php" class="learn-more">Support This Cause</a>
            </div>
            
            <div class="project-card">
                <img src="https://wallpapercave.com/wp/wp2595327.jpg" alt="Clothing and Shoes">
                <h3>Clothing and Shoes</h3>
                <p>Supplying essential clothing to those who lack proper attire.</p>
                <div class="donation-details">
                    <h4>What We Provide:</h4>
                    <ul>
                        <li>Seasonal clothing drives for all ages</li>
                        <li>School uniforms for underprivileged students</li>
                        <li>Professional attire for job seekers</li>
                        <li>Shoes and footwear for children and adults</li>
                    </ul>
                    
                    <h4>Impact:</h4>
                    <div class="donation-stats">
                        <div class="stat">
                            <h5>3,000+</h5>
                            <p>Clothing Items Distributed</p>
                        </div>
                        <div class="stat">
                            <h5>500+</h5>
                            <p>School Uniforms Provided</p>
                        </div>
                        <div class="stat">
                            <h5>1,000+</h5>
                            <p>Pairs of Shoes Donated</p>
                        </div>
                    </div>
                </div>
                <a href="donate.php" class="learn-more">Support This Cause</a>
            </div>
            
            <div class="project-card">
                <img src="https://wallpaperbat.com/img/135836994-educational-background-wallpaper.jpg" alt="Educational Materials">
                <h3>Educational Materials</h3>
                <p>Equipping students with necessary school supplies and resources.</p>
                <div class="donation-details">
                    <h4>What We Provide:</h4>
                    <ul>
                        <li>Textbooks and reference materials</li>
                        <li>School supplies (notebooks, pens, etc.)</li>
                        <li>Educational technology (tablets, computers)</li>
                        <li>Library resources for schools</li>
                    </ul>
                    
                    <h4>Impact:</h4>
                    <div class="donation-stats">
                        <div class="stat">
                            <h5>2,500+</h5>
                            <p>Students Supported</p>
                        </div>
                        <div class="stat">
                            <h5>50+</h5>
                            <p>Schools Assisted</p>
                        </div>
                        <div class="stat">
                            <h5>10,000+</h5>
                            <p>Books Distributed</p>
                        </div>
                    </div>
                </div>
                <a href="donate.php" class="learn-more">Support This Cause</a>
            </div>
        </div>
    </section>

    <section class="team">
        <h2 class="section-title">Meet Our Team</h2>
        <p style="text-align: center; max-width: 800px; margin: 0 auto 30px;">Our dedicated team of professionals and volunteers work tirelessly to ensure that Kemos Donation fulfills its mission and creates lasting impact in the communities we serve.</p>
        
        <div class="team-grid">
            <div class="team-member">
                <img src="/placeholder.svg?height=220&width=220" alt="Moses Mugo">
                <div class="team-member-info">
                    <h3>Moses Mugo</h3>
                    <p>Frontend Web Developer</p>
                    <div class="team-social">
                        <a href="https://ke.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://x.com/?lang=en"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/login/"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="team-member">
                <img src="/placeholder.svg?height=220&width=220" alt="Kelvin Wandera">
                <div class="team-member-info">
                    <h3>Kelvin Wandera</h3>
                    <p>Backend Web Developer</p>
                    <div class="team-social">
                        <a href="https://ke.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://x.com/?lang=en"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.facebook.com/login/"><i class="fab fa-facebook-f"></i></a>
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

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Animate impact numbers in donation stats
            const statNumbers = document.querySelectorAll(".stat h5");
            
            const animateValue = (obj, start, end, duration) => {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    obj.innerHTML = Math.floor(progress * (end - start) + start).toLocaleString() + (obj.getAttribute("data-suffix") || "");
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            };
            
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            const target = entry.target;
                            const value = target.textContent.replace(/,/g, "").replace(/\+/g, "");
                            const endValue = parseInt(value, 10);
                            const suffix = target.textContent.includes("+") ? "+" : "";
                            target.setAttribute("data-suffix", suffix);
                            animateValue(target, 0, endValue, 2000);
                            observer.unobserve(target);
                        }
                    });
                },
                { threshold: 0.5 }
            );
            
            statNumbers.forEach((item) => {
                observer.observe(item);
            });
            
            // Add animation to project cards
            const projectCards = document.querySelectorAll(".project-card");
            projectCards.forEach((card, index) => {
                card.style.opacity = "0";
                card.style.transform = "translateY(20px)";
                card.style.transition = "opacity 0.5s ease, transform 0.5s ease";
                
                setTimeout(
                    () => {
                        card.style.opacity = "1";
                        card.style.transform = "translateY(0)";
                    },
                    200 * (index + 1)
                );
            });
            
            // Add animation to team members
            const teamMembers = document.querySelectorAll(".team-member");
            teamMembers.forEach((member, index) => {
                member.style.opacity = "0";
                member.style.transform = "translateY(20px)";
                member.style.transition = "opacity 0.5s ease, transform 0.5s ease";
                
                setTimeout(
                    () => {
                        member.style.opacity = "1";
                        member.style.transform = "translateY(0)";
                    },
                    200 * (index + 1)
                );
            });
        });
    </script>
</body>
</html>

