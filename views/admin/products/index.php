<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - <?= htmlspecialchars($appName) ?></title>
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
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .btn { padding: 5px 10px; border-radius: 4px; text-decoration: none; color: white; font-size: 0.9em; }
        .btn-edit { background: #3498db; }
        .btn-add { background: #2ecc71; padding: 10px 20px; font-size: 1em; }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
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
        <h1>Products</h1>
        <a href="index.php?url=admin/products/create" class="btn btn-add">+ Add New Product</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td>
                    <?php if (!empty($product['image_path'])): ?>
                        <img src="<?= htmlspecialchars($product['image_path']) ?>" class="img-thumb" alt="Product Image">
                    <?php else: ?>
                        <div style="width:50px;height:50px;background:#eee;display:flex;align-items:center;justify-content:center;font-size:0.7em;color:#aaa;">No Img</div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                <td>$<?= number_format($product['price'], 2) ?></td>
                <td>
                    <a href="index.php?url=admin/products/edit&id=<?= $product['id'] ?>" class="btn btn-edit">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
