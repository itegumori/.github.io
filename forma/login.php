<?php
session_start(); // Начинаем сессию

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, заполнены ли поля
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Настройки подключения к базе данных
            $servername = "localhost";
            $dbUsername = "your_db_username"; // Ваше имя пользователя MySQL
            $dbPassword = "your_db_password"; // Ваш пароль MySQL
            $dbname = "mydb"; // Имя вашей базы данных

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Подготавливаем запрос
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Получаем результат
            $user = $stmt->fetch();

            if ($user !== false && password_verify($password, $user['password'])) {
                // Авторизация успешна
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit;
            } else {
                $_SESSION['error'] = "Неверное имя пользователя или пароль.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Ошибка подключения: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Заполните все обязательные поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
</head>
<body>
<h2>Форма входа</h2>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="username">Имя пользователя:</label><br>
    <input type="text" name="username" required><br><br>

    <label for="password">Пароль:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Войти</button>
</form>

<p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>

</body>
</html>