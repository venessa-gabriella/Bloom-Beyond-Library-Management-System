
<?php
$conn = new mysqli("localhost", "root", "", "bloom_library_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function message($text, $type = "success") {
    $_SESSION['message'] = $text;
    $_SESSION['message_type'] = $type;
}

?>
