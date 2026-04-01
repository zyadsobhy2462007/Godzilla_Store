<?php
include("../inc/config.php");
include("../inc/admin_header.php");


$update_success = false;
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
    if ($stmt->execute([$status, $order_id])) {
        $update_success = true;
    }
}

$order_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div style='color:white; background:#ff3e3e; padding:40px; text-align:center; font-family:Montserrat;'>
            <i class='fas fa-exclamation-triangle fa-3x mb-3'></i><br>CRITICAL ERROR: Order Not Found!
          </div>";
    exit;
}

$stmt_items = $pdo->prepare("SELECT oi.*, p.name as p_name, p.image, p.price as current_product_price FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll();

function getProductImage($image)
{
    if (empty($image)) return '../img/icon.jpg';
    if (file_exists($image)) return $image;
    if (file_exists('../uploads/' . $image)) return '../uploads/' . $image;
    return '../img/icon.jpg';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPECT ORDER #<?php echo $order_id; ?> | GODZILLA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Nosifer&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --neon-orange: #f39c12;
            --neon-green: #00ff88;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --deep-dark: #050505;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--deep-dark);
            color: #fff;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.95) 100%);
            z-index: -1;
        }


        .content {
            padding: 120px 15px 60px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 30px;
            padding: 40px;
            margin-bottom: 40px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        h1,
        h2 {
            font-family: 'Nosifer', sans-serif;
            text-transform: uppercase;
            color: var(--neon-orange);
            margin-bottom: 35px;
            font-size: 1.8rem;
            text-shadow: 0 0 15px rgba(243, 156, 18, 0.3);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.03);
            padding: 20px;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            transition: 0.3s;
        }

        .info-item:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--neon-orange);
        }

        .info-item label {
            color: var(--neon-orange);
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 8px;
        }

        .info-item .value {
            font-weight: 600;
            font-size: 1rem;
            color: #eee;
        }

        .status-box {
            background: rgba(243, 156, 18, 0.05);
            padding: 25px;
            border-radius: 20px;
            display: flex;
            gap: 20px;
            align-items: center;
            border: 1px solid rgba(243, 156, 18, 0.2);
        }

        .status-box select {
            background: #111;
            color: #fff;
            border: 1px solid var(--glass-border);
            padding: 12px 20px;
            border-radius: 12px;
            flex: 1;
            outline: none;
        }

        .btn-update {
            background: var(--neon-orange);
            color: #000;
            font-weight: 900;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            transition: 0.3s;
            letter-spacing: 1px;
        }

        .btn-update:hover {
            box-shadow: 0 0 25px var(--neon-orange);
            transform: translateY(-2px);
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .items-table thead th {
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 10px 20px;
            letter-spacing: 2px;
            border: none;
        }

        .items-table tbody tr {
            background: rgba(255, 255, 255, 0.03);
            transition: 0.3s ease;
        }



        .items-table td {
            padding: 20px;
            vertical-align: middle;
            border: none;
        }

        .items-table td:first-child {
            border-radius: 18px 0 0 18px;
            border-left: 1px solid var(--glass-border);
        }

        .items-table td:last-child {
            border-radius: 0 18px 18px 0;
            border-right: 1px solid var(--glass-border);
        }

        .product-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 15px;
            border: 1px solid var(--glass-border);
            margin-right: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .price-text {
            color: var(--neon-green);
            font-weight: 700;
        }

        .qty-badge {
            background: rgba(243, 156, 18, 0.15);
            color: var(--neon-orange);
            padding: 5px 15px;
            border-radius: 10px;
            font-weight: 800;
            border: 1px solid rgba(243, 156, 18, 0.3);
        }

        .subtotal-text {
            color: var(--neon-green);
            font-weight: 900;
            font-size: 1.1rem;
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
        }

        .total-section {
            text-align: center;
            margin-top: 40px;
            padding-top: 40px;
            border-top: 1px solid var(--glass-border);
        }

        .total-label {
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 5px;
            font-size: 0.8rem;
            margin-bottom: 15px;
        }

        .total-value {
            font-size: 4.5rem;
            font-weight: 900;
            color: var(--neon-green);
            text-shadow: 0 0 40px rgba(0, 255, 136, 0.3);
            line-height: 1;
        }

        .swal2-popup {
            background: #0a0a0a !important;
            border: 1px solid var(--neon-orange) !important;
            border-radius: 25px !important;
            backdrop-filter: blur(10px);
        }

        .btn-go-products {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            font-weight: 900;
            border-radius: 15px;
            text-decoration: none;
            letter-spacing: 1px;
            transition: 0.3s;
            box-shadow: 0 0 20px rgba(243, 156, 18, 0.5);
        }

        .btn-go-products:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 35px rgba(243, 156, 18, 0.9);
            color: black;
        }

        /* --- RESPONSIVE DOSSIER STRATEGY --- */

        @media (max-width: 991px) {
            .content {
                padding-top: 100px;
            }

            .total-value {
                font-size: 3.5rem;
            }
        }

        @media (max-width: 768px) {
            .glass-card {
                padding: 25px 15px;
                border-radius: 20px;
            }

            h1,
            h2 {
                font-size: 1.4rem;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .status-box {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .status-box select,
            .btn-update {
                width: 100%;
            }

            .items-table thead {
                display: none;
            }

            .items-table tbody tr {
                display: block;
                margin-bottom: 20px;
                border-radius: 15px !important;
                border: 1px solid var(--glass-border) !important;
                padding: 10px;
            }

            .items-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right;
                padding: 12px 10px;
                border: none !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            }

            .items-table td:last-child {
                border-bottom: none !important;
            }

            .items-table td:nth-child(2)::before {
                content: "Unit Price:";
                color: var(--neon-orange);
                font-weight: 700;
                font-size: 0.7rem;
            }

            .items-table td:nth-child(3)::before {
                content: "Quantity:";
                color: var(--neon-orange);
                font-weight: 700;
                font-size: 0.7rem;
            }

            .items-table td:nth-child(4)::before {
                content: "Subtotal:";
                color: var(--neon-orange);
                font-weight: 700;
                font-size: 0.7rem;
            }

            .product-thumb {
                width: 50px;
                height: 50px;
                margin-right: 10px;
            }

            .total-value {
                font-size: 2.5rem;
            }

            .total-label {
                letter-spacing: 2px;
            }
        }

        @media (max-width: 480px) {
            .total-value {
                font-size: 2rem;
            }

            .btn-go-products {
                width: 90%;
                padding: 12px;
            }
        }

        /* --- GODZILLA RESPONSIVE DOSSIER STRATEGY --- */

        @media (max-width: 991px) {
            .content {
                padding-top: 80px;
            }

            .total-value {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            .glass-card {
                padding: 25px 15px;
                border-radius: 20px;
            }

            h1,
            h2 {
                font-size: 1.3rem;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .status-box {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .status-box select,
            .btn-update {
                width: 100%;
                margin: 0;
            }

            .items-table thead {
                display: none;
            }

            .items-table tbody tr {
                display: block;
                margin-bottom: 20px;
                border-radius: 20px !important;
                border: 1px solid var(--glass-border) !important;
                padding: 10px;
            }

            .items-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right;
                padding: 12px 10px;
                border: none !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            }

            .items-table td:last-child {
                border-bottom: none !important;
            }

            .items-table td:first-child {
                flex-direction: column;
                gap: 10px;
            }

            .product-thumb {
                margin: 0 auto 10px;
                width: 80px;
                height: 80px;
            }

            .items-table td:nth-child(2)::before {
                content: "Unit Price:";
                color: var(--neon-orange);
                font-weight: 700;
                font-size: 0.75rem;
            }

            .items-table td:nth-child(3)::before {
                content: "Quantity:";
                color: var(--neon-orange);
                font-weight: 700;
                font-size: 0.75rem;
            }

            .items-table td:nth-child(4)::before {
                content: "Subtotal:";
                color: var(--neon-orange);
                font-weight: 700;
                font-size: 0.75rem;
            }

            .total-value {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 480px) {
            .total-value {
                font-size: 1.8rem;
            }

            .btn-go-products {
                width: 100%;
                text-align: center;
            }
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .status-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(243, 156, 18, 0.05);
            padding: 20px;
            border-radius: 20px;
            border: 1px solid rgba(243, 156, 18, 0.2);
            gap: 20px;
        }

        .status-controls {
            display: flex;
            flex: 1;
            gap: 15px;
        }

        /* --- Responsive Media Query (Mobile & Tablets) --- */
        @media (max-width: 768px) {
            .glass-card {
                padding: 25px 15px !important;
            }

            h1 {
                font-size: 1.4rem !important;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .info-item {
                padding: 15px;
                text-align: center;
            }

            .status-box {
                flex-direction: column;
                padding: 20px 15px;
            }

            .status-label {
                font-size: 0.85rem;
                margin-bottom: 5px;
                text-align: center;
                width: 100%;
            }

            .status-controls {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }

            .status-box select,
            .btn-update {
                width: 100% !important;
                margin: 0;
                height: 50px;
            }

            .info-item .value {
                font-size: 0.95rem;
                word-break: break-all;
            }
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="glass-card shadow-lg">
            <h1><i class="fas fa-id-card-alt me-3"></i>Order Dossier</h1>

            <div class="info-grid">
                <div class="info-item">
                    <label><i class="fas fa-user-secret me-2"></i>Target Name</label>
                    <div class="value"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-phone-alt me-2"></i>Contact Line</label>
                    <div class="value"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-map-marker-alt me-2"></i>Drop-off Point</label>
                    <div class="value"><?php echo htmlspecialchars($order['customer_address']); ?></div>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-clock me-2"></i>Timestamp</label>
                    <div class="value"><?php echo date('d M Y | h:i A', strtotime($order['created_at'])); ?></div>
                </div>
            </div>

            <form method="POST" class="status-box" id="statusForm">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <div class="status-label">
                    <i class="fas fa-exclamation-circle me-2"></i> MISSION STATUS:
                </div>
                <div class="status-controls">
                    <select name="status" id="orderStatus">
                        <?php
                        $opts = ['Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled'];
                        foreach ($opts as $opt) {
                            $sel = ($order['status'] == $opt) ? 'selected' : '';
                            echo "<option value='$opt' $sel>$opt</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="update_status" class="btn-update">
                        <i class="fas fa-sync-alt me-2"></i> EXECUTE
                    </button>
                </div>
            </form>
        </div>

        <div class="glass-card">
            <h2><i class="fas fa-box-open me-3"></i>Manifested Items</h2>
            <div class="table-responsive">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Resource</th>
                            <th class="text-center">Unit Price</th>
                            <th class="text-center">Units</th>
                            <th class="text-end">Credit Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item):
                            $img = getProductImage($item['image']);
                            $price = ($item['price'] > 0) ? $item['price'] : ($item['current_product_price'] ?? 0);
                            $sub = $price * $item['quantity'];
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo $img; ?>" class="product-thumb">
                                        <div>
                                            <div class="fw-bold text-white"><?php echo htmlspecialchars($item['product_name'] ?? $item['p_name']); ?></div>
                                            <small class="text-white-50">SKU-<?php echo str_pad($item['product_id'], 5, '0', STR_PAD_LEFT); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center price-text">$<?php echo number_format($price, 2); ?></td>
                                <td class="text-center">
                                    <span class="qty-badge"><?php echo $item['quantity']; ?></span>
                                </td>
                                <td class="text-end subtotal-text">$<?php echo number_format($sub, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total-section">
                <div class="total-label">Grand Total Revenue</div>
                <div class="total-value">$<?php echo number_format($order['total_price'], 2); ?></div>
            </div>
        </div>
    </div>
    <div style="text-align:center; margin-bottom: 60px;">
        <a href="orders.php" class="btn-go-products">
            <i class="fas fa-receipt me-2"></i> Back To Orders
        </a>
    </div>

    <?php include("../inc/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        <?php if ($update_success): ?>
            Swal.fire({
                title: 'STATUS UPDATED',
                text: 'Order <?php echo $order_id; ?> is now marked as <?php echo $_POST['status']; ?>',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timerProgressBar: true,
                background: '#0a0a0a',
                color: '#fff',
                iconColor: '#00ff88'
            });
        <?php endif; ?>
    </script>