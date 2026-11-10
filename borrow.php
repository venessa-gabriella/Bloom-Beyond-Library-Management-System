
<?php
require_once "db.php";

$page_title = "Borrow Book";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $book_id = (int)$_POST['book_id'];
    $borrower_id = (int)$_POST['borrower_id'];
    $borrow_date = $_POST['borrow_date'];
    $due_date = $_POST['due_date'];

    if ($book_id <= 0 || $borrower_id <= 0 ||
        $borrow_date == "" || $due_date == "" ||
        $due_date < $borrow_date) {

        $_SESSION['message'] = "Please enter valid borrowing details.";
        $_SESSION['message_type'] = "error";

    } else {

        try {

            $conn->begin_transaction();

            $stmt = $conn->prepare(
                "SELECT available_quantity FROM books
                 WHERE id=? FOR UPDATE"
            );

            $stmt->bind_param("i", $book_id);
            $stmt->execute();

            $book = $stmt->get_result()->fetch_assoc();

            if (!$book || $book['available_quantity'] < 1) {
                throw new Exception("Book is unavailable.");
            }

            $check = $conn->prepare(
                "SELECT id FROM borrowers WHERE id=?"
            );

            $check->bind_param("i", $borrower_id);
            $check->execute();

            if (!$check->get_result()->fetch_assoc()) {
                throw new Exception("Borrower not found.");
            }

            $insert = $conn->prepare(
                "INSERT INTO borrow_records
                (book_id, borrower_id, borrow_date, due_date)
                VALUES (?, ?, ?, ?)"
            );

            $insert->bind_param(
                "iiss",
                $book_id,
                $borrower_id,
                $borrow_date,
                $due_date
            );

            $insert->execute();

            $update = $conn->prepare(
                "UPDATE books
                 SET available_quantity = available_quantity - 1
                 WHERE id=?"
            );

            $update->bind_param("i", $book_id);
            $update->execute();

            $conn->commit();

            $_SESSION['message'] =
                "Book borrowed successfully! Please remember the return date.";

            $_SESSION['message_type'] = "success";

            header("Location: returns.php");
            exit;

        } catch (Throwable $e) {

            $conn->rollback();

            $_SESSION['message'] = $e instanceof mysqli_sql_exception
                ? "Unable to complete borrowing. Please check your details."
                : $e->getMessage();

            $_SESSION['message_type'] = "error";
        }
    }
}

$books = $conn->query(
    "SELECT * FROM books WHERE available_quantity > 0"
);

$borrowers = $conn->query(
    "SELECT * FROM borrowers ORDER BY full_name"
);

require "header.php";
?>

<h2 class="page-title">BORROW A BOOK</h2>

<form method="POST" class="form-box">

    <label>Select Book</label>

    <select name="book_id" required>

        <option value="">-- Select Book --</option>

        <?php while ($b = $books->fetch_assoc()): ?>

        <option value="<?= h($b['id']) ?>">
            <?= h($b['title']) ?>
            (<?= h($b['available_quantity']) ?> available)
        </option>

        <?php endwhile; ?>

    </select>

    <label>Select Borrower</label>

    <select name="borrower_id" required>

        <option value="">-- Select Borrower --</option>

        <?php while ($p = $borrowers->fetch_assoc()): ?>

        <option value="<?= h($p['id']) ?>">
            <?= h($p['full_name']) ?>
        </option>

        <?php endwhile; ?>

    </select>

    <label>Borrow Date</label>

    <input type="date"
           name="borrow_date"
           value="<?= date('Y-m-d') ?>"
           required>

    <label>Expected Return Date</label>

    <input type="date"
           name="due_date"
           min="<?= date('Y-m-d') ?>"
           required>

    <button type="submit">Confirm Borrowing</button>

</form>

<?php require "footer.php"; ?>
