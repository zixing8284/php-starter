<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

$dbPath = getenv('SQLITE_DB_PATH') ?: __DIR__ . '/../database/app.db';
$db = new Database($dbPath);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $db->addVisitor(trim($_POST['name']));
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$db->initialize();
$visitors = $db->getVisitors();
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Starter</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f5f5;
            color: #333;
            padding: 2rem;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .info {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            gap: 0.5rem;
        }

        input[type="text"] {
            flex: 1;
            padding: 0.6rem 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            padding: 0.6rem 1.2rem;
            font-size: 1rem;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 0.6rem 0.8rem;
            border-bottom: 1px solid #eee;
        }

        th {
            color: #666;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .empty {
            color: #999;
            text-align: center;
            padding: 2rem;
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 0.8rem;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>PHP Starter</h1>
        <p class="info">PHP <?= phpversion() ?> &middot; SQLite &middot; Nginx &middot; Docker</p>

        <div class="card">
            <h2>Add Visitor</h2>
            <form method="POST">
                <input type="text" name="name" placeholder="Enter name..." required>
                <button type="submit">Add</button>
            </form>
        </div>

        <div class="card">
            <h2>Visitors (<?= count($visitors) ?>)</h2>
            <?php if (empty($visitors)): ?>
                <p class="empty">No visitors yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visitors as $v): ?>
                            <tr>
                                <td><?= $v['id'] ?></td>
                                <td><?= htmlspecialchars($v['name']) ?></td>
                                <td><?= $v['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <p class="footer">Generated at <?= date('Y-m-d H:i:s') ?></p>
    </div>
</body>

</html>
