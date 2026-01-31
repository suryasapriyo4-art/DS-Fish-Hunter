<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔒 Admin Login - DS Fish Hunter</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #ff0f0f 0%, #b30000 100%);
            --dark-bg: #050505;
            --glass-bg: rgba(20, 20, 20, 0.6);
            --gold: #daa520;
            --blood-red: #D00000;
            --crimson: #FF1A1A;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #020202;
            background-image:
                radial-gradient(circle at 50% 0%, #200000 0%, transparent 60%),
                radial-gradient(circle at 100% 100%, #100000 0%, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow: hidden;
        }

        .container {
            position: relative;
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            z-index: 10;
        }

        .auth-card {
            background: rgba(20, 10, 10, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 30, 30, 0.2);
            border-radius: 24px;
            padding: 3rem 2rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(255, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .auth-card:hover {
            border-color: rgba(255, 30, 30, 0.4);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 50px rgba(255, 0, 0, 0.2);
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
        }

        .logo-area {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .logo-icon {
            font-size: 3rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.5));
            animation: pulse 3s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.5));
                transform: scale(1);
            }

            50% {
                filter: drop-shadow(0 0 20px rgba(255, 0, 0, 0.8));
                transform: scale(1.05);
            }
        }

        .logo-area h1 {
            margin-top: 1rem;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .logo-area p {
            color: #aaa;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 1rem 1rem 3rem;
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #ff4d4d;
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 15px rgba(255, 77, 77, 0.2);
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            transition: color 0.3s;
        }

        .form-input:focus+.form-icon {
            color: #ff4d4d;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-submit:hover::after {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 0, 0, 0.3);
        }

        .switch-auth {
            text-align: center;
            margin-top: 1.5rem;
            color: #aaa;
            font-size: 0.9rem;
        }

        .switch-auth a {
            color: #ff4d4d;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .switch-auth a:hover {
            text-decoration: underline;
        }

        .alert-box {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.2);
            color: #ff4d4d;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: none;
            animation: fadeIn 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Bubbles Background */
        .bubbles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .bubble {
            position: absolute;
            bottom: -50px;
            background: radial-gradient(circle at 30% 30%, rgba(220, 20, 60, 0.2), transparent);
            border-radius: 50%;
            animation: rise 10s infinite ease-in;
        }

        @keyframes rise {
            0% {
                transform: translateY(0) scale(1);
                opacity: 0;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                transform: translateY(-120vh) scale(1.5);
                opacity: 0;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hidden {
            display: none !important;
        }

        /* --- MODERN LOGIN OVERLAY --- */
        #loginOverlay {
            position: fixed;
            inset: 0;
            background: #020202;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
        }

        #loginOverlay.active {
            opacity: 1;
            visibility: visible;
        }

        .overlay-gate {
            width: 100%;
            height: 2px;
            background: var(--primary-gradient);
            position: absolute;
            left: 0;
            transform: scaleX(0);
            transition: transform 1s ease;
        }

        .gate-top {
            top: 0;
        }

        .gate-bottom {
            bottom: 0;
        }

        #loginOverlay.active .overlay-gate {
            transform: scaleX(1);
        }

        .scan-effect {
            position: absolute;
            inset: 0;
            background: linear-gradient(transparent, rgba(255, 0, 0, 0.05), transparent);
            background-size: 100% 200px;
            animation: moveScan 2s linear infinite;
            pointer-events: none;
        }

        @keyframes moveScan {
            from {
                background-position: 0 -200px;
            }

            to {
                background-position: 0 100%;
            }
        }

        .access-granted {
            font-family: 'Outfit', sans-serif;
            color: #fff;
            text-align: center;
            z-index: 10;
        }

        .access-granted i {
            font-size: 4rem;
            color: #ff0f0f;
            margin-bottom: 2rem;
            filter: drop-shadow(0 0 20px rgba(255, 0, 0, 0.8));
            animation: successPulse 1s ease-in-out infinite;
        }

        .granted-title {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 10px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .granted-id {
            color: #ff0f0f;
            font-family: monospace;
            font-size: 0.8rem;
            letter-spacing: 2px;
            opacity: 0.7;
        }

        @keyframes successPulse {

            0%,
            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 20px rgba(255, 0, 0, 0.8));
            }

            50% {
                transform: scale(1.1);
                filter: drop-shadow(0 0 40px rgba(255, 0, 0, 1));
            }
        }
    </style>
</head>

