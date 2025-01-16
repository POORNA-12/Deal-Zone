<?php
// Include database connection
include('config.php');

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the product details from the 'others' table
$sql_product = "SELECT * FROM others WHERE id = ?";
$stmt_product = $conn->prepare($sql_product);
$stmt_product->bind_param('i', $product_id);
$stmt_product->execute();
$result_product = $stmt_product->get_result();
$product = $result_product->fetch_assoc();

// Check if product exists
if (!$product) {
    echo "<p>Product not found.</p>";
    exit();
}

// Fetch the user details associated with the product
$user_id = $product['user_id'];
$sql_user = "SELECT * FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="hstyle.css">
    <script>
        let slideIndex = 0;

        function showSlides(n) {
            let slides = document.getElementsByClassName("mySlides");
            if (n >= slides.length) { slideIndex = 0; }
            if (n < 0) { slideIndex = slides.length - 1; }
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex].style.display = "block";
        }

        function plusSlides(n) {
            slideIndex += n;
            showSlides(slideIndex);
        }

        window.onload = function() {
            showSlides(slideIndex);
        };
    </script>
</head>
<body>

<div class="main-container">
<aside class="sidebar">
    <img src="<?php echo isset($user['profile_pic']) && !empty($user['profile_pic']) ? 'uploads/' . htmlspecialchars($user['profile_pic']) : 'default_profile.jpg'; ?>" alt="Profile Picture" class="profile-pic">
    <h2><?php echo isset($user['firstname'], $user['lastname']) ? htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) : 'Unknown User'; ?></h2>
    <br>
    <br>
    <div class="contact-info">
        <p><i class="fas fa-envelope"></i> Gmail: <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'Email not available'; ?></p>
        <p><i class="fas fa-phone-alt"></i> Contact: <?php echo isset($user['contactNumber']) ? htmlspecialchars($user['contactNumber']) : 'Contact not available'; ?></p>
    </div>
</aside>


    <main class="content">
        <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
        <div class="product-image">
            <?php
            // Fetch all images into an array
            $product_images = !empty($product['product_images']) ? json_decode($product['product_images']) : ['https://via.placeholder.com/400'];
            ?>
            
            <!-- Image container for slideshow -->
            <div class="slideshow-container">
                <?php
                foreach ($product_images as $index => $image) {
                    echo '<div class="mySlides">';
                    echo '<img src="sell/' . htmlspecialchars(trim($image)) . '" alt="Product Image" style="width:100%;">';
                    echo '</div>';
                }
                ?>
                
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
        </div>
        <div class="product-info">
            <h2>Product Information</h2>
            <p><strong>Product Name:</strong> <?php echo htmlspecialchars($product['product_name']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
        </div>
        <a href="buy.php">Back to Products</a>
    </main>
</div>

<?php
$conn->close();
?>
</body>
</html>
