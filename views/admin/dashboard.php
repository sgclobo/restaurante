<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($appName) ?></title>
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
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
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
    <a href="index.php?url=admin/orders">Orders</a>
    <a href="index.php?url=admin/products" class="active">Products</a>
    <a href="index.php?url=admin/settings">Settings</a>
    <div style="margin-top: auto;">
        <a href="index.php?url=admin/logout" style="color: #e74c3c;">Logout</a>
    </div>
</div>

<div class="main">
    <div class="header">
        <h1>Dashboard</h1>
        <span>Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
    </div>

    <div class="card">
        <h3>Quick Stats</h3>
        <p>Total Orders Today: 0 (Placeholder)</p>
        <p>Active Orders: <span id="active-count">0</span></p>
    </div>
</div>

</body>
</html>
