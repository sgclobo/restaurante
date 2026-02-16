<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display - <?= htmlspecialchars($appName) ?></title>
    <?php if(!empty($favicon)): ?>
        <link rel="icon" href="<?= htmlspecialchars($favicon) ?>" type="image/x-icon">
    <?php endif; ?>

    <style>
        body { font-family: monospace; background: #222; color: #fff; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #555; padding-bottom: 10px; margin-bottom: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .card { background: #333; border: 1px solid #444; border-radius: 5px; padding: 15px; }
        .card.status-new { border-left: 5px solid #2ecc71; }
        .card.status-preparing { border-left: 5px solid #f1c40f; }
        .card-header { font-weight: bold; font-size: 1.2em; border-bottom: 1px solid #444; padding-bottom: 8px; margin-bottom: 8px; display: flex; justify-content: space-between; }
        .time { font-size: 0.8em; color: #aaa; }
        .items { list-style: none; padding: 0; margin: 0; }
        .items li { border-bottom: 1px solid #444; padding: 5px 0; display: flex; justify-content: space-between; }
        .items li:last-child { border-bottom: none; }
        .qty { font-weight: bold; margin-right: 10px; color: #eee; }
        .actions { margin-top: 15px; text-align: right; }
        .btn { padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; color: black; font-weight: bold; }
        .btn-prep { background: #f1c40f; }
        .btn-ready { background: #3498db; color: white; }
    </style>
</head>
<body>

<div class="header">
    <h1>KITCHEN DISPLAY</h1>
    <span id="clock">--:--:--</span>
</div>

<div id="orders-grid" class="grid">
    <!-- Orders injected here -->
</div>

<script>
    function updateTime() {
        document.getElementById('clock').innerText = new Date().toLocaleTimeString();
    }
    setInterval(updateTime, 1000);
    updateTime();

    function fetchOrders() {
        console.log("Polling for orders...");
        fetch('index.php?url=api/orders')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderOrders(data.orders);
                }
            })
            .catch(err => console.error("Poll error:", err));
    }

    function renderOrders(orders) {
        const grid = document.getElementById('orders-grid');
        grid.innerHTML = '';
        
        orders.forEach(order => {
            const card = document.createElement('div');
            // Status color + Order Type Border
            // Dine-In: Green Border, Takeaway: Blue Border
            let borderColor = order.order_type === 'takeaway' ? '#3498db' : '#2ecc71';
            
            card.className = `card status-${order.status}`;
            card.style.borderLeft = `5px solid ${borderColor}`;
            
            let actionBtn = '';
            if (order.status === 'new') {
                actionBtn = `<button class="btn btn-prep" onclick="updateStatus(${order.id}, 'preparing')">Start Prep</button>`;
            } else if (order.status === 'preparing') {
                actionBtn = `<button class="btn btn-ready" onclick="updateStatus(${order.id}, 'ready')">Ready</button>`;
            } else if (order.status === 'ready') {
                 actionBtn = `<button class="btn" style="background:#7f8c8d; color:white;" onclick="updateStatus(${order.id}, 'completed')">Complete</button>`;
            }
            
            let itemsHtml = order.items.map(item => `
                <li>
                    <span><span class="qty">${item.quantity}x</span> ${item.product_name}</span>
                </li>
            `).join('');

            let headerInfo = `#${order.id}`;
            if (order.order_type === 'dine_in') {
                headerInfo += ` (Table ${order.table_number})`;
            } else {
                headerInfo += ` (Takeaway)`;
            }

            card.innerHTML = `
                <div class="card-header">
                    <span>${headerInfo}</span>
                    <span class="time">${order.created_at.split(' ')[1]}</span>
                </div>
                <div style="font-size:0.9em; color:#aaa; margin-bottom:5px;">${order.customer_name || 'Guest'}</div>
                <ul class="items">${itemsHtml}</ul>
                <div class="actions">${actionBtn}</div>
            `;
            
            grid.appendChild(card);
        });
    }
    
    // Status Update (We haven't implemented API for this yet, so it won't work but adding hook)
    function updateStatus(id, status) {
        // Need to add API endpoint for status update
        console.log("Update status:", id, status);
        fetch('index.php?url=api/order/update', {
            method: 'POST',
            body: JSON.stringify({ id: id, status: status })
        })
        .then(() => fetchOrders());
    }

    // Poll every 5 seconds
    fetchOrders();
    setInterval(fetchOrders, 5000);
</script>

</body>
</html>
