<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Приветствие</title>
</head>
<body>
<h2>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<a href="logout.php">Выйти</a>
</body>
</html>