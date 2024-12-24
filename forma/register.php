<?php
session_start(); // Начинаем сессию

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, заполнены ли поля
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хешируем пароль

        try {
            // Настройки подключения к базе данных
            $servername = "localhost";
            $dbUsername = "your_db_username"; // Ваше имя пользователя MySQL
            $dbPassword = "your_db_password"; // Ваш пароль MySQL
            $dbname = "mydb"; // Имя вашей базы данных

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Подготавливаем запрос
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            // Выполняем запрос
            if ($stmt->execute()) {
                $_SESSION['message'] = "Вы успешно зарегистрированы!";
                header("Location: login.php");
                exit;
            } else {
                $_SESSION['error'] = "Произошла ошибка при регистрации.";
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
    <title>Регистрация</title>
</head>
<body>
<h2>Форма регистрации</h2>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="username">Имя пользователя:</label><br>
    <input type="text" name="username" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email"><br><br>

    <label for="password">Пароль:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Зарегистрироваться</button>
</form>

<p>Уже есть аккаунт? <a href="login.php">Войти</a></p>

</body>
</html>