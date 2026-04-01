<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php

/**
 * Godzilla Admin - Neon LED Edition
 * UI: Animated Neon Borders, Glassmorphism, Dual Neon Action Buttons
 */

session_start();
require_once("./inc/config.php");

$page_title = "Secure Admin Access";
$error = "";

if (isset($_POST['login'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin'] = $admin['email'];
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "Access Denied: Invalid Email or Password";
        }
    } else {
        $error = "Please fill in all fields";
    }
}

include("inc/header.php");
?>

<style>
    /* --- Layout & Background --- */
    .login-wrapper {
        min-height: 90vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    /* --- Neon LED Card Effect --- */
    .neon-card {
        position: relative;
        width: 100%;
        max-width: 480px;
        background: #0a0a0a;
        border-radius: 24px;
        padding: 3px;
        overflow: hidden;
    }

    .neon-card::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(transparent, #f39c12, transparent 30%);
        animation: rotate-neon 4s linear infinite;
    }

    @keyframes rotate-neon {
        100% {
            transform: rotate(360deg);
        }
    }

    .login-box {
        position: relative;
        z-index: 1;
        background: rgba(15, 15, 15, 0.98);
        border-radius: 22px;
        padding: 45px 35px;
    }

    .login-box h2 {
        text-align: center;
        margin-bottom: 35px;
        color: #f39c12;
        font-family: 'Nosifer', sans-serif;
        font-size: 1.3rem;
        text-shadow: 0 0 10px rgba(243, 156, 18, 0.5);
    }

    /* --- Inputs Styling --- */
    .input-group-custom {
        position: relative;
        margin-bottom: 25px;
        color: #000000 !important;

    }

    .form-control {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 15px 15px 15px 50px;
        color: #000000 !important;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #f39c12;
        box-shadow: 0 0 15px rgba(243, 156, 18, 0.2);
        color: #000000 !important;

    }

    .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(243, 156, 18, 0.5);
        transition: 0.3s;

    }

    .form-control:focus+.input-icon {
        color: #f39c12;
        transform: translateY(-50%) scale(1.1);
    }

    .action-container {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        align-items: stretch;
    }

    .btn-back-home {
        flex: 1;
        padding: 14px;
        border-radius: 12px;
        background: linear-gradient(45deg, #f39c12, #e67e22);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #000000 !important;
        font-weight: 800;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .btn-back-home:hover {
        background: #e67e22;
        border-color: #f39c12;
        color: #ffffff !important;
        transform: translateY(-3px);
    }


    .btn-neon-submit {
        flex: 2;
        padding: 14px;
        border-radius: 12px;
        background: linear-gradient(45deg, #f39c12, #e67e22);
        color: #000 !important;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s ease;
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        position: relative;
        overflow: hidden;
        font-size: 0.85rem;
    }

    .btn-neon-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 25px rgba(243, 156, 18, 0.6);
        background: #e67e22;
        color: white !important;
    }

    .btn-neon-submit::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -100%;
        width: 100%;
        height: 200%;
        background: #e67e22;
        transform: rotate(45deg);
        transition: 0.6s;
    }

    .btn-neon-submit:hover::after {
        left: 100%;
    }

    /* --- Error Shake Animation --- */
    .error-alert {
        background: rgba(255, 77, 77, 0.1);
        border: 1px solid #ff4d4d;
        color: #ff4d4d;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
        animation: shake 0.4s;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }
    form {
        width: 100%;
        max-width: 500px; /* أقصى عرض للـ Form عشان ميسرحش في الشاشات الكبيرة */
        margin: 0 auto;
        padding: 20px;
    }

    /* تنسيق مجموعات الإدخال */
    .input-group-custom {
        position: relative;
        margin-bottom: 25px;
        width: 100%;
    }

    .form-control {
        width: 100%;
        padding: 12px 45px; /* مساحة للأيقونة */
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(243, 156, 18, 0.3);
        border-radius: 10px;
        color: #fff;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #f39c12;
        box-shadow: 0 0 10px rgba(243, 156, 18, 0.2);
        outline: none;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #f39c12;
    }

    /* حاوية الأزرار - التحكم في الـ Responsive هنا */
    .action-container {
        display: flex;
        gap: 15px;
        align-items: center;
        justify-content: space-between;
        margin-top: 30px;
        flex-wrap: wrap; /* عشان ينزلوا تحت بعض في الشاشات الصغيرة */
    }

    /* ستايل الأزرار الأساسي */
    .btn-cart, .btn-neon-submit {
        flex: 1; /* عشان ياخدوا عرض متساوي */
        min-width: 160px; /* أقل عرض للزرار قبل ما ينزل سطر جديد */
        padding: 12px 20px;
        border-radius: 10px;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cart {
        background: transparent;
        border: 1px solid #f39c12;
        color: #f39c12 !important;
    }

    .btn-neon-submit {
        background: #f39c12;
        border: none;
        color: #000;
        box-shadow: 0 0 15px rgba(243, 156, 18, 0.4);
    }

    .btn-neon-submit:hover {
        box-shadow: 0 0 25px rgba(243, 156, 18, 0.7);
        transform: translateY(-2px);
    }

    /* 📱 الموبايل (أقل من 576px) */
    @media (max-width: 576px) {
        form {
            padding: 10px;
        }

        .action-container {
            flex-direction: column; /* الزراير فوق بعض */
        }

        .btn-cart, .btn-neon-submit {
            width: 100%; /* الزرار ياخد العرض كامل */
            order: 1; /* ترتيب الظهور */
        }

        /* خلي زرار الـ Login هو الأول في الموبايل لو حبيت */
        .btn-neon-submit {
            order: 1;
        }
        .btn-cart {
            order: 2;
            border: none;
            text-decoration: underline !important;
        }
    }
</style>

<div class="login-wrapper">
    <div class="neon-card">
        <div class="login-box">

            <h2><i class="fas fa-lock-open"></i> Access Dashboard</h2>

            <?php if ($error): ?>
                <div class="error-alert">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="input-group-custom">
                    <input type="email" name="email" class="form-control" placeholder="Admin Email" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>

                <div class="input-group-custom">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <i class="fas fa-key input-icon"></i>
                </div>

                <div class="action-container">
                    <a href="index.php" class="btn fw-bold btn-cart text-decoration-none">
                        <i class="fas fa-home me-2"></i> Home
                    </a>

                    <button type="submit" name="login" class="btn-neon-submit">
                        <span>Login To Admin</span>
                        <i class="fas fa-user-shield ms-2"></i>
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
<?php include("./inc/footer.php"); ?>