
<?php
require_once "db.php";

$page_title = "Manage Books";

$search = trim($_GET['search'] ?? '');

if ($search != "") {

    $stmt = $conn->prepare(
        "SELECT * FROM books
         WHERE title LIKE CONCAT('%',?,'%')
         OR author LIKE CONCAT('%',?,'%')
         OR category LIKE CONCAT('%',?,'%')
         ORDER BY id DESC"
    );

    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $result = $conn->query(
        "SELECT * FROM books ORDER BY id DESC"
    );
}

require "header.php";
?>

<h2 class="page-title">BOOK MANAGEMENT</h2>

<div class="table-box">

    <a href="add_book.php" class="btn">
        + Add New Book
    </a>

    <form method="GET" class="search-box">

        <input type="text"
               name="search"
               placeholder="Search book title, author or category..."
               value="<?= h($search) ?>">

        <button type="submit" class="pink">
            Search
        </button>

        <a href="books.php" class="btn">
            Clear
        </a>

    </form>

    <table>

        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Total</th>
            <th>Available</th>
            <th>Actions</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>

            <?php while ($row = $result->fetch_assoc()): ?>

            <tr>

                <td><?= h($row['id']) ?></td>

                <td><?= h($row['title']) ?></td>

                <td><?= h($row['author']) ?></td>

                <td><?= h($row['category']) ?></td>

                <td><?= h($row['total_quantity']) ?></td>

                <td><?= h($row['available_quantity']) ?></td>

                <td>

                    <a class="btn small"
                       href="edit_book.php?id=<?= h($row['id']) ?>">
                       Edit
                    </a>

                    <a class="btn danger small"
                       href="delete_book.php?id=<?= h($row['id']) ?>"
                       onclick="return confirm('Are you sure you want to delete this book?')">
                       Delete
                    </a>

                </td>

            </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">No books found.</td>
            </tr>

        <?php endif; ?>

    </table>

</div>

<?php require "footer.php"; ?>
