
<?php
require_once "db.php";

$page_title = "Borrower Management";

if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $check = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM borrow_records WHERE borrower_id=?"
    );

    $check->bind_param("i", $id);
    $check->execute();

    $count = $check->get_result()->fetch_assoc()['total'];

    if ($id > 0 && $count == 0) {

        $delete = $conn->prepare(
            "DELETE FROM borrowers WHERE id=?"
        );

        $delete->bind_param("i", $id);
        $delete->execute();

        $_SESSION['message'] = "Borrower deleted successfully!";
        $_SESSION['message_type'] = "success";

    } else {

        $_SESSION['message'] =
            "Borrower has borrowing history and cannot be deleted.";

        $_SESSION['message_type'] = "error";
    }

    header("Location: borrowers.php");
    exit;
}

$search = trim($_GET['search'] ?? '');

if ($search != "") {

    $stmt = $conn->prepare(
        "SELECT * FROM borrowers
         WHERE full_name LIKE CONCAT('%',?,'%')
         OR email LIKE CONCAT('%',?,'%')
         OR phone LIKE CONCAT('%',?,'%')"
    );

    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $result = $conn->query(
        "SELECT * FROM borrowers ORDER BY id DESC"
    );
}

require "header.php";
?>

<h2 class="page-title">REGISTERED BORROWERS</h2>

<div class="table-box">

    <a href="add_borrower.php" class="btn">
        + Register Borrower
    </a>

    <form method="GET" class="search-box">

        <input type="text"
               name="search"
               placeholder="Search borrower..."
               value="<?= h($search) ?>">

        <button class="pink">Search</button>

        <a href="borrowers.php" class="btn">Clear</a>

    </form>

    <table>

        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>

            <td><?= h($row['id']) ?></td>

            <td><?= h($row['full_name']) ?></td>

            <td><?= h($row['email']) ?></td>

            <td><?= h($row['phone']) ?></td>

            <td>
                <a class="btn danger small"
                   href="borrowers.php?delete=<?= h($row['id']) ?>"
                   onclick="return confirm('Delete this borrower?')">
                   Delete
                </a>
            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php require "footer.php"; ?>
