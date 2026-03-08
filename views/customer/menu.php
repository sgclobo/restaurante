<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?></title>
    <?php if(!empty($favicon)): ?>
        <link rel="icon" href="<?= htmlspecialchars($favicon) ?>" type="image/x-icon">
    <?php endif; ?>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header { background: #333; color: #fff; padding: 10px; text-align: center; position: sticky; top: 0; z-index: 100; }
        header img { max-height: 50px; vertical-align: middle; }
        header h1 { display: inline; vertical-align: middle; margin-left: 10px; font-size: 1.5em; }
        .container { padding: 15px; max-width: 600px; margin: auto; }
        .category { margin-bottom: 20px; }
        .category h2 { border-bottom: 2px solid #ddd; padding-bottom: 5px; }
        .product { background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 10px; display: flex; align-items: flex-start; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .product img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-right: 15px; }
        .product-info { flex-grow: 1; }
        .product-info h3 { margin: 0 0 5px; font-size: 1.1em; }
        .description { font-size: 0.9em; color: #666; margin-bottom: 5px; }
        .price { color: #2ecc71; font-weight: bold; font-size: 1.1em; }
        .add-btn { background: #2ecc71; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 1em; white-space: nowrap; align-self: center; }
        .variant-select { margin-top: 5px; padding: 5px; border-radius: 4px; border: 1px solid #ddd; }
        #cart-bar { position: fixed; bottom: 0; left: 0; right: 0; background: #fff; border-top: 1px solid #ccc; padding: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); display: none; }
        #cart-total { font-weight: bold; font-size: 1.2em; }
        #checkout-btn { background: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-size: 1em; }

        /* Tab Styles */
        .tab-nav {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            background: #fff;
            padding: 10px 0;
            border-bottom: 2px solid #ddd;
            position: sticky;
            top: 72px; /* Approx header height */
            z-index: 90;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .tab-nav::-webkit-scrollbar { display: none; }
        .tab-nav { -ms-overflow-style: none; scrollbar-width: none; }
        
        .tab-btn {
            background: none;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            color: #666;
            margin: 0 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .tab-btn:hover { color: #2ecc71; }
        .tab-btn.active {
            border-bottom-color: #2ecc71;
            color: #2ecc71;
            font-weight: bold;
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<header>
    <?php if(!empty($logo)): ?>
        <img src="<?= htmlspecialchars($logo) ?>" alt="Logo">
    <?php endif; ?>
    <h1><?= htmlspecialchars($appName) ?></h1>
    <?php if(isset($_SESSION['table_number'])): ?>
        <small>Table: <?= htmlspecialchars($_SESSION['table_number']) ?></small>
    <?php endif; ?>
</header>

<div class="container">
    <div id="order-tracker" style="background:#dff9fb; padding:15px; border-radius:8px; border:1px solid #c7ecee; margin-bottom:20px; display:none; text-align:center;">
        <h3>Order #<span id="track-id"></span> Status: <span id="track-status" style="font-weight:bold; color:#0984e3;">...</span></h3>
        <p id="track-message">Please wait while we prepare your food.</p>
        <button id="manual-reset-btn" onclick="resetOrder()" style="display:none; margin-top:10px; padding:8px 15px; background:#2ecc71; color:white; border:none; border-radius:4px; cursor:pointer;">Place New Order</button>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-nav">
        <?php $isFirst = true; ?>
        <?php foreach ($menu as $catId => $category): ?>
            <?php if (!empty($category['products'])): ?>
                <button class="tab-btn <?= $isFirst ? 'active' : '' ?>" onclick="openCategory(event, 'cat-<?= $catId ?>')">
                    <?= htmlspecialchars($category['name']) ?>
                </button>
                <?php $isFirst = false; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Tab Content -->
    <?php $isFirst = true; ?>
    <?php foreach ($menu as $catId => $category): ?>
        <?php if (!empty($category['products'])): ?>
            <div id="cat-<?= $catId ?>" class="tab-content <?= $isFirst ? 'active' : '' ?>">
                <?php foreach ($category['products'] as $product): ?>
                    <div class="product" data-id="<?= $product['id'] ?>" data-base-price="<?= $product['price'] ?>">
                        <?php if (!empty($product['image_path'])): ?>
                            <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php else: ?>
                            <div style="width:100px; height:100px; background:#eee; border-radius:8px; margin-right:15px; display:flex; align-items:center; justify-content:center; color:#aaa;">No Image</div>
                        <?php endif; ?>
                        
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="description"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                            
                            <?php if (!empty($product['variants'])): ?>
                                <select class="variant-select" onchange="updateProductPrice(this, <?= $product['id'] ?>)">
                                    <?php foreach ($product['variants'] as $variant): ?>
                                        <option value="<?= $variant['id'] ?>" data-price="<?= $variant['price'] ?>" data-name="<?= htmlspecialchars($variant['name']) ?>">
                                            <?= htmlspecialchars($variant['name']) ?> - <?= $currency ?><?= number_format($variant['price'], 2) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>

                            <div class="price" id="price-<?= $product['id'] ?>">
                                <?= $currency ?><?= number_format(!empty($product['variants']) ? $product['variants'][0]['price'] : $product['price'], 2) ?>
                            </div>
                        </div>
                        <button class="add-btn" onclick="prepareAddToCart(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>')">
                            + Add
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php $isFirst = false; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div id="cart-bar">
    <span id="cart-total">Total: <?= $currency ?>0.00</span>
    <button id="checkout-btn" onclick="checkout()">Checkout (<span id="cart-count">0</span>)</button>
</div>

<!-- Checkout Modal -->
<div id="checkout-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:200; align-items:center; justify-content:center;">
    <div style="background:white; padding:20px; border-radius:8px; width:90%; max-width:400px;">
        <h2>Checkout</h2>
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px;">Your Name:</label>
            <input type="text" id="customer-name" style="width:100%; padding:8px; box-sizing:border-box;">
        </div>
        
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px;">Order Type:</label>
            <label style="margin-right:15px;">
                <input type="radio" name="order-type" value="dine_in" checked onchange="toggleTableInput()"> Dine-In
            </label>
            <label>
                <input type="radio" name="order-type" value="takeaway" onchange="toggleTableInput()"> Takeaway
            </label>
        </div>
        
        <div id="table-input-group" style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:5px;">Table Number:</label>
            <input type="text" id="table-number" value="<?= htmlspecialchars($_SESSION['table_number'] ?? '') ?>" style="width:100%; padding:8px; box-sizing:border-box;">
        </div>
        
        <div style="text-align:right;">
            <button onclick="closeCheckoutModal()" style="padding:8px 15px; margin-right:10px; background:#ddd; border:none; border-radius:4px; cursor:pointer;">Cancel</button>
            <button onclick="submitOrder()" style="padding:8px 15px; background:#2ecc71; color:white; border:none; border-radius:4px; cursor:pointer;">Place Order</button>
        </div>
    </div>
</div>

<script>
    function openCategory(evt, catId) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(catId).classList.add("active");
        if (evt) evt.currentTarget.classList.add("active");
    }

    let cart = {};
    const currency = '<?= $currency ?>';
    
    function updateProductPrice(select, productId) {
        const price = select.options[select.selectedIndex].dataset.price;
        document.getElementById(`price-${productId}`).innerText = currency + parseFloat(price).toFixed(2);
    }

    function prepareAddToCart(productId, productName) {
        const productEl = document.querySelector(`.product[data-id="${productId}"]`);
        const variantSelect = productEl.querySelector('.variant-select');
        
        let price = parseFloat(productEl.dataset.basePrice);
        let variantId = null;
        let finalName = productName;

        if (variantSelect) {
            const selectedOption = variantSelect.options[variantSelect.selectedIndex];
            price = parseFloat(selectedOption.dataset.price);
            variantId = selectedOption.value;
            finalName = productName + ' (' + selectedOption.dataset.name + ')';
        }

        addToCart(productId, variantId, finalName, price);
    }

    function addToCart(productId, variantId, name, price) {
        const key = variantId ? `${productId}_${variantId}` : `${productId}`;
        
        if (!cart[key]) {
            cart[key] = { productId: productId, variantId: variantId, name: name, price: price, qty: 0 };
        }
        cart[key].qty++;
        updateCartUI();
    }
    
    function updateCartUI() {
        let total = 0;
        let count = 0;
        for (let id in cart) {
            total += cart[id].price * cart[id].qty;
            count += cart[id].qty;
        }
        
        document.getElementById('cart-total').innerText = 'Total: <?= $currency ?>' + total.toFixed(2);
        document.getElementById('cart-count').innerText = count;
        
        if (count > 0) {
            document.getElementById('cart-bar').style.display = 'flex';
        }
    }
    
    function checkout() {
        if (Object.keys(cart).length === 0) return;
        document.getElementById('checkout-modal').style.display = 'flex';
    }
    
    function closeCheckoutModal() {
        document.getElementById('checkout-modal').style.display = 'none';
    }
    
    function toggleTableInput() {
        const type = document.querySelector('input[name="order-type"]:checked').value;
        const group = document.getElementById('table-input-group');
        if (type === 'takeaway') {
            group.style.display = 'none';
        } else {
            group.style.display = 'block';
        }
    }
    
    function submitOrder() {
        const name = document.getElementById('customer-name').value;
        const type = document.querySelector('input[name="order-type"]:checked').value;
        const table = document.getElementById('table-number').value;
        
        console.log("Submitting order:", { name, type, table, cart });

        if (!name) {
            alert('Please enter your name');
            return;
        }
        if (type === 'dine_in' && !table) {
            alert('Please enter your table number');
            return;
        }
        
        fetch('index.php?url=api/order', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                cart: cart, 
                customerName: name,
                orderType: type,
                table: table 
            })
        })
        .then(res => {
            console.log("Response status:", res.status);
            return res.text().then(text => {
                console.log("Response text:", text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error("Invalid JSON: " + text);
                }
            });
        })
        .then(data => {
            console.log("Data:", data);
            if (data.success) {
                alert('Order Placed! Order ID: #' + data.order_id);
                localStorage.setItem('last_order_id', data.order_id);
                cart = {};
                updateCartUI();
                document.getElementById('cart-bar').style.display = 'none';
                closeCheckoutModal();
                window.location.reload(); 
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
            alert('Network Error: ' + err.message);
        });
    }
    
    // Status Polling
    // Status Polling
    let pollInterval;
    let clearTimer;

    function checkStatus() {
        const orderId = localStorage.getItem('last_order_id');
        if (!orderId) return;
        
        fetch(`index.php?url=api/order/status&id=${orderId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const status = data.status;
                    
                    // Stale Data Check (Page Load / Initial)
                    // If we just loaded the page and it's already completed, clear immediately.
                    // We can distinguish "just loaded" by checking if the tracker is visible yet?
                    // Actually, simpler: if not visible yet and status is completed, clear and don't show.
                    const tracker = document.getElementById('order-tracker');
                    
                    if (status === 'completed') {
                         if (tracker.style.display === 'none') {
                             // It was hidden (e.g. page load), so this is an old completed order.
                             console.log("Found stale completed order. Clearing.");
                             localStorage.removeItem('last_order_id');
                             return; 
                         }

                         // Active completion (user was watching)
                         document.getElementById('track-status').innerText = 'ENJOY YOUR MEAL!';
                         document.getElementById('track-status').style.color = 'green';
                         document.getElementById('track-message').innerText = 'Thank you for your order! This session will clear in 30 seconds.';
                         document.getElementById('manual-reset-btn').style.display = 'inline-block';
                         
                         // Stop polling
                         if (pollInterval) clearInterval(pollInterval);
                         
                         // Set 30s timer to clear and reload if not already set
                         if (!clearTimer) {
                             clearTimer = setTimeout(() => {
                                 resetOrder();
                             }, 30000);
                         }
                    } else {
                        // Show tracker if active order exists
                         tracker.style.display = 'block';
                         document.getElementById('track-id').innerText = orderId;
                         document.getElementById('track-status').innerText = status.toUpperCase();
                         
                         if (status === 'ready') {
                              document.getElementById('track-status').innerText = 'READY FOR PICKUP!';
                              document.getElementById('track-status').style.color = '#e67e22';
                         }
                    }
                } else {
                    // If order not found (maybe db cleared), clear local storage
                    if (data.message === 'Order not found') {
                        localStorage.removeItem('last_order_id');
                        if (pollInterval) clearInterval(pollInterval);
                        document.getElementById('order-tracker').style.display = 'none';
                    }
                }
            })
            .catch(err => console.error("Track error:", err));
    }
    
    function resetOrder() {
        localStorage.removeItem('last_order_id');
        window.location.href = 'index.php?url=menu';
    }

    // Initial check and start polling
    if (localStorage.getItem('last_order_id')) {
        // Run immediately
        checkStatus();
        // and loop
        pollInterval = setInterval(checkStatus, 5000);
    }
</script>

</body>
</html>
