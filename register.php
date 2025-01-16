<?php
// Connect to the database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'ecommerce_db';
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (firstname, lastname, email, password) 
            VALUES ('$firstname', '$lastname', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        header('Location: login.html');
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }
}

$conn->close();
?>
