<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - <?= htmlspecialchars($appName) ?></title>
    <?php if(!empty($favicon)): ?>
        <link rel="icon" href="<?= htmlspecialchars($favicon) ?>" type="image/x-icon">
    <?php endif; ?>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input { padding: 8px; width: 300px; }
        button { padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; }
        .msg { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>

<h1>Settings</h1>
<a href="index.php?url=admin">Back to Dashboard</a>
<hr>

<?php if (isset($success)): ?>
    <p class="msg"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label>Restaurant Name</label>
        <input type="text" name="restaurant_name" value="<?= htmlspecialchars($currentName) ?>" required>
    </div>
    <button type="submit">Save Settings</button>
</form>

</body>
</html>
