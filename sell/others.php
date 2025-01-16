<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collecting form data
$user_id = 1; // This should come from the logged-in user's session or similar
$product_name = $_POST['product_name'];
$category = $_POST['category'];
$price = $_POST['price'];
$condition = $_POST['condition'];
$description = $_POST['description'];

// Handle product images upload
$product_images = [];
if (isset($_FILES['product_images']) && count($_FILES['product_images']['name']) > 0) {
    foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['product_images']['name'][$key];
        $file_tmp = $_FILES['product_images']['tmp_name'][$key];
        $file_destination = "uploads/" . $file_name;

        // Check for file upload errors
        if ($_FILES['product_images']['error'][$key] == UPLOAD_ERR_OK) {
            // Move uploaded file to the desired directory
            if (move_uploaded_file($file_tmp, $file_destination)) {
                $product_images[] = $file_destination;
            } else {
                echo "Error uploading file: $file_name";
            }
        } else {
            echo "Error in file upload: $file_name";
        }
    }
}
$product_images_json = json_encode($product_images);

// Handle 360° view file (optional)
$view_image = null;
if (isset($_FILES['product_360']) && $_FILES['product_360']['error'] == UPLOAD_ERR_OK) {
    $view_image_name = $_FILES['product_360']['name'];
    $view_image_tmp = $_FILES['product_360']['tmp_name'];
    $view_image_destination = "uploads/" . $view_image_name;
    
    // Move the 360° view image
    if (move_uploaded_file($view_image_tmp, $view_image_destination)) {
        $view_image = $view_image_destination;
    } else {
        echo "Error uploading 360° view image.";
    }
}

// Insert data into the `others` table
$stmt = $conn->prepare("INSERT INTO others (user_id, product_name, category, price, `condition`, description, product_images, view_image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("issdssss", $user_id, $product_name, $category, $price, $condition, $description, $product_images_json, $view_image);

if ($stmt->execute()) {
    // Redirect to the home page with a success message parameter
    header("Location: ../sell.php?status=success");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
