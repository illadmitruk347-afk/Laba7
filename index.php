<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db.php';

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date = $_POST['event_date'] ?? '';
    $type = $_POST['event_type'] ?? '';
    $price = $_POST['price'] ?? '';

    if (empty($title) || empty($date) || empty($price)) {
        $error = "Будь ласка, заповніть усі обов'язкові поля!";
    } elseif (!is_numeric($price) || $price < 0) {
        $error = "Ціна повинна бути коректним додатним числом!";
    } else {
        $title = htmlspecialchars($title);
        $description = htmlspecialchars($description);
        $type = htmlspecialchars($type);

        $stmt = $conn->prepare("INSERT INTO events (title, description, date, type, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $title, $description, $date, $type, $price);

        if ($stmt->execute()) {
            $message = "Подію успішно додано до бази даних!";
        } else {
            $error = "Помилка збереження: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додавання події</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; }
        form { background: white; padding: 20px; border-radius: 8px; max-width: 400px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, textarea { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .message { color: #155724; background: #d4edda; padding: 10px; border-radius: 4px; max-width: 400px; margin-bottom: 15px; }
        .error { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px; max-width: 400px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>Додати нову подію</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Назва події *</label>
        <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

        <label>Опис</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

        <label>Дата *</label>
        <input type="date" name="event_date" value="<?= htmlspecialchars($_POST['event_date'] ?? '') ?>">

        <label>Тип події</label>
        <select name="event_type">
            <option value="Концерт">Концерт</option>
            <option value="Конференція">Конференція</option>
            <option value="Виставка">Виставка</option>
            <option value="Інше">Інше</option>
        </select>

        <label>Ціна (грн) *</label>
        <input type="text" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">

        <button type="submit">Додати подію</button>
    </form>
</body>
</html>