<?php
ob_start();
include("../inc/config.php");
include("../inc/admin_header.php");
$current_page = "edit_product";

$product = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product']) && $product) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $imageName = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = '../uploads/';
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_file_name = time() . '_' . uniqid() . '.' . $file_extension;
        $target = $uploads_dir . $new_file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imageName = $new_file_name;
        }
    }

    $update = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    if ($update->execute([$name, $price, $imageName, $product_id])) {
        header("Location: products.php?status=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Edit Product | Godzilla Supplement</title>

    <link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #0a0a0a;
            --neon-orange: #f39c12;
            --glass-bg: rgba(20, 20, 20, 0.95);
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
            padding: 120px 20px 60px;
        }

        .admin-header h1 {
            font-family: 'Nosifer', sans-serif;
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            background: linear-gradient(50deg, #f39c12, #ffcc00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .form-container {
            background: var(--glass-bg);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            max-width: 650px;
            margin: 0 auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        .form-label {
            color: var(--neon-orange);
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white !important;
            padding: 12px 15px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--neon-orange);
            box-shadow: 0 0 15px rgba(243, 156, 18, 0.3);
            outline: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            transition: 0.3s;
            cursor: pointer;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.4);
        }

        .btn-back {
            background: red;
            color: #eee;
            padding: 12px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: rgba(255, 0, 0, 0.27);
            color: #ffffff;
        }

        .current-image-wrapper {
            background: rgba(255, 255, 255, 0.03);
            padding: 15px;
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .current-image {
            border-radius: 10px;
            border: 2px solid var(--neon-orange);
            box-shadow: 0 0 15px rgba(243, 156, 18, 0.2);
        }
        .action-buttons-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px; /* مسافة بين الزرارين */
        margin-top: 20px;
    }

    .btn-back, .btn-submit {
        text-align: center;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 1; /* عشان ياخدوا مساحات متساوية */
    }

    /* 📱 للموبايل (أقل من 576px) */
    @media (max-width: 576px) {
        .action-buttons-container {
            flex-direction: column; /* الزراير فوق بعض */
        }

        .btn-back, .btn-submit {
            width: 100%; /* الزرار ياخد العرض كامل */
            order: 1; /* ترتيب الظهور */
        }

        /* خلي زرار التأكيد هو اللي فوق عشان أسهل للأدمن */
        .btn-submit {
            order: 1;
            padding: 15px; /* تكبير المساحة للضغط */
        }

        .btn-back {
            order: 2;
            background: red;
            border: none;
            color: white; /* خليه أهدى شوية في الموبايل */
        }
    }
    </style>
</head>

<body>

    <div class="admin-content">
        <div class="container">
            <div class="admin-header text-center">
                <h1><i class="fas fa-edit me-2"></i>Edit Product</h1>
                <p class="text-white-50">Securely update product records in the database</p>
            </div>

            <?php if (!$product): ?>
                <div class="alert alert-danger text-center bg-dark border-danger text-danger py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3 d-block"></i>
                    Product ID is invalid or has been removed from the vault.
                </div>
                <div class="text-center mt-3"><a href="products.php" class="btn-back">Return to Products</a></div>
            <?php else: ?>
                <div class="form-container">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-signature me-2"></i>Product Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" placeholder="Enter product name..." required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-dollar-sign me-2"></i>Unit Price</label>
                            <input type="text" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" placeholder="0.00" required>
                        </div>

                        <div class="mb-4 text-center">
                            <label class="form-label text-start"><i class="fas fa-eye me-2"></i>Current Preview</label>
                            <div class="current-image-wrapper">
                                <img src="../uploads/<?php echo $product['image']; ?>" width="140" class="current-image" alt="Current Product">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-camera me-2"></i>Update Visual Asset</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-white-50">Leave empty to keep the current image.</small>
                        </div>

                        <hr style="opacity: 0.1; margin: 30px 0;">

<div class="action-buttons-container">
    <a href="products.php" class="btn-back">
        <i class="fas fa-chevron-left me-2"></i>Discard
    </a>
    <button type="submit" name="update_product" class="btn-submit">
        <i class="fas fa-save me-2"></i>Confirm Changes
    </button>
</div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include("../inc/footer.php"); ?>
</body>

</html>