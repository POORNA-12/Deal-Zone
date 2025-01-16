<?php
// Include your database connection file
include('../config.php'); // Adjust the path if necessary

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the request method is POST
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
    // Check if user ID is set in session
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in.");
    }

    // Get form data
    $user_id = $_SESSION['user_id'];
    $brand = $_POST['phone-brand'];
    $model = $_POST['phone-model'];
    $year_of_manufacture = $_POST['year'];
    $price = $_POST['price'];
    $storage_capacity = $_POST['storage'];
    $ram = $_POST['ram'];
    $color = $_POST['color'];
    $os = $_POST['os'];
    $condition = $_POST['condition'];
    $description = $_POST['phone-description'];

    // Handle file uploads
    $phone_images = [];
    if (isset($_FILES['phone-images'])) {
        $total_files = count($_FILES['phone-images']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            $image_name = $_FILES['phone-images']['name'][$i];
            $image_tmp = $_FILES['phone-images']['tmp_name'][$i];
            $image_path = "uploads/" . basename($image_name);

            // Check if the uploads directory is writable
            if (!is_dir('uploads') || !is_writable('uploads')) {
                die("Uploads directory does not exist or is not writable.");
            }

            if (move_uploaded_file($image_tmp, $image_path)) {
                $phone_images[] = $image_path;
            } else {
                echo "Failed to upload image: $image_name<br>";
                echo "Error: " . error_get_last()['message'] . "<br>"; // Show the last error
            }
        }
    }

    // Handle 360-degree video upload
    $phone_360_view = '';
    if (isset($_FILES['phone-360-view']['name'])) {
        $video_name = $_FILES['phone-360-view']['name'];
        $video_tmp = $_FILES['phone-360-view']['tmp_name'];
        $video_path = "uploads/" . basename($video_name);
        
        if (!is_dir('uploads') || !is_writable('uploads')) {
            die("Uploads directory does not exist or is not writable.");
        }

        if (move_uploaded_file($video_tmp, $video_path)) {
            $phone_360_view = $video_path;
        } else {
            echo "Failed to upload video: $video_name<br>";
            echo "Error: " . error_get_last()['message'] . "<br>"; // Show the last error
        }
    }

    // Convert the array of image paths into a comma-separated string
    $phone_images_str = implode(",", $phone_images);

    // Insert product details into the database
    $stmt = $conn->prepare("INSERT INTO products (user_id, brand, model, year_of_manufacture, price, storage_capacity, ram, color, os, `condition`, description, phone_images, phone_360_view) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issidiiisssss", $user_id, $brand, $model, $year_of_manufacture, $price, $storage_capacity, $ram, $color, $os, $condition, $description, $phone_images_str, $phone_360_view);

    if ($stmt->execute()) {
        // Set a session variable to show a success message
        $_SESSION['upload_success'] = "Product posted successfully!";

        // Send notifications to all users
        $message = "A new product '{$brand} {$model}' has been posted!";
        
        // Fetch all user IDs to send notifications
        $userIdsStmt = $conn->prepare("SELECT id FROM users");
        $userIdsStmt->execute();
        $result = $userIdsStmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            // Insert notification for each user
            $notificationStmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $notificationStmt->bind_param("is", $row['id'], $message);
            $notificationStmt->execute();
            $notificationStmt->close();
        }
        $userIdsStmt->close();

        // Redirect to sell.php on successful insertion
        header("Location: ../sell.php");
        exit(); // Always exit after a header redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "This page can only be accessed via a form submission.";
}
?>
