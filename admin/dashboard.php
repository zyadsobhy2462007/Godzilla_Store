<?php
include("../inc/config.php");
include("../inc/admin_header.php");
$page_title = "Admin Dashboard";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>
        <?php
        echo (isset($page_title) && !empty($page_title)) ? ucfirst($page_title) : "Admin Dashboard";
        ?>
        | Godzilla Supplement
    </title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0a0a0a;
            --secondary: #ff2a6d;
            /* Neon Pink */
            --tertiary: #05d9e8;
            /* Neon Blue */
            --accent: #f2f542;
            /* Neon Yellow */
            --dark-bg: #111111;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--primary);
            color: white;
            overflow-x: hidden;
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
            padding: 100px 20px 40px;
            min-height: calc(100vh - 80px);
        }

        .admin-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .admin-header h1 {
            font-family: 'Nosifer', sans-serif;
            font-size: 2.5rem;
            background: linear-gradient(50deg, #f39c12, #e67e22, #f1c40f);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .admin-header p {
            color: #aaa;
            font-size: 1.1rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        /* Neon Card Effect */
        .stat-card {
            position: relative;
            padding: 40px 20px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: transform 0.4s ease;
            border: none;
        }

        /* The Animated Border */
        .stat-card::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background-image: conic-gradient(transparent,
                    var(--card-color),
                    transparent 30%);
            animation: rotateNeon 4s linear infinite;
            z-index: -2;
        }

        /* Inner Mask */
        .stat-card::after {
            content: '';
            position: absolute;
            inset: 3px;
            background: var(--dark-bg);
            border-radius: 18px;
            z-index: -1;
        }

        @keyframes rotateNeon {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }



        /* Color Variations based on your Website Theme */
        .stat-card.products {
            --card-color: var(--tertiary);
            --card-shadow-rgb: 5, 217, 232;
        }

        .stat-card.orders {
            --card-color: var(--secondary);
            --card-shadow-rgb: 255, 42, 109;
        }

        .stat-card.revenue {
            --card-color: var(--accent);
            --card-shadow-rgb: 242, 245, 66;
        }

        .stat-card.users {
            --card-color: #9d50bb;
            --card-shadow-rgb: 157, 80, 187;
        }

        /* Content Styling */
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--card-color);
            text-shadow: 0 0 15px var(--card-color);
            z-index: 1;
        }

        .stat-card h5 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #aaa;
            margin-bottom: 10px;
            z-index: 1;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 20px;
            z-index: 1;
        }

        .stat-card .btn-custom {
            padding: 8px 20px;
            border-radius: 30px;
            border: 1px solid var(--card-color);
            color: #fff;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: 0.3s;
            z-index: 1;
        }

        .stat-card .btn-custom:hover {
            background: var(--card-color);
            color: #000;
            box-shadow: 0 0 15px var(--card-color);
        }

        @media (max-width: 768px) {
            .admin-header h1 {
                font-size: 2rem;
            }
        }

        .neon-border-popup {
            position: relative;
            background: #0a0a0a !important;
            border-radius: 25px !important;
            border: none !important;
            padding: 3px !important;
            overflow: hidden !important;
        }

        .neon-border-popup::before {
            content: '';
            position: absolute;
            width: 250%;
            height: 250%;
            background: conic-gradient(transparent,
                    #f39c12,
                    #e67e22,
                    #f1c40f,
                    transparent 40%);
            top: -75%;
            left: -75%;
            animation: rotateLED 4s linear infinite;
            z-index: 1;
        }

        .neon-border-popup::after {
            content: '';
            position: absolute;
            inset: 5px;
            background: #0a0a0a;
            border-radius: 20px;
            z-index: 2;
        }

        .neon-border-popup .swal2-icon,
        .neon-border-popup .swal2-title,
        .neon-border-popup .swal2-html-container,
        .neon-border-popup .swal2-actions {
            position: relative;
            z-index: 3;
        }

        @keyframes rotateLED {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .nosifer-title {
            font-family: 'Nosifer', sans-serif !important;
            color: #f39c12 !important;
            font-size: 1.4rem !important;
            text-shadow: 0 0 15px rgba(243, 156, 18, 0.5);
            margin-top: 25px !important;
        }

        .custom-swal-button {
            background: linear-gradient(135deg, #f39c12, #e67e22) !important;
            border: none !important;
            color: #000 !important;
            font-weight: 800 !important;
            letter-spacing: 1px !important;
            padding: 12px 35px !important;
            border-radius: 12px !important;
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4) !important;
            transition: 0.3s !important;
        }

        .custom-swal-button:hover {
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 8px 25px rgba(243, 156, 18, 0.6) !important;
        }

        .swal2-icon.swal2-success {
            border-color: #f39c12 !important;
        }

        .swal2-icon.swal2-success [class^=swal2-success-line] {
            background-color: #f39c12 !important;
        }
    </style>
</head>

<body>
    <div class="admin-content">
        <div class="container">
            <div class="admin-header">
                <h1><i class="fas fa-crown"></i> Admin Dashboard</h1>
                <p>Godzilla Supplement Control Center</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card products">
                    <div class="stat-icon"><i class="fas fa-box-open"></i></div>
                    <h5>Total Products</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
                    $result = $stmt->fetch();
                    echo "<h3>" . ($result['count'] ?? 0) . "</h3>";
                    ?>
                    <a href="products.php" class="btn-custom"><i class="fas fa-eye"></i> View All</a>
                </div>

                <div class="stat-card orders">
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                    <h5>Total Orders</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
                    $result = $stmt->fetch();
                    echo "<h3>" . ($result['count'] ?? 0) . "</h3>";
                    ?>
                    <a href="orders.php" class="btn-custom"><i class="fas fa-eye"></i> View All</a>
                </div>

                <div class="stat-card revenue">
                    <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                    <h5>Total Revenue</h5>
                    <?php
                    $stmt = $pdo->query("SELECT SUM(total_price) as total FROM orders");
                    $result = $stmt->fetch();
                    $total = $result['total'] ?? 0;
                    echo "<h3>$" . number_format($total, 0) . "</h3>";
                    ?>
                </div>

                <div class="stat-card users">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <h5>Active Users</h5>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
                        $result = $stmt->fetch();
                        echo "<h3>" . ($result['count'] ?? 0) . "</h3>";
                    } catch (Exception $e) {
                        echo "<h3>0</h3>";
                    }
                    ?>
                </div>
            </div>


        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: '🔱 SYSTEM ONLINE, COMMANDER!',
                html: `
                <div style="text-align: center; font-family: 'Montserrat', sans-serif;">
                    <p style="color: #f39c12; font-weight: bold; font-size: 1.1rem; margin-bottom: 10px;">
                        Welcome back to <span style="font-family: 'Nosifer';">GODZILLA</span> HQ.
                    </p>
                    <p style="color: #ccc; line-height: 1.6;">
                        Your digital empire is safe and ready for your next move.<br>
                        <span style="color: #fff; font-style: italic;">"Great power comes with great management."</span>
                    </p>
                    <div style="margin-top: 15px; border-top: 1px dashed rgba(243, 156, 18, 0.3); padding-top: 10px;">
                        <small style="color: #94a3b8;">Status: <span style="color: #2ecc71;">● All Systems Operational</span></small>
                    </div>
                </div>
            `,
                icon: 'success',
                background: '#0a0a0a',
                color: '#fff',
                confirmButtonText: '<i class="fas fa-shield-alt"></i> ENTER DASHBOARD',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                customClass: {
                    popup: 'neon-border-popup',
                    title: 'nosifer-title',
                    confirmButton: 'custom-swal-button'
                }
            });
        });
    </script>


    <?php include("../inc/footer.php"); ?>
</body>


</html>