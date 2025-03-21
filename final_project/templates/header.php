<nav>
    <?php if (isset($_SESSION['role'])): ?>
        <span>歡迎，<?= htmlspecialchars($_SESSION['username']); ?>！</span>
        <a href="logout.php">登出</a>
    <?php else: ?>
    <?php endif; ?>
</nav>
<hr>