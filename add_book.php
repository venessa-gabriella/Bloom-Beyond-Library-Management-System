
<?php
require_once "db.php";

$page_title = "Add Book";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $isbn = trim($_POST['isbn']);
    $quantity = (int)$_POST['quantity'];

    if ($title == "" || $author == "" ||
        $category == "" || $quantity < 1) {

        $_SESSION['message'] = "Please fill in all required fields correctly.";
        $_SESSION['message_type'] = "error";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO books
            (title, author, category, isbn, total_quantity, available_quantity)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssii",
            $title, $author, $category, $isbn,
            $quantity, $quantity
        );

        $stmt->execute();

        $_SESSION['message'] = "Book added successfully!";
        $_SESSION['message_type'] = "success";

        header("Location: books.php");
        exit;
    }
}

require "header.php";
?>

<h2 class="page-title">ADD NEW BOOK</h2>

<form method="POST" class="form-box">

    <label>Book Title</label>
    <input type="text" name="title" required>

    <label>Author Name</label>
    <input type="text" name="author" required>

    <label>Category</label>
    <input type="text" name="category" required>

    <label>ISBN Number</label>
    <input type="text" name="isbn">

    <label>Quantity</label>
    <input type="number" name="quantity" min="1" required>

    <button type="submit">Save Book</button>

    <a href="books.php" class="btn pink">Cancel</a>

</form>

<?php require "footer.php"; ?>
