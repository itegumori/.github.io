<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root"; // Имя пользователя по умолчанию для XAMPP
$password = "";     // Пароль по умолчанию пуст
$dbname = "mydb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Получение данных из формы
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хеширование пароля

    // Запрос на вставку данных
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    
    if ($stmt->execute()) {
        echo "Пользователь успешно зарегистрирован!";
    } else {
        echo "Произошла ошибка при регистрации.";
    }
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}
?>