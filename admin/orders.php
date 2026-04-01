<?php
include("../inc/config.php");
include("../inc/admin_header.php");

$current_page = "orders";
$page_title = "Manage Orders";

try {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title><?php echo $page_title; ?> | Godzilla Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #080808;
            --neon-orange: #f39c12;
            --neon-green: #2ecc71;
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: -1;
        }

        .admin-content {
            padding: 80px 20px;
        }

        .admin-header h1 {
            font-family: 'Nosifer', sans-serif;
            font-size: 2.2rem;
            background: linear-gradient(to right, var(--neon-orange), #ffcc00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 15px rgba(243, 156, 18, 0.4);
            margin-bottom: 50px;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 15px;
            color: #fff;
            background: transparent;
        }

        .table thead th {
            border: none;
            color: var(--neon-orange);
            text-transform: uppercase;
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 2px;
            padding: 20px 25px;
            background: rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            text-shadow: 0 0 10px rgba(243, 156, 18, 0.5);
            border-bottom: 2px solid rgba(243, 156, 18, 0.2);
        }

        .table thead th:first-child {
            border-radius: 15px 0 0 0;
        }

        .table thead th:last-child {
            border-radius: 0 15px 0 0;
        }

        .table tbody tr {
            background: rgba(255, 255, 255, 0.03) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: all 0.4s ease;
        }



        .table td {
            padding: 20px 25px;
            border: none;
            vertical-align: middle;
            background: transparent !important;
            color: rgba(255, 255, 255, 0.9);
        }

        .table tbody tr td:first-child {
            border-left: 1px solid var(--glass-border);
            border-radius: 15px 0 0 15px;
        }

        .table tbody tr td:last-child {
            border-right: 1px solid var(--glass-border);
            border-radius: 0 15px 15px 0;
        }

        .order-id-chip {
            color: var(--neon-orange);
            font-weight: 700;
            font-family: 'Nosifer', sans-serif;
            font-size: 0.7rem;
        }

        .price-tag {
            color: var(--neon-green);
            font-weight: 800;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid transparent;
        }

        .status-pending {
            background: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
            border-color: rgba(241, 196, 15, 0.3);
        }

        .status-processing {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
            border-color: rgba(52, 152, 219, 0.3);
        }

        .status-shipped {
            background: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
            border-color: rgba(155, 89, 182, 0.3);
        }

        .status-completed {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
            border-color: rgba(46, 204, 113, 0.3);
        }

        .status-cancelled {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            border-color: rgba(231, 76, 60, 0.3);
        }

        .btn-view {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            padding: 8px 18px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .btn-view:hover {
            background: var(--neon-orange);
            color: #000;
            box-shadow: 0 0 15px var(--neon-orange);
        }

        @media (max-width: 991px) {
            .admin-header h1 {
                font-size: 1.8rem;
            }

            .admin-content {
                padding: 40px 10px;
            }
        }

        @media (max-width: 768px) {

            .table thead {
                display: none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100%;
            }

            .table tbody tr {
                margin-bottom: 25px;
                border: 1px solid var(--glass-border) !important;
                border-radius: 20px !important;
                padding: 15px;
                position: relative;
                overflow: hidden;
                background: rgba(255, 255, 255, 0.05) !important;
            }

            .table td {
                text-align: center;
                padding: 12px 15px;
                display: flex;
                justify-content: center;
                align-items: center;
                border: none !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            }

            .table td:last-child {
                border-bottom: none !important;
                margin-top: 10px;
                justify-content: center;
            }

            .table td::before {
                content: attr(data-label);
                float: left;
                font-weight: 800;
                text-transform: uppercase;
                font-size: 0.7rem;
                color: var(--neon-orange);
                letter-spacing: 1px;
            }

            .order-id-chip {
                font-size: 0.8rem;
            }

            .btn-view {
                width: 100%;
                text-align: center;
                padding: 12px;
            }
        }

        .admin-content {
            padding: 80px 20px;
        }

        .admin-header h1 {
            font-family: 'Nosifer', sans-serif;
            font-size: 2.2rem;
            background: linear-gradient(to right, var(--neon-orange), #ffcc00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 15px rgba(243, 156, 18, 0.4);
            margin-bottom: 50px;
        }

        /* Desktop Table Styling */
        .table {
            border-collapse: separate;
            border-spacing: 0 15px;
            color: #fff;
            background: transparent;
        }

        .table thead th {
            border: none;
            color: var(--neon-orange);
            text-transform: uppercase;
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 2px;
            padding: 20px 25px;
            background: rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(10px);
            border-bottom: 2px solid rgba(243, 156, 18, 0.2);
        }

        .table tbody tr {
            background: rgba(255, 255, 255, 0.03) !important;
            backdrop-filter: blur(8px);
            transition: all 0.4s ease;
        }

        .table td {
            padding: 20px 25px;
            border: none;
            vertical-align: middle;
            background: transparent !important;
        }

        .table tbody tr td:first-child {
            border-radius: 15px 0 0 15px;
            border-left: 1px solid var(--glass-border);
        }

        .table tbody tr td:last-child {
            border-radius: 0 15px 15px 0;
            border-right: 1px solid var(--glass-border);
        }

        /* Chips & Tags */
        .order-id-chip {
            color: var(--neon-orange);
            font-weight: 700;
            font-family: 'Nosifer', sans-serif;
            font-size: 0.7rem;
        }

        .price-tag {
            color: var(--neon-green);
            font-weight: 800;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid transparent;
        }

        .status-pending {
            background: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
            border-color: rgba(241, 196, 15, 0.3);
        }

        .status-completed {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
            border-color: rgba(46, 204, 113, 0.3);
        }

        /* --- Updated Action Button --- */
        .btn-view {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 10px 22px;
            background: rgba(243, 156, 18, 0.05);
            color: var(--neon-orange);
            border: 1px solid rgba(243, 156, 18, 0.3);
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .btn-view:hover {
            background: var(--neon-orange);
            color: #000;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 20px rgba(243, 156, 18, 0.5);
        }

        /* --- FULL RESPONSIVE CODE --- */
        @media (max-width: 991px) {
            .admin-header h1 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100%;
            }

            .table tbody tr {
                margin-bottom: 30px;
                background: rgba(255, 255, 255, 0.05) !important;
                border: 1px solid var(--glass-border) !important;
                border-radius: 20px !important;
                padding: 15px;
            }

            .table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right;
                padding: 12px 10px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            }

            .table td[data-label="Actions"] {
                flex-direction: column;
                justify-content: center;
                gap: 15px;
                padding: 25px 10px !important;
                border-bottom: none !important;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: 800;
                text-transform: uppercase;
                font-size: 0.75rem;
                color: var(--neon-orange);
            }

            .table td[data-label="Actions"]::before {
                margin-bottom: 5px;
                text-align: center;
                width: 100%;
            }

            .btn-view {
                width: 100%;
                max-width: 280px;
                padding: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="admin-content">
        <div class="container">
            <div class="admin-header text-center">
                <h1><i class="fas fa-vault me-3"></i>Orders Vault</h1>
            </div>

            <div class="orders-table-wrapper">
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Mission Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td data-label="Ref"><span class="order-id-chip">#<?php echo $order['id']; ?></span></td>
                                        <td data-label="Customer">
                                            <div class="fw-bold text-white"><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?></div>
                                        </td>
                                        <td data-label="Amount"><span class="price-tag">$<?php echo number_format($order['total_price'], 2); ?></span></td>
                                        <td data-label="Date"><span class="text-white-50 small"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span></td>
                                        <td data-label="Mission Status">
                                            <?php
                                            $status = strtolower($order['status'] ?? 'pending');
                                            $config = [
                                                'pending'    => ['class' => 'status-pending', 'icon' => 'fa-clock'],
                                                'processing' => ['class' => 'status-processing', 'icon' => 'fa-spinner fa-spin'],
                                                'shipped'    => ['class' => 'status-shipped', 'icon' => 'fa-truck'],
                                                'completed'  => ['class' => 'status-completed', 'icon' => 'fa-check-circle'],
                                                'cancelled'  => ['class' => 'status-cancelled', 'icon' => 'fa-times-circle'],
                                            ];
                                            $current = $config[$status] ?? $config['pending'];
                                            ?>
                                            <span class="status-badge <?php echo $current['class']; ?>">
                                                <i class="fas <?php echo $current['icon']; ?>"></i>
                                                <?php echo $status; ?>
                                            </span>
                                        </td>
                                        <td data-label="Actions">
                                            <a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn-view">
                                                <i class="fas fa-folder-open me-1"></i> Update & View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5" style="background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.1);">
                        <i class="fas fa-ghost fa-3x mb-3 text-white-50"></i>
                        <h3 class="text-white-50">Vault is Empty</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include("../inc/footer.php"); ?>

</body>

</html>