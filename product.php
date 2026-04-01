<?php
$page_title = "Product Details";
include("./inc/config.php");
include("./inc/header.php");

/* Validate product ID from URL parameter */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

/* Fetch product details from database */
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

/* Handle product not found */
if (!$product) {
    echo "<div class='container mt-5 text-center'><h3 class='text-white'>Product not found</h3><a href='index.php' class='btn btn-warning'>Go Back</a></div>";
    include("inc/footer.php");
    exit();
}
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-attachment: fixed;
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        margin: 0;
        padding: 0;
    }

    .content-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 150px 0;
    }

    .product-details-card {
        background: rgba(18, 18, 18, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 40px;
        overflow: hidden;
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.6);
        position: relative;
    }

    .product-details-card::before {
        content: "";
        position: absolute;
        top: -50px;
        left: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 193, 7, 0.1) 0%, transparent 70%);
        z-index: 0;
    }

    .image-section {
        position: relative;
        background: #0f0f0f;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 550px;
    }

    @keyframes pulse-animation {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.08);
        }

        100% {
            transform: scale(1);
        }
    }

    .product-main-img {
        width: 80%;
        height: auto;
        object-fit: contain;
        z-index: 2;
        filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.5));
        animation: pulse-animation 4s infinite ease-in-out;
        transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1);
    }

    .image-section:hover .product-main-img {
        filter: drop-shadow(0 30px 50px rgba(255, 193, 7, 0.2));
    }

    .info-section {
        padding: 60px !important;
        position: relative;
        z-index: 1;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.02) 0%, transparent 100%);
    }

    .category-badge {
        color: #ffc107;
        text-transform: uppercase;
        letter-spacing: 4px;
        font-size: 0.75rem;
        font-weight: 800;
        margin-bottom: 20px;
        display: block;
        opacity: 0.8;
    }

    .product-title {
        font-size: 3.2rem;
        font-weight: 800;
        margin-bottom: 25px;
        line-height: 1.1;
        background: linear-gradient(to right, #fff, #bbb);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .product-description {
        color: rgba(255, 255, 255, 0.6);
        font-size: 1.05rem;
        line-height: 1.8;
        margin-bottom: 40px;
        font-weight: 300;
    }

    .price-display {
        font-size: 2.8rem;
        font-weight: 800;
        color: #ffc107;
        margin-bottom: 45px;
        display: flex;
        align-items: baseline;
        gap: 10px;
    }

    .price-display small {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.4);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .action-buttons {
        display: flex;
        gap: 20px;
    }

    .btn-cart-main {
        background: #ffc107;
        color: #000;
        border: none;
        padding: 20px 40px;
        border-radius: 20px;
        font-weight: 800;
        flex-grow: 1;
        transition: all 0.4s;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 30px rgba(255, 193, 7, 0.2);
    }

    .btn-cart-main:hover {
        background: #fff;
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(255, 193, 7, 0.4);
    }

    .btn-back-circle {
        background: rgba(255, 255, 255, 0.03);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        width: 65px;
        height: 65px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        text-decoration: none;
    }

    .btn-back-circle:hover {
        background: #ffc107;
        color: #000;
        border-color: #ffc107;
        transform: rotate(-10deg);
    }

    .trust-icons {
        margin-top: 50px;
        padding-top: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        gap: 30px;
    }

    .trust-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .trust-item i {
        color: #ffc107;
    }

    @media (max-width: 991px) {
        .product-title {
            font-size: 2.5rem;
        }

        .image-section {
            min-height: 400px;
        }
    }
</style>

<div class="content-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="product-details-card">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="image-section">
                                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                    class="product-main-img">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="info-section">
                                <span class="category-badge"><i class="fas fa-crown me-2"></i>Premium Collection</span>
                                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                                <p class="product-description">
                                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                                </p>

                                <div class="price-display">
                                    <small>Price:</small> $<?php echo number_format($product['price'], 2); ?>
                                </div>

                                <div class="action-buttons">
                                    <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn fw-bold btn-cart">
                                        <i class="fas fa-shopping-bag me-2"></i> Add to Collection
                                    </button>

                                    <a href="products.php" class="btn fw-bold btn-cart">
                                        <i class="fas fa-arrow-left fa-lg"></i>
                                    </a>
                                </div>

                                <div class="trust-icons">
                                    <div class="trust-item">
                                        <i class="fas fa-shipping-fast"></i>
                                        <span>Express Delivery</span>
                                    </div>
                                    <div class="trust-item">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>Secure Payment</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addToCart(productId) {
        fetch('cart.php?add=' + productId)
            .then(response => {
                if (response.ok) {
                    alert('Excellent Choice! Added to your collection.');
                }
            });
    }
</script>

<?php include("./inc/footer.php"); ?>