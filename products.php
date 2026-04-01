<?php
session_start();
$page_title = "Products";

include("./inc/config.php");
include("./inc/header.php");

$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-gold: #ffc107;
        --dark-bg: #050505;
        --card-bg: #121212;
        --text-muted: #888;
    }

    body {
        background-color: var(--dark-bg);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .products-container {
        padding: 80px 0;
    }

    .section-title {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(to right, #fff, var(--primary-gold));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 50px;
        text-align: center;
    }

    .product-card {
        background: var(--card-bg);
        border: 1px solid #222;
        border-radius: 24px;
        position: relative;
        transition: all 0.5s cubic-bezier(0.15, 0.83, 0.66, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: var(--primary-gold);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .product-image-wrapper {
        position: relative;
        margin: 15px;
        border-radius: 18px;
        overflow: hidden;
        height: 200px;
        background: #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    /* Auto pulse animation for product images */
    @keyframes product-pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.06);
        }

        100% {
            transform: scale(1);
        }
    }

    .product-image-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: 0.8s ease;
        /* Auto-play animation */
        animation: product-pulse 4s infinite ease-in-out;
    }

    .product-card:hover .product-image-wrapper img {
        /* Faster animation on hover for interaction */
        animation-duration: 2s;
        filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.5));
    }

    /* ------------------------------------------ */

    .quick-view {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 0;
        background: #fff;
        color: #000;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.4s;
        font-size: 0.8rem;
        z-index: 3;
    }

    .product-card:hover .quick-view {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }

    .product-details {
        padding: 0 20px 20px;
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #fff;
    }

    .product-price-tag {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--primary-gold);
        margin-bottom: 15px;
    }

    .add-cart-btn {
        background: transparent;
        color: #fff;
        border: 1px solid #333;
        padding: 12px;
        border-radius: 12px;
        width: 100%;
        font-weight: 600;
        position: relative;
        overflow: hidden;
        transition: 0.4s;
        z-index: 1;
        font-size: 0.9rem;
    }

    .add-cart-btn::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 0;
        background: var(--primary-gold);
        transition: 0.4s;
        z-index: -1;
    }

    .add-cart-btn:hover {
        color: #000;
        border-color: var(--primary-gold);
    }

    .add-cart-btn:hover::after {
        height: 100%;
    }

    .custom-toast {
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(-100px);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        padding: 15px 40px;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        font-weight: 600;
        z-index: 10000;
        transition: 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .custom-toast.active {
        transform: translateX(-50%) translateY(0);
    }

    .section-title {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(to right, #fff, #ffc107);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 60px;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: "";
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--primary-gold), transparent);
        border-radius: 10px;
    }

    /* Professional Responsive Media Queries */

    /* X-Large screens */
    @media (min-width: 1400px) {
        .container {
            max-width: 1320px;
        }
    }

    /* Tablet screens */
    @media (max-width: 991px) {
        .products-container {
            padding: 60px 0;
        }

        .section-title {
            font-size: 2.5rem;
        }

        .product-image-wrapper {
            height: 180px;
        }
    }

    /* Mobile phones (Landscape & Portrait) */
    @media (max-width: 767px) {
        .section-title {
            font-size: 2rem;
            margin-bottom: 40px;
        }

        .product-card {
            margin-bottom: 10px;
        }

        .product-details {
            padding: 0 15px 15px;
        }

        .product-name {
            font-size: 1rem;
        }

        .product-price-tag {
            font-size: 1.2rem;
        }

        /* On mobile, reduce hover scale to avoid touch issues */
        .product-card:hover {
            transform: translateY(-5px);
        }

        /* Make toast larger on mobile */
        .custom-toast {
            width: 90%;
            padding: 12px 20px;
            font-size: 0.9rem;
            text-align: center;
        }
    }

    /* Small mobile phones */
    @media (max-width: 480px) {
        .section-title {
            font-size: 1.8rem;
        }

        /* Full-width cards or 2-column on small screens (col-sm-6) */
        .row.g-4 {
            /* Reduce gutters */
        }

        .add-cart-btn {
            padding: 10px;
            font-size: 0.8rem;
        }
    }

    /* Improve Quick View on touch devices */
    @media (hover: none) {
        .quick-view {
            opacity: 1 !important;
            transform: translate(-50%, -50%) scale(1) !important;
            background: rgba(255, 255, 255, 0.9);
        }
    }
    .btn-cart {
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

<div class="container products-container">
    <div id="toast" class="custom-toast">Added to collection! 🔥</div>

    <div class="text-center">
        <h1 class="section-title">All Products</h1>
    </div>

    <div class="row g-4">
        <?php foreach ($products as $product): ?>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <?php
                        $image_path = 'uploads/' . $product['image'];
                        $img_src = (!empty($product['image']) && file_exists($image_path)) ? $image_path : 'uploads/default.png';
                        ?>
                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

                        <a href="product.php?id=<?php echo $product['id']; ?>" class="quick-view">
                            <i class="fa-solid fa-expand me-2"></i> Details
                        </a>
                    </div>

                    <div class="product-details">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-price-tag">$<?php echo number_format($product['price'], 0); ?></div>

                        <button onclick="smartAddToCart(<?php echo $product['id']; ?>, this)" class="btn fw-bold btn-cart fw-bold">
                            <i class="fa-solid fa-bag-shopping me-2"></i> Add To Cart
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function smartAddToCart(productId, btn) {
        btn.style.transform = "scale(0.95)";
        setTimeout(() => btn.style.transform = "scale(1)", 100);

        fetch('cart.php?add=' + productId)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const toast = document.getElementById('toast');
                    toast.classList.add('active');

                    const originalContent = btn.innerHTML;
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Added';
                    btn.style.color = "#000";

                    setTimeout(() => {
                        toast.classList.remove('active');
                        btn.innerHTML = originalContent;
                        btn.style.color = "#fff";
                    }, 3000);
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>

<?php include("./inc/footer.php"); ?>