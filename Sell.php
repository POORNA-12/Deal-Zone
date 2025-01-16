<?php
// Start session and include the database connection
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data to get the profile picture
$user_id = $_SESSION['user_id'];
$sql = "SELECT profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $profilePic = htmlspecialchars($user['profile_pic'] ? 'uploads/' . $user['profile_pic'] : 'Photos/default.jpg'); // Use default if no pic
} else {
    $profilePic = 'Photos/default.jpg'; // Default image if user not found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Container with Sidebar</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/css/lightslider.min.css">
    <style>
        .section-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .product-section {
            /* Existing styles */
            text-align: center;
            border: 2px solid #0b0b0c; 
            border-radius: 10px;     
            padding: 20px; 
            height: 400px;           
            width: 350px;             
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
            transition: transform 0.2s; 
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: rgb(219, 232, 243); 
        }

        .product-section:hover {
            transform: scale(1.05); 
        }

        .product-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 5px; 
        }

        .action-button {
            background-color: #007bff;
            color: white;
            padding: 20px 15px;
            margin-top: 60px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 20px;
        }

        .action-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="#">Exchange</a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#sell">Sell</a></li>
                <li><a href="#buy">Buy</a></li>
                <li class="profile">
                    <a href="profile.php" id="profile-icon" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo $profilePic; ?>" alt="User Profile" />
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main class="main-container">
        <div id="home" class="content">
            <h1><span id="element"></span></h1>
            <p>Here you can exchange your goods with your friends.</p>
        </div>
        <div class="images"></div>
    </main>
    <br>
    <br>
<div class="section-container">
        <div class="product-section">
            <h2>Sell Your Products</h2>
            <img src="Photos/car.png" alt="Sell Products" class="product-image">
            <button onclick="location.href='Sell.php'" targect="blank"class="action-button">Go to Sell Page</button>
        </div>

        <!-- Div for Purchasing -->
        <div class="product-section">
            <h2>Purchase Products</h2>
            <img src="Photos/car.png" alt="Buy Products" class="product-image">
            <button onclick="location.href='buy.php'" targect="blank" class="action-button">Go to Buy Page</button>
        </div>
    </div>    
    <br>
    <br>
    <br>

    <section id="sell"> 
        <h2 class="purchase-heading">Sell Goods</h2>
        <ul id="autowidth" class="cs-hidden">
            <!--<li class="item-a">
                <div class="purchase-box" data-product-type="cars">
                    <div class="purchase-b-img">
                       <img src="Photos/1.jpeg" alt="Car Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Cars</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="motorbikes">
                    <div class="purchase-b-img">
                       <img src="Photos/2.jpeg" alt="Motorbike Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Motorbikes</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="bicycles">
                    <div class="purchase-b-img">
                       <img src="Photos/3.jpeg" alt="Bicycle Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Bicycles</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="autos">
                    <div class="purchase-b-img">
                       <img src="Photos/4.jpeg" alt="Auto Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Autos</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li> -->
            <!-- <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/5.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Watches</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li> -->
            <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/phone.jpeg" alt="Watch Image"> 
                    </div>
                    <a href="sell/7.html">
                        <div class="purchase-b-text">
                            <h3>Phone</h3>
                            <p>Create a Post</p>
                        </div>
                    </a>
                </div>    
            </li>
            <!-- <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/buds.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Ear Bud's</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li> -->
            <!-- <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/tv.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Flat tv's</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/cooler.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Coolers</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/fans.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Fan's</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/Fridge.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Fridge</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li> --> 
            <li class="item-a">
                <div class="purchase-box" data-product-type="books">
                    <div class="purchase-b-img">
                       <img src="Photos/5.1.jpeg" alt="Book Image"> 
                    </div>
                    <a href="sell/6.html">
                    <div class="purchase-b-text">
                        <h3>Books</h3>
                        <p>Create a Post</p>
                    </div>   
                    </a> 
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="acs">
                    <div class="purchase-b-img">
                       <img src="Photos/6.jpeg" alt="AC Image"> 
                    </div>
                    <a href="sell/other.html">
                    <div class="purchase-b-text">
                        <h3>Others</h3>
                        <p>Create a Post</p>
                    </div>   
                    </a>   
                </div>    
            </li>
            <!--<li class="item-a">
                <div class="purchase-box" data-product-type="motorbikes">
                    <div class="purchase-b-img">
                       <img src="Photos/9.jpeg" alt="Motorbike Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Water Purifiers</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="water heaters">
                    <div class="purchase-b-img">
                       <img src="Photos/7.jpeg" alt="Water Heater Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Water Heaters</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="washing machines">
                    <div class="purchase-b-img">
                       <img src="Photos/8.jpeg" alt="Washing Machine Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Washing Machines</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/Grinder.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Grinder's</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/Mixers.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Mixer's</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="watches">
                    <div class="purchase-b-img">
                       <img src="Photos/cookers.jpeg" alt="Watch Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Cooker's</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="electrical parts">
                    <div class="purchase-b-img">
                       <img src="Photos/10.jpeg" alt="Electrical Parts Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Electrical Parts</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box" data-product-type="mechanical parts">
                    <div class="purchase-b-img">
                       <img src="Photos/11.jpeg" alt="Mechanical Parts Image"> 
                    </div>
                    <div class="purchase-b-text">
                        <h3>Mechanical Parts</h3>
                        <p>Create a Post</p>
                    </div>    
                </div>    
            </li> -->
        </ul>
    </section> 
    <br>
    <br>
    <br>
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <a href="#">Exchange</a>
                <p>Your trusted platform for buying and selling</p>
            </div>
            <div class="footer-links">
                <div class="footer-column">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#careers">Careers</a></li>
                        <li><a href="#press">Press</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="#blog">Blog</a></li>
                        <li><a href="#help-center">Help Center</a></li>
                        <li><a href="#privacy">Privacy Policy</a></li>
                        <li><a href="#terms">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contact</h3>
                    <ul>
                        <li><a href="mailto:support@exchange.com">Email Us</a></li>
                        <li><a href="#location">Our Location</a></li>
                        <li><a href="#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Subscribe</h3>
                    <p>Get the latest updates right in your inbox.</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-socials">
                <a href="#"><img src="Photos/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="Photos/twitter.png" alt="Twitter"></a>
                <a href="#"><img src="Photos/instagram.png" alt="Instagram"></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Exchange. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/js/lightslider.min.js"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script>
        var typed = new Typed('#element', {
            strings: ['Exchange Goods.'],
            loop: true,
            typeSpeed: 70,
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize lightSlider for both elements
            $('#autowidth').lightSlider({
                autoWidth: true,
                loop: false,
                onSliderLoad: function() {
                    $('#autowidth').removeClass('cS-hidden');
                }
            });
    
            $('#autowidth2').lightSlider({
                autoWidth: true,
                loop: false,
                onSliderLoad: function() {
                    $('#autowidth2').removeClass('cS-hidden');
                }
            });
        });
    </script>
    
    
</body>
</html>
