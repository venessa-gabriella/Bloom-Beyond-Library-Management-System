
</div>

<footer class="footer">

    <h3>BLOOM & BEYOND LIBRARY</h3>

    <p>Read • Learn • Grow</p>

    <p>
        &copy; <?= date("Y") ?>
        Bloom & Beyond Library Management System.
        All Rights Reserved.
    </p>

</footer>

<script>
const notification = document.getElementById("notification");

if (notification) {
    setTimeout(function() {
        notification.style.display = "none";
    }, 5000);
}
</script>

</body>
</html>
