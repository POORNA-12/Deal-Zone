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
    $title = $_POST['book-title'];
    $author = $_POST['book-author'];
    $year_of_publication = $_POST['book-year'];
    $price = $_POST['book-price'];
    $genre = $_POST['book-genre'];
    $description = $_POST['book-description'];

    // Handle file uploads for book images
    $book_images = [];
    if (isset($_FILES['book-images'])) {
        $total_files = count($_FILES['book-images']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            $image_name = $_FILES['book-images']['name'][$i];
            $image_tmp = $_FILES['book-images']['tmp_name'][$i];
            $image_path = "uploads/" . basename($image_name);

            // Check if the uploads directory is writable
            if (!is_dir('uploads') || !is_writable('uploads')) {
                die("Uploads directory does not exist or is not writable.");
            }

            if (move_uploaded_file($image_tmp, $image_path)) {
                $book_images[] = $image_path;
            } else {
                echo "Failed to upload image: $image_name<br>";
                echo "Error: " . error_get_last()['message'] . "<br>"; // Show the last error
            }
        }
    }

    // Convert the array of image paths into a comma-separated string
    $book_images_str = implode(",", $book_images);

$stmt = $conn->prepare("INSERT INTO books (user_id, title, author, year_of_publication, price, genre, description, book_images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issdisss", $user_id, $title, $author, $year_of_publication, $price, $genre, $description, $book_images_str);

    if ($stmt->execute()) {
        // Set a session variable to show a success message
        $_SESSION['upload_success'] = "Book posted successfully!";

        // Send notifications to all users
        $message = "A new book '{$title}' by {$author} has been posted!";
        
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
