<?php
// 1. PHP logic must be first in file
include("../inc/config.php");

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Process uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $uploads_dir = '../uploads/';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        $imageName = time() . '_' . $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        $target = $uploads_dir . $imageName;
        if (!move_uploaded_file($tmp, $target)) {
            $imageName = null;
        }
    } else {
        $imageName = null;
    }

    // Insert data into database
    $stmt = $pdo->prepare("INSERT INTO products(name, price, image) VALUES(?,?,?)");
    if ($stmt->execute([$name, $price, $imageName])) {
        // التحويل لصفحة المنتجات مع إرسال حالة النجاح في الرابط
        header("Location: products.php?msg=added");
        exit;
    }
}

include("../inc/admin_header.php");
$current_page = "add_product";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Admin Dashboard | Godzilla Supplement</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
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
            --tertiary: #05d9e8;
            --accent: #f2f542;
            --accentely: #e67e22;
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
            margin-bottom: 10px;
        }

        .form-container {
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.95), rgba(20, 20, 20, 0.98));
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-label {
            color: #f39c12;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 15px;
            border-radius: 10px;
            width: 100%;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #f39c12;
            box-shadow: 0 0 15px rgba(243, 156, 18, 0.3);
            outline: none;
            color: white;
        }

        .btn-submit {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(39, 174, 96, 0.4);
        }

        .btn-back {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
        }
        .actions-group {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .btn-submit, .btn-back {
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        cursor: pointer;
    }

    /* ستايل زرار الإضافة (Neon Gold) */
    .btn-submit {
        background: green;
        color: #000;
        flex: 2; /* خليه أعرض شوية من زرار الرجوع */
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }

    /* ستايل زرار الرجوع */
    .btn-back {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        flex: 1;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.5);
    }

    /* 📱 للموبايل (أقل من 768px) */
    @media (max-width: 768px) {
        .actions-group {
            flex-direction: column; /* الزراير فوق بعض */
            gap: 12px !important; /* مسافة بينهم */
        }

        .btn-submit, .btn-back {
            width: 100%; /* العرض كامل */
            padding: 15px; /* مساحة ضغط أكبر */
            font-size: 1rem;
        }

        /* ترتيب الزراير: الإضافة فوق والرجوع تحت */
        .btn-submit {
            order: 1;
        }
        
        .btn-back {
            order: 2;
            background: red;
            border: none;
            color: white;
            font-size: 0.9rem;
        }
    }
    </style>
</head>

<body>
    <div class="admin-content">
        <div class="container">
            <div class="admin-header">
                <h1><i class="fas fa-plus-circle"></i> Add New Product</h1>
                <p>Fill in the details to add a new product</p>
            </div>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-box"></i> Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-dollar-sign"></i> Price</label>
                        <input type="text" name="price" class="form-control" placeholder="Enter price" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-image"></i> Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
<div class="d-flex gap-3 mt-4 actions-group">
    <button type="submit" class="btn-submit" name="add">
        <i class="fas fa-plus"></i> Add Product
    </button>
    <a href="products.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>
                </form>
            </div>
        </div>
    </div>

    <?php include("../inc/footer.php"); ?>
</body>

</html>