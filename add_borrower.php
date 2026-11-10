
<?php
require_once "db.php";

$page_title = "Register Borrower";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || $phone == "") {

        $_SESSION['message'] = "Please enter valid borrower details.";
        $_SESSION['message_type'] = "error";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO borrowers
            (full_name, email, phone)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sss", $name, $email, $phone);
        $stmt->execute();

        $_SESSION['message'] = "Borrower registered successfully!";
        $_SESSION['message_type'] = "success";

        header("Location: borrowers.php");
        exit;
    }
}

require "header.php";
?>

<h2 class="page-title">REGISTER NEW BORROWER</h2>

<form method="POST" class="form-box">

    <label>Full Name</label>
    <input type="text" name="full_name" required>

    <label>Email Address</label>
    <input type="email" name="email" required>

    <label>Phone Number</label>
    <input type="text" name="phone" required>

    <button type="submit">Register Borrower</button>

    <a href="borrowers.php" class="btn pink">Cancel</a>

</form>

<?php require "footer.php"; ?>
