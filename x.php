<?php
// Include database connection
include('config.php');

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the product details from the database
$sql_product = "SELECT * FROM products WHERE id = ?";
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
    <title><?php echo htmlspecialchars($product['brand'] . ' ' . $product['model']); ?></title>
    <link rel="stylesheet" href="hstyle.css">
</head>
<body>

<div class="main-container">
    <!-- Sidebar for User Profile -->
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

    <!-- Main Content for Product Details -->
    <main class="content">
        <h1><?php echo htmlspecialchars($product['brand'] . ' ' . $product['model']); ?></h1>

        <div class="product-image">
            <?php
            // Fetch all images into an array
            $images = !empty($product['phone_images']) ? explode(',', $product['phone_images']) : ['https://via.placeholder.com/400'];
            ?>
            
            <!-- Image container for slideshow -->
            <div class="slideshow-container">
                <?php
                // Loop through each image and display it as a slide
                foreach ($images as $index => $image) {
                    echo '<div class="mySlides">';
                    echo '<img src="sell/' . htmlspecialchars(trim($image)) . '" alt="Product Image" style="width:100%;">';
                    echo '</div>';
                }
                ?>
                
                <!-- Navigation buttons -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
        </div>
        <div class="product-info">
            <h2>Product Information</h2>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
            <p><strong>Storage:</strong> <?php echo htmlspecialchars($product['storage_capacity']); ?> GB</p>
            <p><strong>RAM:</strong> <?php echo htmlspecialchars($product['ram']); ?> GB</p>
        </div>       
        <a href="buy.php">Back to Products</a>
    </main>
</div>
<script>
    let slideIndex = 0;
    showSlides();

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function showSlides() {
        let slides = document.getElementsByClassName("mySlides");
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}
        slides[slideIndex - 1].style.display = "block";
        setTimeout(showSlides, 30000); // Change image every 30 seconds
    }
</script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
