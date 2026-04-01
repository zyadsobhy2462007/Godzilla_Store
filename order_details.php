<?php
session_start();
$page_title = "Order Details";
include("./inc/config.php");
include("./inc/header.php");

if (!isset($_GET['id'])) {
    echo "<div class='container mt-5 text-center'><h3 style='color:#fff;'>Order not found</h3></div>";
    include("inc/footer.php");
    exit;
}

$order_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id=?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='container mt-5 text-center'><h3 style='color:#fff;'>Order not found</h3></div>";
    include("inc/footer.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT order_items.*, products.name, products.price, products.image 
    FROM order_items 
    JOIN products ON products.id = order_items.product_id
    WHERE order_items.order_id=?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

$status = $order['status'] ?? 'Pending';
$statusMap = [
    "Pending" => ["color" => "#d4af37", "bg" => "rgba(212, 175, 55, 0.1)", "icon" => "fa-clock"],
    "Completed" => ["color" => "#10b981", "bg" => "rgba(16, 185, 129, 0.1)", "icon" => "fa-check-circle"],
    "Cancelled" => ["color" => "#ef4444", "bg" => "rgba(239, 68, 68, 0.1)", "icon" => "fa-times-circle"]
];
$statusColor = $statusMap[$status]['color'] ?? "#94a3b8";
$statusBg = $statusMap[$status]['bg'] ?? "rgba(255,255,255,0.05)";
$statusIcon = $statusMap[$status]['icon'] ?? "fa-info-circle";
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-gold: #d4af37;
        --dark-bg: #0b0e14;
        --card-bg: #151921;
        --text-white: #ffffff;
        --border-glass: rgba(255, 255, 255, 0.1);
    }

    body {
        background-color: var(--dark-bg);
        color: var(--text-white);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .order-container {
        padding: 60px 0;
    }

    .order-card {
        background: var(--card-bg);
        border: 1px solid var(--border-glass);
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .status-badge {
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .info-section {
        border-bottom: 1px solid var(--border-glass);
        padding-bottom: 30px;
        margin-bottom: 30px;
    }

    .section-label {
        color: var(--primary-gold);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 20px;
        display: block;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .info-item label {
        display: block;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.8rem;
        margin-bottom: 5px;
    }

    .info-item p {
        color: var(--text-white);
        font-weight: 600;
        margin: 0;
    }

    /* جدول المنتجات المطور */
    .product-list {
        margin-top: 30px;
    }

    .product-row {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .product-name {
        flex: 2;
        font-weight: 600;
    }

    .product-meta {
        flex: 1;
        text-align: center;
        color: var(--text-white);
    }

    .product-total {
        flex: 1;
        text-align: right;
        font-weight: 700;
        color: var(--primary-gold);
    }

    .summary-box {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 20px;
        padding: 25px;
        margin-top: 30px;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        color: var(--text-white);
    }

    .total-line {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--primary-gold);
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--primary-gold);
    }

    .btn-store {
        background: transparent;
        border: 1px solid var(--primary-gold);
        color: var(--primary-gold);
        padding: 15px;
        border-radius: 15px;
        width: 100%;
        text-decoration: none;
        display: block;
        text-align: center;
        font-weight: 700;
        transition: 0.3s;
        margin-top: 30px;
    }

    .btn-store:hover {
        background: var(--primary-gold);
        color: #000;
    }
</style>

<div class="order-container">
    <div class="container d-flex justify-content-center">
        <div class="order-card" style="width: 100%; max-width: 800px;">

            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 style="color:#fff; margin:0; font-weight:800;">Order #<?php echo $order_id; ?></h2>
                    <p style="color:rgba(255,255,255,0.5); margin:0;">Thank you for your purchase</p>
                </div>
                <div class="status-badge" style="color: <?php echo $statusColor; ?>; background: <?php echo $statusBg; ?>; border: 1px solid <?php echo $statusColor; ?>33;">
                    <i class="fas <?php echo $statusIcon; ?>"></i> <?php echo $status; ?>
                </div>
            </div>

            <div class="info-section">
                <span class="section-label">Shipping Details</span>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Customer Name</label>
                        <p><?php echo htmlspecialchars($order['customer_name']); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Phone Number</label>
                        <p><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Payment Method</label>
                        <p><?php echo strtoupper(htmlspecialchars($order['payment_method'] ?? 'N/A')); ?></p>
                    </div>
                </div>
                <div class="info-item mt-3">
                    <label>Delivery Address</label>
                    <p><?php echo htmlspecialchars($order['customer_address']); ?></p>
                </div>
            </div>

            <div class="product-list">
                <span class="section-label">Order Items</span>
                <?php foreach ($items as $item): ?>
                    <div class="product-row">
                        <div class="product-name">
                            <span style="color:#fff;"><?php echo htmlspecialchars($item['name']); ?></span>
                            <div style="font-size: 0.8rem; color: rgba(255,255,255,0.4);">Unit Price: $<?php echo number_format($item['price'], 2); ?></div>
                        </div>
                        <div class="product-meta">
                            <span style="background: rgba(255,255,255,0.05); padding: 4px 12px; border-radius: 8px;">Qty: <?php echo $item['quantity']; ?></span>
                        </div>
                        <div class="product-total">
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-box">
                <?php if (!empty($order['coupon_code'])): ?>
                    <div class="summary-line" style="color: #10b981;">
                        <span>Discount (<?php echo htmlspecialchars($order['coupon_code']); ?>)</span>
                        <span>-$<?php echo number_format($order['discount'], 2); ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary-line total-line">
                    <span>Grand Total</span>
                    <span>$<?php echo number_format($order['total_price'], 2); ?></span>
                </div>
            </div>

            <a href="index.php" class="btn-store">
                <i class="fas fa-arrow-left me-2"></i> Return to Home
            </a>

        </div>
    </div>
</div>

<?php include("./inc/footer.php"); ?>