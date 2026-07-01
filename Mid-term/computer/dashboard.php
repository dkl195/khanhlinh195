<?php
$host = 'localhost';
$dbname = 'computer_management';
$username = 'root';
$password = '';

$message = '';
$error = '';
$editComputer = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_computer'])) {
    $computer_name = trim($_POST['computer_name']);
    $model = trim($_POST['model']);
    $operating_system = trim($_POST['operating_system']);
    $processor = trim($_POST['processor']);
    $memory = (int)$_POST['memory'];
    $available = isset($_POST['available']) ? 1 : 0;

    if ($computer_name === '' || $model === '') {
        $error = "Computer name and model are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO computers (computer_name, model, operating_system, processor, memory, available) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$computer_name, $model, $operating_system, $processor, $memory, $available]);
            $message = "New computer added successfully.";
        } catch (PDOException $e) {
            $error = "Error adding computer: " . $e->getMessage();
        }
    }Z
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_computer'])) {
    $id = (int)$_POST['id'];
    $computer_name = trim($_POST['computer_name']);
    $model = trim($_POST['model']);
    $operating_system = trim($_POST['operating_system']);
    $processor = trim($_POST['processor']);
    $memory = (int)$_POST['memory'];
    $available = isset($_POST['available']) ? 1 : 0;

    if ($computer_name === '' || $model === '') {
        $error = "Computer name and model are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE computers SET computer_name = ?, model = ?, operating_system = ?, processor = ?, memory = ?, available = ? WHERE id = ?");
            $stmt->execute([$computer_name, $model, $operating_system, $processor, $memory, $available, $id]);
            $message = "Computer updated successfully.";
        } catch (PDOException $e) {
            $error = "Error updating computer: " . $e->getMessage();
        }
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM computers WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Computer deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting computer: " . $e->getMessage();
    }
}

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];

    $stmt = $pdo->prepare("SELECT * FROM computers WHERE id = ?");
    $stmt->execute([$id]);
    $editComputer = $stmt->fetch(PDO::FETCH_ASSOC);
}

$search = $_GET['search'] ?? '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM computers WHERE computer_name LIKE ? ORDER BY id ASC");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM computers ORDER BY id ASC");
    $stmt->execute();
}

$computers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Management Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.08);
        }
        h1, h2 {
            margin-bottom: 15px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        input, button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        .status-available {
            color: green;
            font-weight: bold;
        }
        .status-repair {
            color: red;
            font-weight: bold;
        }
        .action-btn {
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            color: white;
            margin-right: 5px;
            display: inline-block;
        }
        .edit-btn {
            background: #28a745;
        }
        .delete-btn {
            background: #dc3545;
        }
        .form-section {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 10px;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Computer Management Dashboard</h1>

    <?php if ($message): ?>
        <div class="message success"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <div class="top-bar">
        <form method="GET">
            <input type="text" name="search" placeholder="Search by computer name" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
            <a href="dashboard.php"><button type="button">Reset</button></a>
        </form>
    </div>

    <div class="form-section">
        <h2>Add New Computer</h2>
        <form method="POST">
            <div class="form-grid">
                <input type="text" name="computer_name" placeholder="Computer Name" required>
                <input type="text" name="model" placeholder="Model" required>
                <input type="text" name="operating_system" placeholder="Operating System">
                <input type="text" name="processor" placeholder="Processor">
                <input type="number" name="memory" placeholder="Memory (GB)">
                <label><input type="checkbox" name="available" checked> Available</label>
            </div>
            <br>
            <button type="submit" name="add_computer">Add Computer</button>
        </form>
    </div>

    <?php if ($editComputer): ?>
    <div class="form-section">
        <h2>Edit Computer</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $editComputer['id'] ?>">
            <div class="form-grid">
                <input type="text" name="computer_name" value="<?= htmlspecialchars($editComputer['computer_name']) ?>" required>
                <input type="text" name="model" value="<?= htmlspecialchars($editComputer['model']) ?>" required>
                <input type="text" name="operating_system" value="<?= htmlspecialchars($editComputer['operating_system']) ?>">
                <input type="text" name="processor" value="<?= htmlspecialchars($editComputer['processor']) ?>">
                <input type="number" name="memory" value="<?= htmlspecialchars($editComputer['memory']) ?>">
                <label><input type="checkbox" name="available" <?= $editComputer['available'] ? 'checked' : '' ?>> Available</label>
            </div>
            <br>
            <button type="submit" name="update_computer">Update Computer</button>
            <a href="dashboard.php"><button type="button">Cancel</button></a>
        </form>
    </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Model</th>
            <th>OS</th>
            <th>CPU</th>
            <th>RAM</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($computers as $computer): ?>
            <tr>
                <td><?= $computer['id'] ?></td>
                <td><?= htmlspecialchars($computer['computer_name']) ?></td>
                <td><?= htmlspecialchars($computer['model']) ?></td>
                <td><?= htmlspecialchars($computer['operating_system']) ?></td>
                <td><?= htmlspecialchars($computer['processor']) ?></td>
                <td><?= htmlspecialchars($computer['memory']) ?> GB</td>
                <td>
                    <?php if ($computer['available']): ?>
                        <span class="status-available">Available</span>
                    <?php else: ?>
                        <span class="status-repair">Under Repair</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a class="action-btn edit-btn" href="dashboard.php?edit=<?= $computer['id'] ?>">Edit</a>
                    <a class="action-btn delete-btn" href="dashboard.php?delete=<?= $computer['id'] ?>" onclick="return confirm('Are you sure you want to delete this computer?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>