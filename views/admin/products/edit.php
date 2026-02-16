<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - <?= htmlspecialchars($appName) ?></title>
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
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 10px 20px; border-radius: 4px; border: none; cursor: pointer; color: white; font-size: 1em; }
        .btn-save { background: #2ecc71; }
        .btn-cancel { background: #95a5a6; text-decoration: none; display: inline-block; text-align: center; }
        .preview-img { max-width: 200px; margin-top: 10px; border-radius: 4px; display: block; }
        
        .variants-section { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
        .variant-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
        .variant-row input { flex: 1; }
        .btn-remove { background: #e74c3c; padding: 5px 10px; font-size: 0.9em; border-radius: 4px; border:none; color:white; cursor:pointer;}
        .btn-add-variant { background: #3498db; margin-top: 10px; padding: 8px 15px; border-radius: 4px; border:none; color:white; cursor:pointer;}
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
        <h1>Edit Product</h1>
    </div>

    <div class="container">
        <form action="index.php?url=admin/products/update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
            </div>

            <div class="form-group">
                <label>Image</label>
                <?php if (!empty($product['image_path'])): ?>
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" class="preview-img">
                <?php endif; ?>
                <input type="file" name="image" accept="image/*" style="margin-top: 10px;">
                <p style="font-size:0.8em; color:#666;">Or paste URL:</p>
                <input type="text" name="image_url_text" value="<?= strpos($product['image_path'], 'http') === 0 ? htmlspecialchars($product['image_path']) : '' ?>" placeholder="https://...">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_available" <?= $product['is_available'] ? 'checked' : '' ?>> Available
                </label>
            </div>

            <div class="variants-section">
                <label>Variants (Optional)</label>
                <div id="variants-container">
                    <?php if (!empty($product['variants'])): ?>
                        <?php foreach ($product['variants'] as $index => $variant): ?>
                            <div class="variant-row">
                                <input type="text" name="variants[<?= $index ?>][name]" value="<?= htmlspecialchars($variant['name']) ?>" placeholder="Variant Name" required>
                                <input type="number" step="0.01" name="variants[<?= $index ?>][price]" value="<?= $variant['price'] ?>" placeholder="Price" required>
                                <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">X</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-add-variant" onclick="addVariant()">+ Add Variant</button>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-save">Save Changes</button>
            <a href="index.php?url=admin/products" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
</div>

<script>
    function addVariant() {
        const container = document.getElementById('variants-container');
        // Use current timestamp as index to avoid collisions if deleting/adding
        const index = Date.now(); 
        
        const div = document.createElement('div');
        div.className = 'variant-row';
        div.innerHTML = `
            <input type="text" name="variants[${index}][name]" placeholder="Variant Name (e.g. Large)" required>
            <input type="number" step="0.01" name="variants[${index}][price]" placeholder="Price" required>
            <button type="button" class="btn btn-remove" onclick="this.parentElement.remove()">X</button>
        `;
        
        container.appendChild(div);
    }
</script>

</body>
</html>
