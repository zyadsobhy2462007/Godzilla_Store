<?php
include("../inc/config.php");
include("../inc/admin_header.php");
$current_page = "products";

// جلب جميع المنتجات
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Manage Products | Godzilla Admin</title>

    <!-- Font awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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


        /* Admin Content */
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

        /* Container Styling */
        .products-table {
            background: rgba(255, 255, 255, 0.03);
            /* شفافية خفيفة جداً */
            backdrop-filter: blur(15px);
            /* تأثير الزجاج المغبش */
            -webkit-backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            color: white;
            border-collapse: separate;
            border-spacing: 0 10px;
            /* لإعطاء مسافة بين الصفوف وجعلها كالكروت */
        }

        /* Table Header */
        .table thead th {
            background: transparent;
            color: #f39c12;
            /* البرتقالي الخاص بك */
            font-weight: 700;
            border: none;
            padding: 18px 20px;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1.2px;
            border-radius: 12px;
        }

        /* Table Rows */
        .table tbody tr {
            background: rgba(251, 9, 9, 0.02);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Image Styling */

        .table tbody tr:hover .product-image {
            transform: rotate(-3deg);
        }

        .product-name {
            font-weight: 600;
            color: #ffffff;
            font-size: 1.05rem;
            display: block;
        }

        .product-price {
            color: #2ecc71;
            /* الأخضر الخاص بك */
            font-weight: 800;
            font-size: 1.15rem;
            background: rgba(46, 204, 113, 0.1);
            padding: 4px 12px;
            border-radius: 8px;
            display: inline-block;
        }

        .product-id {
            color: #777;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }

        /* Action Buttons */
        .btn-action {
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-edit {
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
            border: 1px solid rgba(52, 152, 219, 0.3);
        }

        .btn-edit:hover {
            background: #3498db;
            color: white;
            box-shadow: 0 8px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-delete {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: white;
            box-shadow: 0 8px 15px rgba(231, 76, 60, 0.3);
        }

        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .products-header h2 {
            font-family: 'Nosifer', sans-serif;
            font-size: 2rem;
            color: #f39c12;
            margin: 0;
        }

        .btn-add-product {
            background: linear-gradient(135deg, #20623c, #25ad5e);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-add-product:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .products-table {
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            color: white;
        }

        .table thead th {
            background: transparent;
            color: #f39c12;
            font-weight: 600;
            border: none;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .table tbody td {
            background: rgba(0, 0, 0, 0.07);
            /* اللون الأحمر اللي اخترته */
            padding: 15px;
            vertical-align: middle;
            border: none;
            /* شيل البوردر عشان الريديس يبان نظيف */
        }

        .table tbody td:first-child {
            border-radius: 15px 0 0 15px;
        }

        /* تدوير الحافة اليمنى لآخر خلية في الصف */
        .table tbody td:last-child {
            border-radius: 0 15px 15px 0;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .product-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-name {
            font-weight: 600;
            color: white;
        }

        .product-price {
            color: #2ecc71;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .product-id {
            color: #888;
            font-size: 0.9rem;
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0 3px;
        }

        .btn-edit {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #888;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #f39c12;
        }


        @media (max-width: 768px) {
            .products-header {
                flex-direction: column;
                text-align: center;
            }

            .product-image {
                width: 50px;
                height: 50px;
            }
        }

        /* --- RESPONSIVE PRODUCTS STRATEGY --- */

        @media (max-width: 991px) {
            .admin-header h1 {
                font-size: 2rem;
            }

            .products-header h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .admin-content {
                padding-top: 80px;
            }

            .products-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .btn-add-product {
                width: 100%;
                justify-content: center;
            }

            /* تحويل الجدول لنظام كروت نيون */
            .table-responsive {
                border: none;
            }

            .table thead {
                display: none;
                /* إخفاء رؤوس الجدول في الموبايل */
            }

            .table tbody tr {
                display: block;
                background: rgba(255, 255, 255, 0.03);
                margin-bottom: 25px;
                border-radius: 20px !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                padding: 15px;
                position: relative;
                overflow: hidden;
            }

            .table tbody td {
                display: block;
                text-align: right;
                padding: 10px 5px;
                border: none !important;
                background: transparent !important;
                border-radius: 0 !important;
            }

            /* جعل الصورة والاسم في الأعلى بشكل مميز */
            .table tbody td:nth-child(2) {
                text-align: center;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
                padding-bottom: 15px;
            }

            .product-image {
                width: 100px;
                height: 100px;
                margin: 0 auto;
            }

            /* إضافة تسميات بجانب البيانات */
            .table tbody td:nth-child(1)::before {
                content: "Product ID:";
                float: left;
                color: #f39c12;
                font-weight: bold;
            }

            .table tbody td:nth-child(3)::before {
                content: "Product Name:";
                float: left;
                color: #f39c12;
                font-weight: bold;
            }

            .table tbody td:nth-child(4)::before {
                content: "Price:";
                float: left;
                color: #f39c12;
                font-weight: bold;
            }

            /* الأزرار في الأسفل */
            .table tbody td:last-child {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 10px;
                border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
                padding-top: 15px;
            }

            .btn-action {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .admin-header h1 {
                font-size: 1.5rem;
            }

            .product-name {
                font-size: 1rem;
            }

            .product-price {
                font-size: 1rem;
            }
        }

        .btn-action {
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;

            /* الكود السحري للسنتر */
            display: inline-flex;
            /* بيخلي الزرار مرن */
            align-items: center;
            /* سنتر عمودي */
            justify-content: center;
            /* سنتر أفقي */

            gap: 8px;
            /* مسافة ثابتة ومظبوطة بين الأيقونة والكلام */
            transition: all 0.3s ease;
            text-decoration: none;
            min-width: 100px;
            /* بيدي عرض أدنى للزرار عشان شكلهم يبقوا قد بعض */
        }

        /* تعديل أزرار الجدول في الموبايل عشان تملأ المساحة */
        @media (max-width: 768px) {
            .table tbody td:last-child {
                display: flex;
                justify-content: center;
                gap: 15px;
                /* توسيع المسافة بين زرار التعديل والمسح في الموبايل */
                padding: 20px 10px !important;
            }

            .btn-action {
                flex: 1;
                /* بيخلي الزرارين ياخدوا نفس العرض في الموبايل */
                padding: 12px;
                /* تكبير مساحة الضغط بالصابع */
            }
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <div class="admin-content">
        <div class="container">
            <div class="admin-header">
                <h1><i class="fas fa-boxes"></i> Manage Products</h1>
                <p>View and manage your store products</p>
            </div>

            <div class="products-header">
                <h2>All Products</h2>
                <a href="add_product.php" class="btn-add-product">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>

            <?php if (count($products) > 0): ?>
                <div class="products-table">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><span class="product-id">#<?php echo $product['id']; ?></span></td>
                                        <td>
                                            <img src="../uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                                        </td>
                                        <td><span class="product-name"><?php echo htmlspecialchars($product['name']); ?></span></td>
                                        <td><span class="product-price">$<?php echo number_format($product['price'], 2); ?></span></td>
                                        <td>
                                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn-action btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="products-table">
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>No Products Found</h3>
                        <p>Start by adding your first product!</p>
                        <a href="add_product.php" class="btn-add-product mt-3">
                            <i class="fas fa-plus"></i> Add New Product
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include("../inc/footer.php"); ?>