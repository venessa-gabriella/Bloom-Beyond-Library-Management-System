
<?php
if (!isset($page_title)) {
    $page_title = "Dashboard";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= h($page_title) ?> | Bloom & Beyond</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">

    <h1>BLOOM & BEYOND</h1>

    <h2>LIBRARY MANAGEMENT SYSTEM</h2>

    <nav>
        <a href="index.php">Dashboard</a>
        <a href="books.php">Books</a>
        <a href="borrowers.php">Borrowers</a>
        <a href="borrow.php">Borrow Book</a>
        <a href="returns.php">Returns</a>
    </nav>

</header>

<div class="container">

<?php if (isset($_SESSION['message'])): ?>

<div class="alert <?= h($_SESSION['message_type']) ?>"
     id="notification">

    <?= h($_SESSION['message']) ?>

    <button onclick="this.parentElement.remove()">×</button>

</div>

<?php
unset($_SESSION['message']);
unset($_SESSION['message_type']);
endif;
?>
