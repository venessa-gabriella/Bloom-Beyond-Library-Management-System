
<?php
require_once "db.php";

$page_title = "Edit Book";

$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM books WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$book = $stmt->get_result()->fetch_assoc();

if (!$book) {
    $_SESSION['message'] = "Book not found.";
    $_SESSION['message_type'] = "error";
    header("Location: books.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $isbn = trim($_POST['isbn']);
    $quantity = (int)$_POST['quantity'];

    $borrowed = $book['total_quantity'] -
                $book['available_quantity'];

    if ($title == "" || $author == "" ||
        $category == "" || $quantity < 1 ||
        $quantity < $borrowed) {

        $_SESSION['message'] =
            "Please enter valid details. Total quantity cannot be less than books currently borrowed.";

        $_SESSION['message_type'] = "error";

        header("Location: edit_book.php?id=" . $id);
        exit;
    }

    $available = $quantity - $borrowed;

    $update = $conn->prepare(
        "UPDATE books SET
        title=?, author=?, category=?, isbn=?,
        total_quantity=?, available_quantity=?
        WHERE id=?"
    );

    $update->bind_param(
        "ssssiii",
        $title, $author, $category, $isbn,
        $quantity, $available, $id
    );

    $update->execute();

    $_SESSION['message'] = "Book updated successfully!";
    $_SESSION['message_type'] = "success";

    header("Location: books.php");
    exit;
}

require "header.php";
?>

<h2 class="page-title">EDIT BOOK DETAILS</h2>

<form method="POST" class="form-box">

    <label>Book Title</label>
    <input type="text" name="title"
           value="<?= h($book['title']) ?>" required>

    <label>Author</label>
    <input type="text" name="author"
           value="<?= h($book['author']) ?>" required>

    <label>Category</label>
    <input type="text" name="category"
           value="<?= h($book['category']) ?>" required>

    <label>ISBN</label>
    <input type="text" name="isbn"
           value="<?= h($book['isbn']) ?>">

    <label>Total Quantity</label>
    <input type="number" name="quantity"
           min="1"
           value="<?= h($book['total_quantity']) ?>"
           required>

    <button type="submit">Update Book</button>

    <a href="books.php" class="btn pink">Cancel</a>

</form>

<?php require "footer.php"; ?>
