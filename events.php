<?php
require 'db.php';

$sort = $_GET['sort'] ?? 'date';
$allowedSorts = ['date', 'title', 'price'];
if (!in_array($sort, $allowedSorts)) {
    $sort = 'date';
}

$sql = "SELECT * FROM events ORDER BY $sort ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список подій</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
        a.btn { display: inline-block; padding: 8px 12px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; margin-bottom: 10px; }
        a.btn:hover { background: #5a6268; }
    </style>
</head>
<body>
    <h2>Список подій</h2>
    <a href="index.php" class="btn">< Додати нову подію</a>
    
    <p>Сортувати за: 
        <a href="events.php?sort=date">Датою</a> | 
        <a href="events.php?sort=title">Назвою</a> | 
        <a href="events.php?sort=price">Ціною</a>
    </p>

    <table>
        <tr>
            <th>ID</th>
            <th>Назва</th>
            <th>Опис</th>
            <th>Дата</th>
            <th>Тип</th>
            <th>Ціна (грн)</th>
            <th>Дата створення</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= htmlspecialchars($row['type']) ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Немає даних для відображення.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>