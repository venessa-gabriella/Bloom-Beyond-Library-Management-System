
<?php
require_once "db.php";

$page_title = "Dashboard";

$books = $conn->query(
    "SELECT COUNT(*) AS total FROM books"
)->fetch_assoc()['total'];

$borrowers = $conn->query(
    "SELECT COUNT(*) AS total FROM borrowers"
)->fetch_assoc()['total'];

$borrowed = $conn->query(
    "SELECT COUNT(*) AS total FROM borrow_records WHERE status='Borrowed'"
)->fetch_assoc()['total'];

$returned = $conn->query(
    "SELECT COUNT(*) AS total FROM borrow_records WHERE status='Returned'"
)->fetch_assoc()['total'];

require "header.php";
?>

<h2 class="page-title">
    Welcome to Bloom & Beyond Library
</h2>

<div class="cards">

    <div class="card">
        <h3>Total Books</h3>
        <h1><?= h($books) ?></h1>
    </div>

    <div class="card">
        <h3>Registered Borrowers</h3>
        <h1><?= h($borrowers) ?></h1>
    </div>

    <div class="card">
        <h3>Borrowed Books</h3>
        <h1><?= h($borrowed) ?></h1>
    </div>

    <div class="card">
        <h3>Returned Books</h3>
        <h1><?= h($returned) ?></h1>
    </div>

</div>

<br>

<div class="table-box">
    <h2 style="color:#98144f;">Quick Actions</h2>

    <a href="add_book.php" class="btn">Add Book</a>

    <a href="add_borrower.php" class="btn pink">
        Register Borrower
    </a>

    <a href="borrow.php" class="btn">
        Borrow Book
    </a>

    <a href="returns.php" class="btn pink">
        View Returns
    </a>
</div>

<?php require "footer.php"; ?>