<body>
    <!-- Background Bubbles -->
    <div class="bubbles" id="bubbles"></div>

    <!-- Cool Login Overlay -->
    <div id="loginOverlay">
        <div class="scan-effect"></div>
        <div class="overlay-gate gate-top"></div>
        <div class="overlay-gate gate-bottom"></div>
        <div class="access-granted">
            <i class="fas fa-shield-alt"></i>
            <div class="granted-title">Access Granted</div>
            <div class="granted-id">PROTOCOL: ADMIN_BYPASS_INIT</div>
        </div>
    </div>

    <div class="container">
        <!-- LOGIN FORM -->
        <div class="auth-card" id="loginCard">
            <div class="logo-area">
                <i class="fas fa-fish logo-icon"></i>
                <h1>Admin Login</h1>
                <p>Masuk ke dashboard manajemen DS Fish Hunter</p>
            </div>

            <div class="alert-box" id="loginAlert" style="display: none;">
                <i class="fas fa-exclamation-circle"></i> <span>Username atau password salah!</span>
            </div>

            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <input type="text" id="loginUser" class="form-input" placeholder="Username" required>
                    <i class="fas fa-user form-icon"></i>
                </div>
                <div class="form-group">
                    <input type="password" id="loginPass" class="form-input" placeholder="Password" required>
                    <i class="fas fa-lock form-icon"></i>
                </div>
                <button type="submit" class="btn-submit">MASUK <i class="fas fa-arrow-right"></i></button>
            </form>

            <div class="switch-auth">
                Belum punya akun? <a onclick="toggleForm('register')">Daftar Admin Baru</a>
            </div>
            <div class="switch-auth" style="margin-top: 0.5rem;">
                <a href="index.html" style="color: #666; font-size: 0.8rem;"><i class="fas fa-chevron-left"></i> Kembali
                    ke Beranda</a>
            </div>
        </div>

        <!-- REGISTER FORM -->
        <div class="auth-card hidden" id="registerCard">
            <div class="logo-area">
                <i class="fas fa-user-shield logo-icon"></i>
                <h1>Registrasi Admin</h1>
                <p>Buat akun untuk mengelola konten</p>
            </div>

            <div class="alert-box" id="registerAlert" style="display: none;">
                <i class="fas fa-exclamation-circle"></i> <span>Username sudah digunakan!</span>
            </div>
            <div class="alert-box" id="registerSuccess"
                style="display: none; background: rgba(0, 255, 0, 0.1); border-color: rgba(0, 255, 0, 0.2); color: #00ff00;">
                <i class="fas fa-check-circle"></i> <span>Registrasi berhasil! Silahkan login.</span>
            </div>

            <form id="registerForm" onsubmit="handleRegister(event)">
                <div class="form-group">
                    <input type="text" id="regUser" class="form-input" placeholder="Username Baru" required>
                    <i class="fas fa-user form-icon"></i>
                </div>
                <div class="form-group">
                    <input type="password" id="regPass" class="form-input" placeholder="Password" required>
                    <i class="fas fa-lock form-icon"></i>
                </div>
                <div class="form-group">
                    <input type="password" id="regPassConfirm" class="form-input" placeholder="Konfirmasi Password"
                        required>
                    <i class="fas fa-check-circle form-icon"></i>
                </div>
                <button type="submit" class="btn-submit">DAFTAR ADMIN</button>
            </form>

            <div class="switch-auth">
                Sudah punya akun? <a onclick="toggleForm('login')">Login disini</a>
            </div>
        </div>
    </div>

    <script>
        // --- ANIMATIONS ---
        // Create bubbles dynamically
        function createBubbles() {
            const container = document.getElementById('bubbles');
            for (let i = 0; i < 15; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');
                const size = Math.random() * 50 + 10;
                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${Math.random() * 100}%`;
                bubble.style.animationDuration = `${Math.random() * 5 + 5}s`;
                bubble.style.animationDelay = `${Math.random() * 5}s`;
                container.appendChild(bubble);
            }
        }
        createBubbles();

        function toggleForm(type) {
            const loginCard = document.getElementById('loginCard');
            const registerCard = document.getElementById('registerCard');
            const loginAlert = document.getElementById('loginAlert');
            const registerAlert = document.getElementById('registerAlert');
            const registerSuccess = document.getElementById('registerSuccess');

            // Hide alerts
            loginAlert.style.display = 'none';
            registerAlert.style.display = 'none';
            registerSuccess.style.display = 'none';

            if (type === 'register') {
                loginCard.classList.add('hidden');
                registerCard.classList.remove('hidden');
            } else {
                loginCard.classList.remove('hidden');
                registerCard.classList.add('hidden');
            }
        }

        function handleLogin(e) {
            e.preventDefault();
            const userIn = document.getElementById('loginUser').value;
            const passIn = document.getElementById('loginPass').value;
            const alertBox = document.getElementById('loginAlert');
            const btn = e.target.querySelector('button');

            // Loading state
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MEMUAT...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('action', 'login');
            formData.append('username', userIn);
            formData.append('password', passIn);

            fetch('auth_action.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Activate Cool Transition
                        const overlay = document.getElementById('loginOverlay');
                        overlay.classList.add('active');

                        setTimeout(() => {
                            window.location.href = 'admin.php';
                        }, 1500);
                    } else {
                        alertBox.style.display = 'flex';
                        alertBox.querySelector('span').innerText = data.message || 'Username atau password salah!';
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    alertBox.style.display = 'flex';
                    alertBox.querySelector('span').innerText = 'Terjadi kesalahan sistem!';
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }

        function handleRegister(e) {
            e.preventDefault();
            const userIn = document.getElementById('regUser').value;
            const passIn = document.getElementById('regPass').value;
            const passConfirmIn = document.getElementById('regPassConfirm').value;
            const alertBox = document.getElementById('registerAlert');
            const successBox = document.getElementById('registerSuccess');
            const btn = e.target.querySelector('button');

            // Validation
            if (passIn !== passConfirmIn) {
                alertBox.style.display = 'flex';
                alertBox.querySelector('span').innerText = 'Password tidak cocok!';
                successBox.style.display = 'none';
                return;
            }

            // Loading state
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MEMPROSES...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('action', 'register');
            formData.append('username', userIn);
            formData.append('password', passIn);

            fetch('auth_action.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;

                    if (data.success) {
                        alertBox.style.display = 'none';
                        successBox.style.display = 'flex';
                        document.getElementById('registerForm').reset();
                        setTimeout(() => {
                            toggleForm('login');
                        }, 2000);
                    } else {
                        alertBox.style.display = 'flex';
                        alertBox.querySelector('span').innerText = data.message || 'Gagal mendaftar!';
                        successBox.style.display = 'none';
                    }
                })
                .catch(error => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    alertBox.style.display = 'flex';
                    alertBox.querySelector('span').innerText = 'Terjadi kesalahan sistem!';
                    successBox.style.display = 'none';
                });
        }
    </script>
</body>

</html>