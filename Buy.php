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
// Fetch product details
$products = [];
$sql = "SELECT product_name, product_images FROM others"; // Ensure this is the correct table name
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
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
        .purchase-heading {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            font-size: 32px;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        /* .purchase-heading::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background-color: #007bff;
            margin: 10px auto 0;
            border-radius: 2px;
        } */
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
    </div>    
    <br>
    <br>
    <section id="buy"> 
        <h2 class="purchase-heading">Purchase Goods</h2>
        <ul id="autowidth2" class="cs-hidden">
            <li class="item-a">
                <div class="purchase-box">
                    <div class="purchase-b-img">
                        <img src="Photos/phone.jpeg" alt="Mobile Phone Image"> 
                    </div>
                    <a href="phone_products.php">
                        <div class="purchase-b-text">
                            <h3>Mobile Phones</h3>
                            <p>Purchase</p>
                        </div>
                    </a>
                </div>    
            </li>
            <li class="item-a">
                <div class="purchase-box">
                    <div class="purchase-b-img">
                        <img src="Photos/5.1.jpeg" alt="Book Image"> 
                    </div>
                    <a href="buy_book.php">
                        <div class="purchase-b-text">
                            <h3>Books</h3>
                            <p>Purchase</p>
                        </div>
                    </a>
                </div>    
            </li>
        </ul>
</section>
<section id="buy">
<h2 class="purchase-heading">Others Goods</h2>
<ul id="autowidth" class="cs-hidden">
<?php
// Include database connection
include('config.php');

// Fetch products from the 'others' table
$sql_products = "SELECT * FROM others";
$stmt_products = $conn->prepare($sql_products);
$stmt_products->execute();
$result_products = $stmt_products->get_result();

// Loop through each product and display it
while ($product = $result_products->fetch_assoc()) {
    $product_images = json_decode($product['product_images']);
    $main_image = !empty($product_images) ? 'sell/' . $product_images[0] : 'Photos/default.jpg';
    echo '
            <li class="item-a">
                <div class="purchase-box">
                    <div class="purchase-b-img">
                        <img src="' . htmlspecialchars($main_image) . '" alt="Product Image">
                    </div>
                    <a href="others_details.php?id=' . $product['id'] . '">
                        <div class="purchase-b-text">
                            <h3>' . htmlspecialchars($product['product_name']) . '</h3>
                            <p>Purchase</p>
                        </div>
                    </a>
                </div>    
            </li>';
        
}
?>
</ul>
</section>

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
