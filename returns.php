
<?php
require_once "db.php";

$page_title = "Book Returns";

$result = $conn->query(
    "SELECT
        borrow_records.*,
        books.title,
        borrowers.full_name

     FROM borrow_records

     INNER JOIN books
     ON borrow_records.book_id = books.id

     INNER JOIN borrowers
     ON borrow_records.borrower_id = borrowers.id

     ORDER BY borrow_records.id DESC"
);

require "header.php";
?>

<h2 class="page-title">BOOK BORROWING & RETURNS</h2>

<div class="table-box">

    <table>

        <tr>
            <th>Borrower</th>
            <th>Book Title</th>
            <th>Borrow Date</th>
            <th>Due Date</th>
            <th>Return Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>

            <td><?= h($row['full_name']) ?></td>

            <td><?= h($row['title']) ?></td>

            <td><?= h($row['borrow_date']) ?></td>

            <td><?= h($row['due_date']) ?></td>

            <td>
                <?= h($row['return_date'] ?? 'Not yet returned') ?>
            </td>

            <td><?= h($row['status']) ?></td>

            <td>

                <?php if ($row['status'] == "Borrowed"): ?>

                    <a href="return_book.php?id=<?= h($row['id']) ?>"
                       class="btn pink small"
                       onclick="return confirm('Confirm that the borrower has returned this book?')">

                       Return Book

                    </a>

                <?php else: ?>

                    <span style="color:green;font-weight:bold;">
                        Returned
                    </span>

                <?php endif; ?>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php require "footer.php"; ?>
