<?php
// Include database connection
include('config.php');

// Get the book ID from the URL
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the book details from the database
$sql_book = "SELECT * FROM books WHERE id = ?";
$stmt_book = $conn->prepare($sql_book);
$stmt_book->bind_param('i', $book_id);
$stmt_book->execute();
$result_book = $stmt_book->get_result();
$book = $result_book->fetch_assoc();

// Check if book exists
if (!$book) {
    echo "<p>Book not found.</p>";
    exit();
}

// Fetch the user details associated with the book
$user_id = $book['user_id'];
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
    <title><?php echo htmlspecialchars($book['title']); ?></title>
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
        <h1><?php echo htmlspecialchars($book['title']); ?></h1>
        <div class="product-image">
            <?php
            // Fetch all images into an array
            $images = !empty($book['book_images']) ? explode(',', $book['book_images']) : ['https://via.placeholder.com/400'];
            ?>
            
            <!-- Image container for slideshow -->
            <div class="slideshow-container">
                <?php
                foreach ($images as $index => $image) {
                    echo '<div class="mySlides">';
                    echo '<img src="sell/' . htmlspecialchars(trim($image)) . '" alt="Product Image" style="width:100%;">';
                    echo '</div>';
                }
                ?>
                
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
        </div>
        <div class="book-info">
            <h2>Book Information</h2>
            <p><strong>Title:</strong> <?php echo htmlspecialchars($book['title']); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
            <p><strong>Year of Publication:</strong> <?php echo htmlspecialchars($book['year_of_publication']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($book['description']); ?></p>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars($book['price']); ?></p>
        </div>
        <a href="buy.php">Back to Books</a>
    </main>
</div>

<?php
$conn->close();
?>
</body>
</html>
