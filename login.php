<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = ''; // Update this if a password is set for MySQL root user
$dbname = 'ecommerce_db';
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: 2.php');
            exit;
        } else {
            echo 'Invalid password.';
        }
    } else {
        echo 'No user found with that email.';
    }
}

$conn->close();
?>
