<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - <?= htmlspecialchars($appName) ?></title>
    <?php if(!empty($favicon)): ?>
        <link rel="icon" href="<?= htmlspecialchars($favicon) ?>" type="image/x-icon">
    <?php endif; ?>
    <style>
        body { font-family: sans-serif; margin: 0; display: flex; height: 100vh; }
        .sidebar { width: 250px; background: #333; color: white; padding: 20px; display: flex; flex-direction: column; }
        .sidebar h2 { margin-top: 0; display: flex; align-items: center; }
        .sidebar-logo { max-height: 40px; margin-right: 10px; }
        .sidebar a { color: #ccc; text-decoration: none; padding: 10px; display: block; margin-bottom: 5px; border-radius: 4px; }
        .sidebar a:hover, .sidebar a.active { background: #555; color: white; }
        .main { flex: 1; padding: 20px; background: #f4f4f4; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .status-new { color: #2ecc71; font-weight: bold; }
        .status-preparing { color: #f39c12; font-weight: bold; }
        .status-ready { color: #3498db; font-weight: bold; }
        .status-completed { color: #7f8c8d; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>
        <?php if(!empty($logo)): ?>
            <img src="<?= htmlspecialchars($logo) ?>" class="sidebar-logo" alt="Logo">
        <?php endif; ?>
        Admin
    </h2>
    <a href="index.php?url=admin">Dashboard</a>
    <a href="index.php?url=admin/orders" class="active">Orders</a>
    <a href="index.php?url=admin/products">Products</a>
    <a href="index.php?url=admin/settings">Settings</a>
    <div style="margin-top: auto;">
        <a href="index.php?url=admin/logout" style="color: #e74c3c;">Logout</a>
    </div>
</div>

<div class="main">
    <h1>Orders</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Table</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['customer_name'] ?? 'Guest') ?></td>
                <td><?= ucfirst(htmlspecialchars($order['order_type'] ?? 'dine_in')) ?></td>
                <td><?= htmlspecialchars($order['table_number'] ?? '-') ?></td>
                <td>$<?= number_format($order['total'], 2) ?></td>
                <td class="status-<?= htmlspecialchars($order['status']) ?>">
                    <?= ucfirst(htmlspecialchars($order['status'])) ?>
                </td>
                <td><?= htmlspecialchars($order['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
