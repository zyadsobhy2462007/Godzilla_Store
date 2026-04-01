<?php
session_start();
$page_title = "Your Cart";
include("./inc/config.php");

// 1. Add Product (AJAX)
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'cart_count' => array_sum($_SESSION['cart'])]);
    exit;
}

// 2. Handle Removal (Specific Qty or Full Remove)
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    $qty_to_remove = isset($_GET['qty']) ? (int)$_GET['qty'] : 0;
    $status = "error";

    if (isset($_SESSION['cart'][$product_id])) {
        $current_qty = $_SESSION['cart'][$product_id];

        if ($qty_to_remove > 0 && $qty_to_remove < $current_qty) {
            $_SESSION['cart'][$product_id] -= $qty_to_remove;
            $status = "updated";
        } else {
            unset($_SESSION['cart'][$product_id]);
            $status = "removed";
        }
    }
    header("Location: cart.php?status=$status");
    exit;
}

include("inc/header.php");
$total = 0;
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-gold: #ffc107;
        --dark-bg: #050505;
        --card-bg: rgba(18, 18, 18, 0.7);
        --glass-border: rgba(255, 255, 255, 0.05);
        --neon-glow: 0 0 20px rgba(255, 193, 7, 0.15);
    }

    body {
        background-color: var(--dark-bg);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .cart-wrapper {
        padding: 80px 0;
    }

    .section-title {
        font-size: 2.8rem;
        font-weight: 900;
        background: linear-gradient(45deg, #fff, var(--primary-gold));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        padding-bottom: 25px;
        /* Space between text and underline */
        margin-bottom: 50px;
        display: inline-block;
        /* Center underline precisely */
        left: 50%;
        transform: translateX(-50%);
    }

    /* Professional underline design */
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        /* Line length */
        height: 4px;
        /* Line thickness */
        background: linear-gradient(90deg, transparent, var(--primary-gold), transparent);
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.6);
        /* Line glow */
    }

    /* Animation for the cart icon (Pulse) */
    @keyframes cart-pulse {
        0% {
            transform: scale(1);
            opacity: 0.3;
        }

        50% {
            transform: scale(1.15);
            opacity: 0.6;
        }

        100% {
            transform: scale(1);
            opacity: 0.3;
        }
    }

    .animated-cart {
        display: inline-block;
        animation: cart-pulse 2s infinite ease-in-out;
    }

    .cart-item-card {
        background: var(--card-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cart-item-card:hover {
        transform: scale(1.01);
        border-color: rgba(255, 193, 7, 0.4);
        box-shadow: var(--neon-glow);
        background: rgba(25, 25, 25, 0.8);
    }

    .item-info {
        display: flex;
        align-items: center;
        gap: 25px;
        flex: 2;
    }

    .item-img-box {
        width: 100px;
        height: 100px;
        background: #000;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border: 1px solid var(--glass-border);
    }

    .item-img-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .btn-delete {
        width: 45px;
        height: 45px;
        background: rgba(255, 77, 77, 0.1);
        color: #ff4d4d;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .btn-delete:hover {
        background: #ff4d4d;
        color: #fff;
        transform: rotate(15deg);
    }

    .order-summary {
        background: linear-gradient(145deg, #111, #080808);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        padding: 40px;
        position: sticky;
        top: 40px;
    }

    .btn-checkout {
        background: var(--primary-gold);
        color: #000;
        font-weight: 800;
        border-radius: 20px;
        padding: 20px;
        width: 100%;
        display: block;
        text-align: center;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.4s;
        margin-top: 30px;
    }

    .empty-cart-wrapper {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 40px;
        padding: 80px 40px;
        border: 1px solid var(--glass-border);
    }

    .cart-item-card {
        background: rgba(20, 20, 20, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 215, 0, 0.15);
        border-radius: 24px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        transition: all 0.4s ease;
    }

    .cart-item-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 215, 0, 0.4);
        box-shadow: 0 0 25px rgba(255, 215, 0, 0.15);
    }

    .item-info {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 2;
        /* Takes larger space */
    }

    .item-img-box {
        width: 90px;
        height: 90px;
        background: #000;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .item-img-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .item-details h4 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: #fff;
    }

    .item-details p {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.5);
        margin: 0;
    }

    .qty-box {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 215, 0, 0.1);
        padding: 10px 20px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 100px;
        justify-content: center;
    }

    .qty-box small {
        color: rgba(255, 215, 0, 0.7);
        font-weight: 600;
        font-size: 0.75rem;
    }

    .item-subtotal .price-tag {
        font-size: 1.3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #FFD700 0%, #B8860B 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .btn-delete {
        width: 45px;
        height: 45px;
        background: rgba(255, 76, 76, 0.1);
        color: #ff4c4c;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-delete:hover {
        background: #ff4c4c;
        color: #fff;
        transform: rotate(10deg);
    }

    @media (max-width: 768px) {
        .cart-item-card {
            flex-direction: column;
            text-align: center;
            padding: 25px;
        }

        .item-info {
            flex-direction: column;
            width: 100%;
        }

        .item-qty,
        .item-subtotal,
        .item-actions {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .qty-box {
            width: 100%;
            max-width: 150px;
        }
    }.btn-cart {
        background: linear-gradient(90deg, #b47716, #91521a);
        color: #fff;
        border: none;
        font-weight: 600;
        transition: 0.3s;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(243, 156, 18, 0.6);
        padding: 10px 20px; /* مساحة افتراضية */
    }

    .btn-cart:hover {
        background: linear-gradient(90deg, #e67e22, #f1c40f);
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(241, 196, 15, 1);
        color: black;
    }

    /* 📱 التعديلات الخاصة بالموبايل (أقل من 768px) */
    @media (max-width: 767px) {
        .btn-cart {
            width: 100%; /* الزرار ياخد العرض كامل عشان الضغط يكون أسهل */
            padding: 12px 10px; /* تكبير الارتفاع سنة عشان "بصمة الإصبع" */
            font-size: 0.9rem; /* تصغير الخط بسيط عشان يتناسب مع الكروت الصغيرة */
            letter-spacing: 0.5px;
        }

        /* بدل الـ hover بنستخدم الـ active عند اللمس في الموبايل */
        .btn-cart:active {
            transform: scale(0.95); /* تأثير "الضغط" للداخل بيدي إحساس حقيقي */
            background: linear-gradient(90deg, #e67e22, #f1c40f);
            box-shadow: 0 0 20px rgba(241, 196, 15, 0.8);
            transition: 0.1s; /* سرعة استجابة اللمس */
        }
        
        /* تعطيل الـ hover في الموبايل لتجنب تعليق اللون بعد اللمس */
        @media (hover: none) {
            .btn-cart:hover {
                transform: none;
                background: linear-gradient(90deg, #b47716, #91521a);
                color: #fff;
                box-shadow: 0 0 8px rgba(243, 156, 18, 0.6);
            }
        }
    }
</style>

<div class="cart-wrapper">
    <div class="container">
        <h2 class="section-title text-center">My Order</h2>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="empty-cart-wrapper text-center">
                <i class="fas fa-shopping-cart fa-5x mb-4 animated-cart" style="color: var(--primary-gold);"></i>
                <h3 class="fw-bold">Your cart is empty</h3>
                <p class="opacity-50">Looks like you haven't added anything to your collection yet.</p>
                <a href="products.php" class="btn fw-bold btn-cart mt-4 px-5 py-3" style="border-radius: 20px;">
                    <i class="fas fa-arrow-left me-2"></i> Explore Products
                </a>
            </div>
        <?php else: ?>
            <div class="row g-5">
                <div class="col-lg-8">
                    <?php
                    foreach ($_SESSION['cart'] as $id => $qty):
                        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                        $stmt->execute([$id]);
                        $p = $stmt->fetch();
                        if (!$p) continue;
                        $sub = $p['price'] * $qty;
                        $total += $sub;
                    ?>
                        <div class="cart-item-card">
                            <div class="item-info">
                                <div class="item-img-box">
                                    <img src="uploads/<?php echo $p['image']; ?>" alt="Product">
                                </div>
                                <div class="item-details">
                                    <h4><?php echo htmlspecialchars($p['name']); ?></h4>
                                    <p>$<?php echo number_format($p['price'], 2); ?> / unit</p>
                                </div>
                            </div>

                            <div class="item-qty">
                                <div class="qty-box">
                                    <small>QTY</small>
                                    <strong><?php echo $qty; ?></strong>
                                </div>
                            </div>

                            <div class="item-subtotal">
                                <span class="price-tag">$<?php echo number_format($sub, 2); ?></span>
                            </div>

                            <div class="item-actions">
                                <button onclick="confirmRemove(<?php echo $id; ?>, '<?php echo addslashes($p['name']); ?>', <?php echo $qty; ?>)" class="btn-delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-lg-4">
                    <div class="order-summary">
                        <h3 class="fw-bold mb-5 text-center" style="color: var(--primary-gold);">Summary</h3>
                        <div class="d-flex justify-content-between mb-3 opacity-70 mb-3 pb-3 border-bottom border-secondary">
                            <span>Subtotal</span>
                            <span>$<?php echo number_format($total, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 mb-3 pb-3 border-bottom border-secondary">
                            <span class="opacity-70">Shipping</span>
                            <span class="text-success small">Free Shipping</span>
                        </div>
                        <hr style="border-color: var(--glass-border)">
                        <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                            <span class="h4 fw-bold">Total</span>
                            <span class="h2 fw-bold" style="color: var(--primary-gold);">$<?php echo number_format($total, 2); ?></span>
                        </div>

                        <a href="checkout.php" class="btn fw-bold btn-cart d-flex align-items-center justify-content-center">
                            <span>Place Order</span>
                            <i class="fas fa-arrow-right ms-3"></i>
                        </a>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmRemove(productId, productName, currentQty) {
        if (currentQty <= 1) {
            Swal.fire({
                title: 'Remove Item?',
                text: `Are you sure you want to remove "${productName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff4d4d',
                confirmButtonText: 'Yes, delete it!',
                background: '#111',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = `cart.php?remove=${productId}`;
            });
            return;
        }

        Swal.fire({
            title: 'Remove Options',
            text: `You have ${currentQty} units of "${productName}".`,
            icon: 'question',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Remove All',
            denyButtonText: 'Remove Specific Qty',
            confirmButtonColor: '#ff4d4d',
            denyButtonColor: '#ffc107',
            background: '#111',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `cart.php?remove=${productId}`;
            } else if (result.isDenied) {
                Swal.fire({
                    title: 'Enter Quantity',
                    text: `How many units to remove? (Max: ${currentQty})`,
                    input: 'number',
                    inputAttributes: {
                        min: 1,
                        max: currentQty,
                        step: 1
                    },
                    inputValue: 1,
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    confirmButtonColor: '#ffc107',
                    background: '#111',
                    color: '#fff',
                    inputValidator: (value) => {
                        if (!value || value <= 0) return 'Please enter a valid number!';
                        if (value > currentQty) return `Only ${currentQty} units available!`;
                    }
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.location.href = `cart.php?remove=${productId}&qty=${res.value}`;
                    }
                });
            }
        });
    }

    // Status Alerts
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status && status !== 'error') {
        let titleText = status === 'removed' ? 'Deleted!' : 'Updated!';
        let messageText = status === 'removed' ? 'Product removed from cart successfully.' : 'Cart quantity has been updated.';

        Swal.fire({
            title: titleText,
            text: messageText,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            background: '#111',
            color: '#fff',
            iconColor: status === 'removed' ? '#ff4d4d' : '#ffc107',
            backdrop: `rgba(0,0,0,0.4)`
        });
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>

<?php include("./inc/footer.php"); ?>