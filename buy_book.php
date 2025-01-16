<?php
// Include database connection
include('config.php');

// Check if a search query is provided
$search_query = isset($_POST['search']) ? $_POST['search'] : '';

// Prepare the SQL statement
$sql = "SELECT * FROM books WHERE 1"; // Changed to fetch all books by default

// Modify query if a search query exists
if (!empty($search_query)) {
    $sql .= " AND (title LIKE ? OR author LIKE ?)";
}

// Prepare and bind
$stmt = $conn->prepare($sql);
if (!empty($search_query)) {
    $search_param = "%$search_query%";
    $stmt->bind_param("ss", $search_param, $search_param); // Bind the parameters
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Books</title>
    <link rel="stylesheet" href="pustyle.css">
</head>
<body>
    <!-- <main class="main-container">
        <h1>Search for Books</h1>
        <form method="post" action="buy_books.php">
            <div class="search">
                <input name="search" placeholder="Search..." type="text" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" style="background-color: #4e99e9; color: #fff;">Go</button>
            </div>
        </form>
    </main> -->

    <section id="purchase">
        <div class="purchase-heading">Book Results</div>
        
        <?php if ($result->num_rows > 0): ?>
            <ul class="purchase-box">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="item-a">
                        <div class="purchase-item">
                            <div class="purchase-b-img">
                                <?php 
                                // Set the image source correctly based on your folder structure
                                $imagePath = !empty($row['book_images']) ? explode(',', $row['book_images'])[0] : 'https://via.placeholder.com/300x270';
                                // Add the correct path to the uploads directory
                                $imagePath = 'sell/' . $imagePath; 
                                ?>
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Image of <?php echo htmlspecialchars($row['title']); ?>">
                            </div>
                            <a href="book_details.php?id=<?php echo $row['id']; ?>">
                                <button type="button">View Details</button>
                            </a>
                        </div>    
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No books found.</p>
        <?php endif; ?>

    </section>

    <?php
    $stmt->close(); // Close statement
    $conn->close(); // Close connection
    ?>
</body>
</html>
