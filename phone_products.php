<?php
// Include database connection
include('config.php');

// Check if a search query is provided
$search_query = isset($_POST['search']) ? $_POST['search'] : '';

// Prepare the SQL statement
$sql = "SELECT * FROM products WHERE 1"; // Changed to fetch all products by default

// Modify query if a search query exists
if (!empty($search_query)) {
    $sql .= " AND (brand LIKE ? OR model LIKE ?)";
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
    <title>Buy Phones</title>
    <link rel="stylesheet" href="pustyle.css">
</head>
<body>
    <main id="purchase">
        <header class="purchase-heading">Product Results</header>

        <?php if ($result->num_rows > 0): ?>
            <ul class="purchase-box">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php 
                        // Define image path with a default placeholder
                        $imagePath = !empty($row['phone_images']) ? 'sell/' . explode(',', $row['phone_images'])[0] : 'https://via.placeholder.com/300x270';
                    ?>
                    <li class="item-a">
                        <article class="purchase-item">
                            <div class="purchase-b-img">
                                <img src="<?= htmlspecialchars($imagePath); ?>" alt="Image of <?= htmlspecialchars($row['model']); ?>">
                            </div>
                            <!-- <a href="phone_details.php?id=<?= $row['id']; ?>"> -->
                            <a href="x.php?id=<?= $row['id']; ?>"> 
                                <button type="button">View Details</button>
                            </a>
                        </article>    
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>

    </main>

    <?php
    // Close database resources
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>

