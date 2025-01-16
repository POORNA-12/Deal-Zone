<?php
// Start session and include the database connection
session_start();
include 'config.php';

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT firstname, lastname, email, contactNumber, profession, country, state, district, city, landmarks, houseNumber, bio, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

// Fetch the number of products posted by the user
$sql_products = "SELECT COUNT(*) AS product_count FROM products WHERE user_id = ?";
$stmt_products = $conn->prepare($sql_products);
$stmt_products->bind_param('i', $user_id);
$stmt_products->execute();
$result_products = $stmt_products->get_result();
$product_count = ($result_products->num_rows > 0) ? $result_products->fetch_assoc()['product_count'] : 0;

// Fetch new notifications (assuming notifications table exists and tracks new posts)
$sql_notifications = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt_notifications = $conn->prepare($sql_notifications);
$stmt_notifications->bind_param('i', $user_id);
$stmt_notifications->execute();
$result_notifications = $stmt_notifications->get_result();
$notifications = $result_notifications->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="pstyles.css">
</head>
<body>
    <div class="main-container">
        <aside class="sidebar">
            <h1>Profile Picture</h1>
            <div id="image">
                <img id="profilePicSidebar" src="<?php echo htmlspecialchars($user['profile_pic'] ? 'uploads/' . $user['profile_pic'] : 'profile.jpeg'); ?>" alt="Profile Picture" class="image"/>
            </div>
        </aside>
        <main class="content">
            <form method="POST" action="update_profile.php" enctype="multipart/form-data">
                <!-- Profile Picture Section -->
                <div class="profile-pic-section">
                    <img id="profilePic" 
                        src="<?php echo htmlspecialchars($user['profile_pic'] ? 'uploads/' . $user['profile_pic'] : 'profile.jpeg'); ?>" 
                        alt="Profile Picture" class="profile-pic" />
                    <div class="button-container">
                        <button id="changePicBtn" class="btn-secondary" type="button">Change picture</button>
                        <input type="file" id="fileInput" name="profilePic" accept="image/*" style="display: none;" />
                        <button id="deletePicBtn" class="btn-destructive" type="button">Delete picture</button>
                    </div>
                </div>

                <!-- Profile Form -->
                <div>
                    <label for="firstName"><b>First name</b></label>
                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['firstname']); ?>" required />
                </div>
                <div>
                    <label for="lastName"><b>Last name</b></label>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['lastname']); ?>" required />
                </div>
                <div>
                    <label for="email"><b>Email</b></label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly />
                </div>
                <div>
                    <label for="contactNumber"><b>Contact Number</b></label>
                    <input type="tel" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($user['contactNumber']); ?>" />
                </div>
                <div>
                    <label for="profession"><b>Profession</b></label>
                    <input type="text" id="profession" name="profession" value="<?php echo htmlspecialchars($user['profession']); ?>" />
                </div>
                <div>
                    <label for="country"><b>Country</b></label>
                    <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>" />
                </div>
                <div>
                    <label for="state"><b>State</b></label>
                    <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" />
                </div>
                <div>
                    <label for="district"><b>District</b></label>
                    <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($user['district']); ?>" />
                </div>
                <div>
                    <label for="city"><b>City</b></label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" />
                </div>
                <div>
                    <label for="landmarks"><b>Landmarks</b></label>
                    <input type="text" id="landmarks" name="landmarks" value="<?php echo htmlspecialchars($user['landmarks']); ?>" />
                </div>
                <div>
                    <label for="houseNumber"><b>House Number</b></label>
                    <input type="text" id="houseNumber" name="houseNumber" value="<?php echo htmlspecialchars($user['houseNumber']); ?>" />
                </div>
                <div>
                    <label for="bio"><b>Bio</b></label>
                    <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                </div>

                <button type="submit" class="btn-primary">Save Changes</button>
            </form>

            <!-- New Container for Product Posts and Notifications -->
           
        </main>
    </div>
    <main>
            <div class="posts-notifications-container">
                <h2>Your Product Posts</h2>
                <p>You have posted <?php echo htmlspecialchars($product_count); ?> products.</p>

                <h2>New Notifications</h2>
                <?php if (count($notifications) > 0): ?>
                    <ul>
                        <?php foreach ($notifications as $notification): ?>
                            <li><?php echo htmlspecialchars($notification['message']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No new notifications.</p>
                <?php endif; ?>
            </div>
    </main>

    <script>
        document.getElementById('changePicBtn').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePic').src = e.target.result;
                    document.getElementById('profilePicSidebar').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('deletePicBtn').addEventListener('click', function() {
            const placeholderSrc = 'profile.jpeg';
            document.getElementById('profilePic').src = placeholderSrc;
            document.getElementById('profilePicSidebar').src = placeholderSrc;
            // Implement backend logic to delete the picture from the database and filesystem
        });
    </script>
</body>
</html>
