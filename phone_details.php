<?php
// Include database connection
include('config.php');

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the product details from the database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p>Product not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['brand'] . ' ' . $product['model']); ?></title>
  <link rel="stylesheet" href="pdstyle.css"> 
</head>
<body>
<main class="-details-container">
    <h1><?php echo htmlspecialchars($product['brand'] . ' ' . $product['model']); ?></h1>

  </main>
  <main class="product-details-container">
    <h1><?php echo htmlspecialchars($product['brand'] . ' ' . $product['model']); ?></h1>
    <div class="product-image">
        <div class="slideshow-container">
            <?php 
            // Fetch all images into an array
            $images = $product['phone_images'] ? explode(',', $product['phone_images']) : ['https://via.placeholder.com/300x270'];

            // Create slideshow images
            foreach ($images as $index => $image) {
                $imagePath = 'sell/' . trim($image);
                echo '<div class="mySlides fade">
                        <img src="' . htmlspecialchars($imagePath) . '" alt="Product Image ' . ($index + 1) . '">
                    </div>';
            }
            ?>
        </div>
    
        <!-- Video section -->
        <!-- <div class="video-container">
            <h2>Product Video</h2>
            <video controls>
                <?php 
                // Assuming the video filename is stored in the database in a field like 'video_file'
                $videoFile = htmlspecialchars($product['phone-360-view']); // Ensure 'video_file' is the correct field name
                $videoPath = 'sell/' . $videoFile; // Define video path in the 'sell/uploads' directory
                
                // Check if the video file exists before displaying
                if (!empty($videoFile) && file_exists($videoPath)) {
                    echo '<source src="' . $videoPath . '" type="video/mp4">'; // Update with the correct video path
                } else {
                    echo '<p>Video not available.</p>'; // Message if video is not available
                }
                ?>
                Your browser does not support the video tag.
            </video>
        </div> -->

    
        <!-- Slideshow Navigation -->
        <div style="text-align:center">
            <?php 
            // Create navigation dots
            for ($i = 0; $i < count($images); $i++) {
                echo '<span class="dot" onclick="currentSlide(' . ($i + 1) . ')"></span>'; 
            }
            ?>
        </div>
    </div>

    <div class="product-info">
      <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
      <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
      <p><strong>Storage:</strong> <?php echo htmlspecialchars($product['storage_capacity']); ?> GB</p>
      <p><strong>RAM:</strong> <?php echo htmlspecialchars($product['ram']); ?> GB</p>
    </div>
    <a href="buy.php">Back to Products</a>
  </main>
  <script>
let slideIndex = 0;
showSlides();

function showSlides() {
    const slides = document.getElementsByClassName("mySlides");
    const dots = document.getElementsByClassName("dot");
    
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }
    
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}    

    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    
    slides[slideIndex - 1].style.display = "block";  
    dots[slideIndex - 1].className += " active";
    
    setTimeout(showSlides, 3000); // Change image every 3 seconds
}

// Function to control slide display
function currentSlide(n) {
    slideIndex = n - 1; // Adjust for zero-based index
    showSlides();
}
</script>

</body>
</html>

<?php
$conn->close();
?>
