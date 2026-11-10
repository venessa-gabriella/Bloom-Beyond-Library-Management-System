
<?php
require_once "db.php";

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {

    $_SESSION['message'] = "Invalid return record.";
    $_SESSION['message_type'] = "error";

    header("Location: returns.php");
    exit;
}

try {

    $conn->begin_transaction();

    // Find borrowing record
    $stmt = $conn->prepare(
        "SELECT book_id, status
         FROM borrow_records
         WHERE id=? FOR UPDATE"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $record = $stmt->get_result()->fetch_assoc();

    if (!$record) {
        throw new Exception("Borrowing record not found.");
    }

    if ($record['status'] == "Returned") {
        throw new Exception("This book has already been returned.");
    }

    $book_id = $record['book_id'];

    // Update borrowing record
    $update = $conn->prepare(
        "UPDATE borrow_records
         SET status='Returned',
             return_date=CURDATE()
         WHERE id=?"
    );

    $update->bind_param("i", $id);
    $update->execute();

    // Increase available book quantity
    $book_update = $conn->prepare(
        "UPDATE books
         SET available_quantity = available_quantity + 1
         WHERE id=?"
    );

    $book_update->bind_param("i", $book_id);
    $book_update->execute();

    $conn->commit();

    $_SESSION['message'] =
        "Thank you for returning the book! Your return has been recorded successfully.";

    $_SESSION['message_type'] = "success";

    header("Location: returns.php");
    exit;

} catch (Throwable $e) {

    $conn->rollback();

    $_SESSION['message'] = $e instanceof mysqli_sql_exception
        ? "The return could not be completed. Please try again."
        : $e->getMessage();

    $_SESSION['message_type'] = "error";

    header("Location: returns.php");
    exit;
}
?>
