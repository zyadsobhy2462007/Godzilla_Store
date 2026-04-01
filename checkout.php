<?php
session_start();
$page_title = "Secure Checkout";
include("./inc/config.php");
include("./inc/header.php");

// 1. Check if cart exists
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
    <style>
        @keyframes cartPulse {
            0% {
                transform: scale(1);
                filter: drop-shadow(0 0 0px var(--primary-gold));
            }

            50% {
                transform: scale(1.1);
                filter: drop-shadow(0 0 15px var(--primary-gold));
            }

            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 0px var(--primary-gold));
            }
        }

        .animated-cart {
            animation: cartPulse 2s infinite ease-in-out;
            display: inline-block;
            background: linear-gradient(45deg, #ffc107, #ff9800);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .empty-state-glass {
            background: rgba(255, 255, 255, 0.03) !important;
            backdrop-filter: blur(15px);
            padding: 80px 60px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class='container text-center' style='padding:150px 0;'>
        <div class='empty-state-glass'>
            <i class='fa-duotone fa-solid fa-basket-shopping fa-5x mb-4 animated-cart'></i>
            <h2 style='color:#fff; font-weight:800;'>Your cart is empty!</h2>
            <p style='color:rgba(255,255,255,0.6); font-size: 1.1rem;'>Please add some items to your cart to proceed.</p>
            <a href='index.php' class='btn fw-bold btn-cart text-decoration-none d-inline-block mt-4'>
                <i class="fa-solid fa-arrow-left-long me-2"></i> Back to Shop
            </a>
        </div>
    </div>
<?php
    include("inc/footer.php");
    exit;
endif;

// Process cart data
$total = 0;
$cart_items = [];
foreach ($_SESSION['cart'] as $product_id => $qty) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    if ($product) {
        $product['quantity'] = $qty;
        $product['subtotal'] = $product['price'] * $qty;
        $total += $product['subtotal'];
        $cart_items[] = $product;
    }
}

$discount = $_SESSION['applied_coupon']['discount'] ?? 0;
$total_after_discount = $total - $discount;

if (isset($_POST['place_order'])) {
    $name = trim(htmlspecialchars($_POST['name']));
    $phone = trim(htmlspecialchars($_POST['phone']));
    $address = trim(htmlspecialchars($_POST['address']));
    $payment_method = trim(htmlspecialchars($_POST['payment_method']));

    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, total_price, payment_method, coupon_code, discount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $phone, $address, $total_after_discount, $payment_method, $_SESSION['applied_coupon']['code'] ?? null, $discount]);
    $order_id = $pdo->lastInsertId();

    foreach ($cart_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
    }

    $_SESSION['cart'] = [];
    unset($_SESSION['applied_coupon']);

    echo "<div class='container text-center' style='padding:120px 0;'>
            <div class='success-card' style='background:rgba(17,20,27,0.8); backdrop-filter:blur(20px); padding:60px; border-radius:40px; border:1px solid #ffc107; box-shadow: 0 0 50px rgba(255, 193, 7, 0.15);'>
                <div class='check-icon mb-4' style='width:100px; height:100px; background:rgba(40, 167, 69, 0.1); color:#28a745; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;'>
                    <i class='fa-solid fa-circle-check fa-4x'></i>
                </div>
                <h1 style='color:#fff; font-weight:900;'>Order Successful!</h1>
                <p style='color:rgba(255,255,255,0.7); font-size:1.1rem;'>Order ID: <span style='color:#ffc107;'>#$order_id</span>.</br> Thank you for shopping with us!</p>
                <div class='mt-5'>
                    <a href='products.php' class='btn fw-bold btn-cart' style='width:auto; padding:18px 50px; border-radius: 15px;'>Continue Shopping</a>
                </div>
            </div>
          </div>";
    include("inc/footer.php");
    exit;
}
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-gold: #ffc107;
        --dark-bg: #050505;
        --card-bg: rgba(255, 255, 255, 0.03);
        --input-bg: rgba(255, 255, 255, 0.05);
        --border-glass: rgba(255, 255, 255, 0.1);
    }

    body {
        background-color: var(--dark-bg);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .checkout-container {
        padding: 80px 0;
    }

    .section-title {
        font-size: 2.8rem;
        font-weight: 900;
        background: linear-gradient(45deg, #fff, var(--primary-gold));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 50px;
    }

    .checkout-card {
        background: var(--card-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--border-glass);
        border-radius: 30px;
        padding: 40px;
        margin-bottom: 30px;
        transition: 0.3s;
    }

    .card-label {
        color: var(--primary-gold);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.85rem;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
    }

    .card-label i {
        font-size: 1.2rem;
        filter: drop-shadow(0 0 5px rgba(255, 193, 7, 0.4));
    }

    .form-control {
        background: var(--input-bg);
        border: 1px solid var(--border-glass);
        color: #000000 !important;
        border-radius: 15px;
        padding: 18px;
    }

    .payment-methods-grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .payment-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .payment-card.active {
        background: rgba(255, 193, 7, 0.08);
        border-color: var(--primary-gold);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .payment-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--primary-gold);
    }

    .payment-card.active .payment-icon {
        background: var(--primary-gold);
        color: #000;
        box-shadow: 0 0 20px rgba(255, 193, 7, 0.4);
    }

    .status-indicator i {
        font-size: 1.4rem;
        transition: 0.3s;
    }

    .btn-place-order {
        background: linear-gradient(90deg, #b47716, #91521a);
        color: #fff;
        font-weight: 600;
        transition: 0.3s;
        padding: 20px;
        border-radius: 18px;
        width: 100%;
        border: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        box-shadow: 0 10px 30px rgba(255, 193, 7, 0.2);
    }

    .btn-place-order:hover {
        background: linear-gradient(90deg, #e67e22, #f1c40f);
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(241, 196, 15, 1);
        color: black;
    }

    .section-title {
        font-size: 2.8rem;
        font-weight: 900;
        background: linear-gradient(45deg, #fff, var(--primary-gold));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        padding-bottom: 25px;
        margin-bottom: 50px;
        display: inline-block;
        left: 50%;
        transform: translateX(-50%);
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--primary-gold), transparent);
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.6);
    }

    /* --- Responsive Fixes --- */

    @media (max-width: 991.98px) {

        .checkout-card {
            position: relative !important;
            top: 0 !important;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .total-area .h4 {
            font-size: 1.1rem;
        }

        .total-area .h3 {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 576px) {

        .checkout-card {
            padding: 20px 15px !important;
            border-radius: 15px;
        }

        .btn-place-order {
            padding: 15px !important;
            font-size: 0.9rem !important;
            letter-spacing: 1px;
        }

        .mb-4[style*="max-height"] {
            max-height: 250px !important;
        }

        .checkout-card h6 {
            font-size: 0.9rem;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    /* --- Total Area Enhancement --- */
    .total-area {
        background: rgba(255, 193, 7, 0.05);
        padding: 20px;
        border-radius: 20px;
        border: 1px dashed rgba(255, 193, 7, 0.2);
    }

    .text-warning {
        color: var(--primary-gold) !important;
        text-shadow: 0 0 15px rgba(255, 193, 7, 0.4);
    }

    .btn-place-order {
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .btn-place-order::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
        z-index: -1;
    }

    .btn-place-order:hover::before {
        left: 100%;
    }

    /* --- Order Items Polish --- */
    .border-bottom.border-secondary {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding-bottom: 15px !important;
        margin-bottom: 15px !important;
        transition: 0.3s ease;
    }

    .border-bottom.border-secondary:hover {
        background: rgba(255, 193, 7, 0.02);
        padding-left: 10px;
        border-radius: 8px;
    }

    .checkout-card h6 {
        font-size: 1rem;
        letter-spacing: 0.5px;
        color: #efefef;
    }

    .checkout-card span.fw-bold {
        color: #fff;
        font-family: 'Courier New', Courier, monospace;
    }

    /* --- Custom Scrollbar for Order Summary --- */
    .mb-4[style*="overflow-y: auto"]::-webkit-scrollbar {
        width: 6px;
    }

    .mb-4[style*="overflow-y: auto"]::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 10px;
    }

    .mb-4[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, transparent, var(--primary-gold), transparent);
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
    }
</style>

<div class="checkout-container">
    <div class="container">
        <h2 class="section-title text-center">Secure Checkout</h2>
        <div class="row g-5">
            <div class="col-lg-7">
                <form method="POST" id="checkout_form">
                    <div class="checkout-card">
                        <div class="card-label"><i class="fa-solid fa-map-location-dot"></i> Shipping Details</div>
                        <div class="mb-4">
                            <label class="small opacity-50 mb-2">FULL NAME</label>
                            <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="small opacity-50 mb-2">PHONE NUMBER</label>
                                <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="small opacity-50 mb-2">CITY</label>
                                <input type="text" name="city" class="form-control" placeholder="Your City" required>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="small opacity-50 mb-2">SHIPPING ADDRESS</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Full street address..." required></textarea>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <div class="card-label"><i class="fa-solid fa-credit-card"></i> Payment Method</div>
                        <div class="payment-methods-grid">

                            <div class="payment-card active" onclick="selectPayment(this, 'cod')">
                                <input type="radio" name="payment_method" value="cod" checked class="d-none">
                                <div class="payment-icon">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <div class="payment-info">
                                    <span class="d-block fw-bold h5 mb-1">Cash on Delivery</span>
                                    <p class="small opacity-50 mb-0">Direct payment upon delivery.</p>
                                </div>
                                <div class="status-indicator ms-auto"><i class="fa-solid fa-circle-check text-warning"></i></div>
                            </div>

                            <div class="payment-card disabled" onclick="showDisabledAlert()">
                                <div class="payment-icon">
                                    <i class="fa-solid fa-microchip"></i>
                                </div>
                                <div class="payment-info">
                                    <span class="d-block fw-bold h5 mb-1">Card Payment</span>
                                    <p class="small opacity-50 mb-0">Pay with Visa or MasterCard.</p>
                                </div>
                                <div class="status-indicator ms-auto"><i class="fa-solid fa-lock opacity-20"></i></div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="checkout-card" style="top: 100px;">
                    <div class="card-label"><i class="fa-solid fa-file-invoice-dollar"></i> ORDER SUMMARY</div>
                    <div class="mb-4" style="max-height: 300px; overflow-y: auto;">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small class="opacity-50"><i class="fa-solid fa-box-open me-1"></i> Qty: <?php echo $item['quantity']; ?></small>
                                </div>
                                <span class="fw-bold">$<?php echo number_format($item['subtotal'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="total-area mt-4">
                        <div class="d-flex justify-content-between">
                            <span class="h4 fw-bold">Total Amount</span>
                            <span class="h3 fw-bold text-warning">$<?php echo number_format($total_after_discount, 2); ?></span>
                        </div>
                    </div>

                    <button type="submit" form="checkout_form" name="place_order" class="btn-place-order mt-5 fw-900">
                        <i class="fa-solid fa-shield-halved me-2"></i> PLACE ORDER NOW
                    </button>
                    <div class="d-flex justify-content-center gap-3 mt-4 opacity-30">
                        <i class="fa-brands fa-cc-visa fa-2x"></i>
                        <i class="fa-brands fa-cc-mastercard fa-2x"></i>
                        <i class="fa-brands fa-cc-apple-pay fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectPayment(element, value) {
        if (element.classList.contains('disabled')) return;
        document.querySelectorAll('.payment-card').forEach(card => {
            card.classList.remove('active');
            card.querySelector('.status-indicator i').className = 'fa-solid fa-circle-notch opacity-10';
        });
        element.classList.add('active');
        element.querySelector('.status-indicator i').className = 'fa-solid fa-circle-check text-warning';
        element.querySelector('input[type="radio"]').checked = true;
    }

    function showDisabledAlert() {
        Swal.fire({
            title: 'Coming Soon',
            text: 'We are currently integrating secure card payments.',
            icon: 'info',
            background: '#050505',
            color: '#fff',
            confirmButtonColor: '#ffc107'
        });
    }
</script>

<?php include("./inc/footer.php"); ?>