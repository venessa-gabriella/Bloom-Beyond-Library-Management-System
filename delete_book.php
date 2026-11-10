
<?php
require_once "db.php";

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['message'] = "Invalid book selected.";
    $_SESSION['message_type'] = "error";
    header("Location: books.php");
    exit;
}

$check = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM borrow_records WHERE book_id=?"
);

$check->bind_param("i", $id);
$check->execute();

$count = $check->get_result()->fetch_assoc()['total'];

if ($count > 0) {

    $_SESSION['message'] =
        "This book has borrowing history and cannot be deleted.";

    $_SESSION['message_type'] = "error";

} else {

    $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['message'] = "Book deleted successfully!";
    $_SESSION['message_type'] = "success";
}

header("Location: books.php");
exit;
?>
