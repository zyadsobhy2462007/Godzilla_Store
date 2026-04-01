<?php
// Start session if not active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure cart exists in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate cart item count including multiples
$cart_count = 0;
foreach ($_SESSION['cart'] as $product_id => $qty) {
    // Each item counts
}

// Set base path for assets (used in footer.php)
$base_path = '../';

// Set default page title
$page_title = isset($page_title) ? $page_title : 'Home';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../img/icon title.jpeg">
    <link rel="stylesheet" href="../css/custom.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0a0a0a;
            --white: white;
            --secondary: #ff2a6d;
            --tertiary: #05d9e8;
            --accent: #f2f542;
            --accentely: #e67e22;
            --purple: #a259ff;
            --blue: #2c81ff;
            --glow: 0 0 20px rgba(5, 217, 232, 0.7);
            --glow-secondary: 0 0 30px rgba(255, 42, 109, 0.7);
            --admin-bg: #0f1113;
            --card-bg: #1a1d21;
            --accent-color: #f39c12;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.05);
        }


        /* Main Background - Applied to whole page */
        body {
            font-family: 'Montserrat', sans-serif;
            /*background: url("../img/header.png") center center/cover no-repeat fixed;*/
            background-color: black;
            color: white;
            overflow-x: hidden;
            cursor: auto;
            scroll-behavior: smooth;
            min-height: 100vh;
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

        .floating-dots {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            min-height: 100vh;
            pointer-events: none;
            z-index: 1;
        }


        .dot {
            position: absolute;
            width: 4px;
            height: 4px;
            background-color: #e67e22;
            border-radius: 50%;
            opacity: 0.6;
            animation: floatDot 5s linear infinite;
            box-shadow: 0 0 8px #e67e22, 0 0 15px #e67e22;
        }

        @keyframes floatDot {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0.4;
            }

            50% {
                transform: translateY(-30px) translateX(10px);
                opacity: 0.8;
            }

            100% {
                transform: translateY(-60px) translateX(-10px);
                opacity: 0.4;
            }
        }

        /* Loading screen */
        #loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 1s ease-out, visibility 1s ease-out;
        }

        .loader-container {
            text-align: center;
        }

        .loader {
            width: 120px;
            height: 120px;
            position: relative;
            display: inline-block;
        }

        .loader::before,
        .loader::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 5px solid transparent;
            border-top-color: var(--accent);
            animation: spin 2s linear infinite;
        }

        .loader::after {
            border-top-color: var(--accentely);
            animation: spin 2s linear infinite reverse;
        }

        .loader-text {
            color: rgba(255, 255, 255, 0.7);
            margin-top: 2rem;
            font-size: 1.2rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loaded #loading {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .godzilla {
            width: 80px;
            margin-left: 100px;
            background: transparent;
        }

        #nv {
            margin-right: 30px;
            color: white;
        }

        #nv:hover {
            border-bottom: 2px solid #e4b95b;
            color: #e4b95b;
            transition: 0.5s;
        }

        .godzilla {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #f39c12;
        }

        .btn-cart {
            background: linear-gradient(90deg, #b47716, #91521a);
            color: #fff;
            border: none;
            font-weight: 600;
            transition: 0.3s;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(243, 156, 18, 0.6);
        }

        .btn-cart:hover {
            background: linear-gradient(90deg, #e67e22, #f1c40f);
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(241, 196, 15, 1);
            color: black;
        }

        /* Header Hero Section */
        .header-hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .header-hero * {
            z-index: 10;
        }

        #navbar img {
            width: 80px;
            margin-left: 100px;
        }


        .header-hero .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            text-align: center;

        }

        .zigzag-text {
            font-size: 3rem;
            font-weight: bold;
            text-align: center;
            margin-top: 2rem;
        }

        .zigzag-text span {
            display: inline-block;
            animation: bounce 1.5s ease-in-out infinite;
        }

        .zigzag-text span:nth-child(1) {
            animation-delay: 0s;
        }

        .zigzag-text span:nth-child(2) {
            animation-delay: 0.1s;
        }

        .zigzag-text span:nth-child(3) {
            animation-delay: 0.2s;
        }

        .zigzag-text span:nth-child(4) {
            animation-delay: 0.3s;
        }

        .zigzag-text span:nth-child(5) {
            animation-delay: 0.4s;
        }

        .zigzag-text span:nth-child(6) {
            animation-delay: 0.5s;
        }

        .zigzag-text span:nth-child(7) {
            animation-delay: 0.6s;
        }

        .zigzag-text span:nth-child(8) {
            animation-delay: 0.7s;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .zigzag-text span {
            display: inline-block;
            font-family: "Nosifer", sans-serif;
            font-weight: 400;
            color: #f39c12;
            letter-spacing: 3px;
            animation: bounce 1.5s ease-in-out infinite;
        }

        .header-hero .content h1 {
            font-size: 40px;
            color: #fff;
        }

        .header-hero .content p {
            color: #fff;
            margin: 20px 0 40px;
        }

        /* Utility Classes */
        .btn {
            display: inline-block;
            padding: 15px 30px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
        }

        .btn-primary {
            border: 1px solid #fff;
        }

        .primary-text {
            color: #e4b95b;
        }



        /* ====== ULTRA PRO NAVBAR ====== */
        .admin-navbar {
            background: rgba(0, 0, 0, 0.50) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(243, 156, 18, 0.2);
            padding: 12px 0;
            transition: all 0.4s ease;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        }

        .admin-navbar::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #f39c12, transparent);
            animation: glowLine 3s infinite linear;
        }

        @keyframes glowLine {
            0% {
                opacity: 0.2;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.2;
            }
        }

        /* ===== Logo ===== */
        .admin-navbar .navbar-brand {
            font-family: 'Nosifer', sans-serif;
            font-size: 1.3rem;
            color: #f39c12 !important;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-navbar .navbar-brand img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #f39c12;
            box-shadow: 0 0 15px rgba(243, 156, 18, 0.6);
            transition: 0.4s;
        }

        @keyframes pulseZoom {
            0% {
                transform: scale(1);
                box-shadow: 0 0 10px rgba(243, 156, 18, 0.4);
            }

            50% {
                transform: scale(1.2);
                box-shadow: 0 0 25px rgba(243, 156, 18, 0.9);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 10px rgba(243, 156, 18, 0.4);
            }
        }

        .admin-navbar .navbar-brand img {
            animation: pulseZoom 2s infinite ease-in-out;
        }

        .admin-navbar .navbar-brand:hover img {
            animation-duration: 1s;
            transform: scale(1);
        }

        .admin-navbar .nav-link {
            position: relative;
            color: #ddd !important;
            font-weight: 600;
            padding: 10px 18px !important;
            margin: 0 6px;
            border-radius: 12px;
            transition: 0.3s;
            overflow: hidden;
        }

        .admin-navbar .nav-link::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0%;
            height: 2px;
            transition: 0.3s;
            transform: translateX(-50%);
        }

        /* Hover */
        .admin-navbar .nav-link:hover::before {
            width: 70%;
        }

        .admin-navbar .nav-link:hover {
            color: #f39c12 !important;
            background: rgba(243, 156, 18, 0.08);
            transform: translateY(-2px);
        }

        /* Icons */
        .admin-navbar .nav-link i {
            transition: 0.3s;
        }

        .admin-navbar .nav-link:hover i {
            transform: scale(1.2) rotate(5deg);
            color: #f39c12;
        }

        /* Active */
        .admin-navbar .nav-link.active {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: black !important;
            box-shadow: 0 5px 20px rgba(243, 156, 18, 0.5);
        }

        .admin-navbar .nav-link.active i {
            color: black;
        }

        /* ===== Logout Button ===== */
        .logout-wrapper .logout-btn {
            background: transparent;
            border: 1px solid #ff4d4d;
            color: #ff4d4d !important;
            border-radius: 12px;
            padding: 8px 18px !important;
            margin-left: 12px;
            font-weight: bold;
            transition: 0.3s;
        }



        /* ===== Mobile Button ===== */
        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            filter: invert(70%) sepia(100%) saturate(500%) hue-rotate(5deg);
        }

        .content-wrapper {
            background: rgba(0, 0, 0, 0.6);
            min-height: calc(100vh - 100px);
            padding: 30px 0;
        }

        .product-image-container {
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 0.375rem 0 0 0.375rem;
        }

        .product-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.02);
        }

        .card {
            border: none;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
        }

        .card-title {
            font-weight: 700;
        }

        .card-text {
            line-height: 1.6;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--secondary), var(--tertiary));
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 42, 109, 0.4);
        }

        #nav1 {
            background: rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(243, 156, 18, 0.2);
            padding: 10px 0;
            transition: all 0.3s ease;
        }

        #nv {
            color: white !important;
            margin: 0 12px;
            font-size: 15px;
            letter-spacing: 0.5px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #nv i {
            color: #f39c12;
            font-size: 18px;
        }

        #nv:hover {
            color: #f39c12 !important;
            text-shadow: 0 0 10px rgba(243, 156, 18, 0.6);
            border-bottom: 2px solid #f39c12;
        }

        .cart-badge {
            background-color: white;
            color: black;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 50%;
            position: relative;
            top: -10px;
            right: 5px;
            font-weight: bold;
            box-shadow: 0 0 5px #f39c12;
        }

        @keyframes pulse-logo {
            0% {
                transform: scale(1);
                box-shadow: 0 0 10px rgba(243, 156, 18, 0.4);
            }

            50% {
                transform: scale(1.1);
                box-shadow: 0 0 25px rgba(243, 156, 18, 0.7);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 10px rgba(243, 156, 18, 0.4);
            }
        }

        .godzilla {
            width: 70px !important;
            height: 70px !important;
            border-radius: 50%;
            border: 2px solid #f39c12;
            box-shadow: 0 0 15px rgba(243, 156, 18, 0.4);
            display: inline-block;
            animation: pulse-logo 2s infinite ease-in-out;
            transition: 0.3s;
        }

        .navbar-brand:hover .godzilla {
            animation-duration: 1s;
            cursor: pointer;
        }

        /* footer */
        .footer {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 2px solid rgba(243, 156, 18, 0.3);
            padding: 60px 0 20px;
            margin-top: 80px;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #f39c12, transparent);
            animation: lightning 4s infinite linear;
        }

        @keyframes lightning {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .footer-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 40px;
        }

        .footer-col h4 {
            color: #f39c12;
            font-family: 'Nosifer', sans-serif;
            font-size: 1.2rem;
            text-transform: uppercase;
            margin-bottom: 30px;
            position: relative;
            letter-spacing: 1px;
        }

        .footer-col h4::after {
            content: "";
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: -12px;
            width: 60px;
            height: 3px;
            background: #f39c12;
            box-shadow: 0 0 10px #f39c12;
            border-radius: 10px;
        }

        .footer-col p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            max-width: 450px;
        }

        .social-links {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }

        .social-links a:hover {
            color: #fff;
            transform: translateY(-8px) scale(1.1);
        }

        .social-links a.whatsapp:hover {
            background: #25D366;
            box-shadow: 0 0 20px rgba(37, 211, 102, 0.6);
            border-color: #25D366;
        }

        .social-links a.facebook:hover {
            background: #1877F2;
            box-shadow: 0 0 20px rgba(24, 119, 242, 0.6);
            border-color: #1877F2;
        }

        .social-links a.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            box-shadow: 0 0 20px rgba(220, 39, 67, 0.6);
        }

        .social-links a.location:hover {
            background: #ff2a6d;
            box-shadow: 0 0 20px rgba(255, 42, 109, 0.6);
            border-color: #ff2a6d;
        }

        /* Copyright Section */
        .footer-bottom {
            margin-top: 20px;
            padding: 25px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.85rem;
            margin: 0;
        }

        .footer-bottom span {
            color: #f39c12;
            font-family: 'Nosifer', sans-serif;
            font-size: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .footer {
                padding: 40px 0 20px;
            }

            .footer-col h4 {
                font-size: 1.1rem;
            }

            .footer-col p {
                font-size: 0.9rem;
                padding: 0 10px;
            }
        }
    </style>
</head>

<body>
    <!-- dot class -->
    <div class="floating-dots"></div>

    <!-- Loading Screen -->
    <div id="loading">
        <div class="loader-container">
            <div class="loader"></div>
            <div class="loader-text">LOADING...</div>
        </div>
    </div>


    <!-- Start Navbar -->
    <nav class="navbar navbar-expand-lg admin-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php" style="align-items: flex-start;">
                <img src="../img/icon-title.png" alt="Godzilla" class="godzilla me-2" style="transform: translateY(-10px); width: 70px !important; height: 70px !important;">
            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-crown"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box-open"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-receipt"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Live Store
                        </a>
                    </li>
                    <li class="nav-item logout-wrapper">
                        <a class="nav-link logout-btn" href="../login.php">
                            <i class="fas fa-power-off"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->