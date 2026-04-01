<?php
$page_title = "About Us";
include("./inc/header.php");
?>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@300;400;600&family=Nosifer&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-gold: #ffc107;
        --primary: #0a0a0a;
        --neon-shadow: rgba(255, 193, 7, 0.2);
    }

    body {
        overflow-x: hidden;
    }

    .about-wrapper {
        position: relative;
        padding: 120px 0;
        background: var(--deep-black);
        font-family: 'Inter', sans-serif;
    }

    /* Animated light background with professional transparency */
    .about-wrapper::before,
    .about-wrapper::after {
        content: '';
        position: absolute;
        width: clamp(300px, 40vw, 600px);
        height: clamp(300px, 40vw, 600px);
        border-radius: 50%;
        background: radial-gradient(circle, var(--gold-glow) 0%, transparent 80%);
        z-index: 0;
        pointer-events: none;
        filter: blur(60px);
        animation: drift 12s infinite alternate ease-in-out;
    }

    .about-wrapper::before {
        top: -5%;
        left: -5%;
    }

    .about-wrapper::after {
        bottom: -5%;
        right: -5%;
        animation-delay: -6s;
    }

    @keyframes drift {
        0% {
            transform: translate(0, 0) scale(1);
            opacity: 0.5;
        }

        100% {
            transform: translate(50px, 30px) scale(1.1);
            opacity: 0.8;
        }
    }

    .hero-title {
        font-family: 'Nosifer', sans-serif;
        font-size: clamp(2rem, 6vw, 4rem);
        background: linear-gradient(100deg, transparent, #ffa008, transparent);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.9));
        line-height: 1;
        letter-spacing: -2px;
        margin-bottom: 20px;
        text-align: center;
        word-break: break-word;
        animation: titleAppear 1.2s cubic-bezier(0.215, 0.61, 0.355, 1) forwards;
    }


    @media (max-width: 400px) {
        .hero-title {
            font-size: 2rem;
            letter-spacing: -1px;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .hero-title {
            font-size: 3.5rem;
            /* Adjusted from 4.5rem */
        }
    }

    @media (min-width: 1000px) {
        .hero-title {
            font-size: 5rem;
            /* Adjusted from 6.5rem */
            margin-bottom: 25px;
        }
    }

    @keyframes titleAppear {
        0% {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
            filter: blur(10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
    }

    .sub-title {
        font-family: 'Orbitron', sans-serif;
        color: var(--primary-gold);
        letter-spacing: clamp(5px, 2vw, 12px);
        font-size: clamp(0.7rem, 1.5vw, 0.85rem);
        text-transform: uppercase;
        display: block;
        margin-bottom: 30px;
        opacity: 0.9;
    }


    /* Professional floating icon adjustment */
    .main-icon {
        font-size: 4.5rem;
        color: var(--primary-gold);
        margin-bottom: 30px;
        display: inline-block;
        filter: drop-shadow(0 0 20px var(--neon-shadow));
        animation: floatIcon 4s infinite ease-in-out;
    }

    @keyframes floatIcon {

        0%,
        100% {
            transform: translateY(0) scale(1);
            filter: drop-shadow(0 0 15px var(--neon-shadow));
        }

        50% {
            transform: translateY(-20px) scale(1.1);
            filter: drop-shadow(0 0 35px var(--primary-gold));
        }
    }

    .story-text {
        font-size: 1.2rem;
        line-height: 2.1;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 300;
        max-width: 90%;
        margin: 0 auto;
    }

    /* Luxury counters */
    .stat-box {
        padding: 30px;
        border-radius: 25px;
        background: rgba(255, 255, 255, 0.01);
        border: 1px solid rgba(255, 255, 255, 0.03);
        transition: 0.4s;
    }

    .stat-box:hover {
        background: rgba(255, 193, 7, 0.03);
        border-color: var(--primary-gold);
    }

    .stat-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.2rem;
        font-weight: 700;
        color: #fff;
        display: block;
        background: linear-gradient(to bottom, #fff, #777);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-label {
        color: var(--primary-gold);
        text-transform: uppercase;
        letter-spacing: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .godzilla-img-wrap {
        position: relative;
        display: inline-block;
    }

    .godzilla-img-wrap::after {
        content: '';
        position: absolute;
        inset: -10px;
        border: 2px dashed var(--primary-gold);
        border-radius: 50%;
        animation: rotate 20s linear infinite;
        opacity: 0.3;
    }

    @keyframes rotate {
        from {
            transform: rotate(0);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .godzilla-img {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--glass-bg);
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
    }

    .glass-card {
        position: relative;
        padding: 60px 40px;
        border-radius: 40px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform 0.4s ease;
        border: none;
        height: 100%;
        z-index: 1;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        width: 150%;
        height: 150%;
        background-image: conic-gradient(transparent, var(--primary-gold), transparent 30%);
        animation: rotateNeon 4s linear infinite;
        z-index: -2;
    }

    .glass-card::after {
        content: '';
        position: absolute;
        inset: 3px;
        background: #0a0a0a;
        border-radius: 38px;
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

    /* Confirm horizontal alignment in small cards */
    .glass-card.d-flex {
        flex-direction: row !important;
        /* Ensure always horizontal */
        justify-content: flex-start !important;
        align-items: center !important;
        padding: 25px 30px !important;
        /* Reduce padding for side cards */
    }

    /* Ensure content appears above neon effect */
    .glass-card .icon-box,
    .glass-card .content-box {
        position: relative;
        z-index: 5;
    }

    .glass-card .icon-box {
        min-width: 50px;
        /* Uniform icon spacing */
        text-align: center;
    }

    .stat-box {
        position: relative;
        padding: 45px 25px;
        border-radius: 25px;
        /* Deep black background */
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 1;
        border: none;
    }

    .stat-box::before {
        content: '';
        position: absolute;
        width: 160%;
        height: 160%;
        background: conic-gradient(transparent,
                /* Gold */
                transparent 25%,
                transparent 50%,
                /* Snow white */
                transparent 75%);
        animation: neonRotate 4s linear infinite;
        z-index: -2;
    }

    .stat-box::after {
        content: '';
        position: absolute;
        background: linear-gradient(135deg, rgba(20, 20, 20, 0.95), rgba(5, 5, 5, 1));
        border-radius: 23px;
        z-index: -1;
    }

    .stat-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 5px;
        background: linear-gradient(to bottom, #ffffff, #d2a212);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 0 8px rgba(255, 193, 7, 0.4));
        position: relative;
        z-index: 2;
    }

    .stat-label {
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 4px;
        font-weight: 600;
        position: relative;
        z-index: 2;
        transition: 0.3s;
    }

    @keyframes neonRotate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div class="about-wrapper">
    <div class="container">
        <div class="text-center mb-2">
            <h1 class="hero-title">GODZILLA</h1>
            <span class="sub-title">Ultimate Power Legacy</span>

        </div>

        <div class="row g-5 align-items-stretch">
            <div class="col-lg-7">
                <div class="glass-card text-center">
                    <div class="main-icon">
                        <i class="fas fa-fist-raised"></i>
                    </div>
                    <h2 class="text-white mb-4 fw-bold" style="font-family: 'Orbitron';">The Supplement King</h2>
                    <p class="story-text">
                        We don't just sell supplements; we engineer results. Born from the intensity of underground gyms, Godzilla was built for those who demand more than average. Every scoop is a step toward dominance.
                    </p>
                    <a href="Products.php" class="btn fw-bold btn-cart">GO Shopping</a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="row h-100 g-4">
                    <div class="col-12">
                        <div class="glass-card p-4 d-flex flex-row align-items-center justify-content-start text-start">
                            <div class="icon-box me-4">
                                <i class="fas fa-microscope fa-2x text-warning"></i>
                            </div>
                            <div class="content-box">
                                <h5 class="text-white mb-1" style="font-family: 'Orbitron'; font-size: 1rem; letter-spacing: 1px;">Elite Science</h5>
                                <p class="small text-secondary mb-0">100% Lab-tested for peak performance.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="glass-card p-4 d-flex flex-row align-items-center justify-content-start text-start" style="--primary-gold: #e74c3c;">
                            <div class="icon-box me-4">
                                <i class="fas fa-fire-alt fa-2x text-danger"></i>
                            </div>
                            <div class="content-box">
                                <h5 class="text-white mb-1" style="font-family: 'Orbitron'; font-size: 1rem; letter-spacing: 1px;">Raw Energy</h5>
                                <p class="small text-secondary mb-0">Zero fillers. Maximum explosive power.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <div class="godzilla-img-wrap">
                            <img src="img/header.png" class="godzilla-img" alt="Legendary Godzilla">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-5 g-4">
            <div class="col-md-3 col-6 text-center">
                <div class="stat-box">
                    <span class="stat-number" data-target="50">0</span>
                    <span class="stat-label">Warriors (K+)</span>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center">
                <div class="stat-box">
                    <span class="stat-number" data-target="24">0</span>
                    <span class="stat-label">Support (7/7)</span>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center">
                <div class="stat-box">
                    <span class="stat-number" data-target="100">0</span>
                    <span class="stat-label">Purity (%)</span>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center">
                <div class="stat-box">
                    <span class="stat-number" data-target="1">0</span>
                    <span class="stat-label">Rank (#)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Professional counter code with fade effect
    document.addEventListener("DOMContentLoaded", () => {
        const counters = document.querySelectorAll('.stat-number');
        const options = {
            threshold: 0.6
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    let count = 0;
                    const speed = 2000 / target; // Adjust speed based on number

                    const updateCount = () => {
                        const increment = target / 100;
                        if (count < target) {
                            count += increment;
                            counter.innerText = Math.ceil(count);
                            setTimeout(updateCount, 20);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    updateCount();
                    observer.unobserve(counter);
                }
            });
        }, options);

        counters.forEach(c => observer.observe(c));
    });
</script>

<?php include("./inc/footer.php"); ?>