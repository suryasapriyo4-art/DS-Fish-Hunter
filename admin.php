<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
$adminUsername = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚡ Admin Dashboard - DS Fish Hunter</title>
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
            --glass-bg: rgba(20, 20, 20, 0.8);
            --gold: #daa520;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #020202;
            color: #fff;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(10, 5, 5, 0.9);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            color: #aaa;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(220, 20, 60, 0.1);
            color: #ff4d4d;
        }

        .logout-btn {
            margin-top: auto;
            background: transparent;
            border: 1px solid #ff4d4d;
            color: #ff4d4d;
            padding: 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .logout-btn:hover {
            background: #ff4d4d;
            color: white;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 3rem;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stat-card h3 {
            color: #aaa;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .recent-activity {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 20px;
            padding: 2rem;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .status.success {
            background: rgba(0, 255, 0, 0.1);
            color: #00ff00;
        }

        .status.warning {
            background: rgba(255, 165, 0, 0.1);
            color: #ffa500;
        }

        .status.danger {
            background: rgba(255, 0, 0, 0.1);
            color: #ff4d4d;
        }

        /* Table Styles */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            color: #ddd;
        }

        .order-table th,
        .order-table td {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .order-table th {
            color: #aaa;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s;
            margin-right: 0.5rem;
        }

        .btn-view {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .btn-approve {
            background: rgba(0, 255, 0, 0.1);
            color: #00ff00;
            border: 1px solid rgba(0, 255, 0, 0.2);
        }

        .btn-reject {
            background: rgba(255, 0, 0, 0.1);
            color: #ff4d4d;
            border: 1px solid rgba(255, 0, 0, 0.2);
        }

        .btn-view:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-approve:hover {
            background: rgba(0, 255, 0, 0.2);
        }

        .btn-reject:hover {
            background: rgba(255, 0, 0, 0.2);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: #111;
            padding: 2rem;
            border-radius: 20px;
            width: 500px;
            border: 1px solid #333;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
            padding-bottom: 0.5rem;
        }

        .detail-label {
            color: #aaa;
        }

        .detail-value {
            font-weight: 600;
            text-align: right;
        }

        /* --- MODERN COOL PRELOADER --- */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, #1a0505 0%, #020202 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1), visibility 1s;
            overflow: hidden;
        }

        .loader-content {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .scanner-ring {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 2px solid rgba(255, 15, 15, 0.1);
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .scanner-ring::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top: 2px solid #ff0f0f;
            border-bottom: 2px solid #ff0f0f;
            animation: spin-cool 2s linear infinite;
        }

        .scanner-line {
            position: absolute;
            width: 140%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ff0f0f, transparent);
            animation: scan 3s ease-in-out infinite;
            filter: blur(2px) drop-shadow(0 0 10px #ff0f0f);
            opacity: 0.5;
        }

        .core-icon {
            font-size: 4rem;
            color: #ff0f0f;
            filter: drop-shadow(0 0 20px rgba(255, 15, 15, 0.8));
            animation: core-pulse 2s ease-in-out infinite;
            z-index: 2;
        }

        .loading-text-container {
            margin-top: 3rem;
            text-align: center;
        }

        .loading-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 8px;
            text-transform: uppercase;
            background: linear-gradient(90deg, #fff, #ff0f0f, #fff);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine-text 3s linear infinite;
        }

        .loading-subtitle {
            font-size: 0.7rem;
            letter-spacing: 4px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 0.5rem;
            text-transform: uppercase;
        }

        /* Animations */
        @keyframes spin-cool {
            0% {
                transform: rotate(0deg) scale(1);
            }

            50% {
                transform: rotate(180deg) scale(1.1);
            }

            100% {
                transform: rotate(360deg) scale(1);
            }
        }

        @keyframes scan {

            0%,
            100% {
                top: -20%;
                transform: rotate(0deg);
            }

            50% {
                top: 120%;
                transform: rotate(5deg);
            }
        }

        @keyframes core-pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        @keyframes shine-text {
            to {
                background-position: 200% center;
            }
        }

        .data-stream {
            position: absolute;
            font-family: monospace;
            font-size: 10px;
            color: rgba(255, 15, 15, 0.2);
            pointer-events: none;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="loader-content">
            <div class="scanner-ring">
                <div class="scanner-line"></div>
                <i class="fas fa-user-shield core-icon"></i>
            </div>
            <div class="loading-text-container">
                <div class="loading-title">SuperAdmin</div>
                <div class="loading-subtitle">Initializing Secure Protocol...</div>
            </div>
        </div>

        <!-- Background Data Streams -->
        <div class="data-stream" style="top: 10%; left: 5%;">AUTH_SEQ: 0x9F2E</div>
        <div class="data-stream" style="top: 20%; right: 10%;">ENCRYPT_MODE: RSA_4096</div>
        <div class="data-stream" style="bottom: 15%; left: 15%;">SYSTEM_READY: TRUE</div>
        <div class="data-stream" style="bottom: 25%; right: 5%;">ACCESS_LEVEL: SUPERADMIN</div>
    </div>

    <div class="sidebar">
        <div class="admin-profile">
            <div class="admin-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <h3 style="font-weight: 700;">
                    <?php echo htmlspecialchars($adminUsername); ?>
                </h3>
                <span style="font-size: 0.8rem; color: #ff6b6b;">Super Admin</span>
            </div>
        </div>

        <nav style="display: flex; flex-direction: column; gap: 0.5rem;">
            <a href="#" class="menu-item active">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-fish"></i>
                Kelola Ikan
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                Pesanan
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-users"></i>
                Pelanggan
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                Pengaturan
            </a>
        </nav>

        <button class="logout-btn" onclick="logout()">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1
                    style="font-size: 2.5rem; font-weight: 800; letter-spacing: -1px; background: linear-gradient(to right, #fff, #888); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Ringkasan Eksekutif</h1>
                <p style="color: #666; font-size: 1rem; margin-top: 0.2rem;">Otoritas Terpusat DS Fish Hunter • Sesi:
                    <span style="color: #ff4d4d; font-weight: 600;">Secure</span></p>
            </div>
            <button
                style="padding: 1rem 2rem; background: var(--primary-gradient); border: none; border-radius: 12px; color: white; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 20px rgba(255, 15, 15, 0.2); transition: all 0.3s;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 30px rgba(255, 15, 15, 0.4)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(255, 15, 15, 0.2)';">
                <i class="fas fa-plus-circle"></i> Tambah Koleksi Baru
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card"
                style="border-left: 4px solid #ff0f0f; background: linear-gradient(180deg, rgba(255, 15, 15, 0.05) 0%, rgba(20, 20, 20, 0.8) 100%);">
                <div
                    style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                    <h3 style="color: #ff4d4d; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Total
                        Koleksi</h3>
                    <i class="fas fa-fish" style="font-size: 1.5rem; color: #ff0f0f; opacity: 0.5;"></i>
                </div>
                <div class="value" style="font-size: 3rem; line-height: 1;">128</div>
                <div style="margin-top: 1rem;">
                    <span class="status success"
                        style="background: rgba(0, 255, 0, 0.1); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.8rem;">
                        <i class="fas fa-chart-line"></i> +12% bulan ini
                    </span>
                </div>
            </div>

            <div class="stat-card"
                style="border-left: 4px solid #00c6ff; background: linear-gradient(180deg, rgba(0, 198, 255, 0.05) 0%, rgba(20, 20, 20, 0.8) 100%);">
                <div
                    style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                    <h3 style="color: #00c6ff; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Total
                        Pesanan</h3>
                    <i class="fas fa-shopping-cart" style="font-size: 1.5rem; color: #00c6ff; opacity: 0.5;"></i>
                </div>
                <div class="value" style="font-size: 3rem; line-height: 1;">45</div>
                <div style="margin-top: 1rem;">
                    <span class="status success"
                        style="background: rgba(0, 255, 0, 0.1); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.8rem;">
                        <i class="fas fa-bell"></i> 8 Pesanan baru
                    </span>
                </div>
            </div>

            <div class="stat-card"
                style="border-left: 4px solid var(--gold); background: linear-gradient(180deg, rgba(218, 165, 32, 0.05) 0%, rgba(20, 20, 20, 0.8) 100%);">
                <div
                    style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                    <h3 style="color: var(--gold); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                        Pendapatan</h3>
                    <i class="fas fa-wallet" style="font-size: 1.5rem; color: var(--gold); opacity: 0.5;"></i>
                </div>
                <div class="value" style="color: var(--gold); font-size: 2.5rem; line-height: 1.2;">Rp <span
                        style="font-weight: 800;">25.4JT</span></div>
                <div style="margin-top: 1rem; color: #666; font-size: 0.8rem;">
                    Total Akumulasi Transaksi
                </div>
            </div>
        </div>

        <div class="recent-activity">
            <h2 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-list-ul"></i> Daftar Pesanan
            </h2>

            <div style="overflow-x: auto;">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Jml</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="orderTableBody">
                        <!-- Data will be populated by JS -->
                    </tbody>
                </table>
            </div>

            <div id="emptyState" style="text-align: center; padding: 2rem; display: none;">
                <p style="color: #666;">Belum ada pesanan masuk.</p>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 style="color: white; margin: 0;">Detail Transaksi</h2>
                <button onclick="closeModal()"
                    style="background: none; border: none; color: #666; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>

            <div id="modalDetails">
                <!-- Details injected here -->
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button id="modalApproveBtn" class="action-btn btn-approve" style="flex: 1; padding: 1rem;">Setujui
                    Pesanan</button>
                <button id="modalRejectBtn" class="action-btn btn-reject" style="flex: 1; padding: 1rem;">Tolak
                    Pesanan</button>
            </div>
        </div>
    </div>

    <script>
        // --- MOCK DATA SEEDING (Since we don't have a real backend) ---
        function seedMockData() {
            if (!localStorage.getItem('adminOrders')) {
                const mockOrders = [
                    {
                        id: 'TRX-001',
                        customer: 'Budi Santoso',
                        item: 'Channa Barca (High Quality)',
                        quantity: 2,
                        price: 'Rp 30.000.000',
                        date: '2025-01-28',
                        status: 'Approved',
                        address: 'Jl. Merdeka No. 12, Jakarta Selatan',
                        phone: '0812-3456-7890'
                    },
                    {
                        id: 'TRX-002',
                        customer: 'Siti Aminah',
                        item: 'Arwana Super Red',
                        quantity: 1,
                        price: 'Rp 8.500.000',
                        date: '2025-01-29',
                        status: 'Pending',
                        address: 'Komp. Gading Serpong Blok A1, Tangerang',
                        phone: '0819-8765-4321'
                    },
                    {
                        id: 'TRX-003',
                        customer: 'Doni Pratama',
                        item: 'P-Bass Monoculus',
                        quantity: 5,
                        price: 'Rp 6.000.000',
                        date: '2025-01-29',
                        status: 'Rejected',
                        address: 'Jl. Ahmad Yani No. 45, Surabaya',
                        phone: '0857-1122-3344'
                    },
                    {
                        id: 'TRX-004',
                        customer: 'Reza Rahardian',
                        item: 'Arapaima Gigas Baby',
                        quantity: 3,
                        price: 'Rp 10.500.000',
                        date: '2025-01-30',
                        status: 'Pending',
                        address: 'Villa Dago Pakar, Bandung',
                        phone: '0813-9988-7766'
                    }
                ];
                localStorage.setItem('adminOrders', JSON.stringify(mockOrders));
            }
        }

        // --- CORE FUNCTIONS ---
        let orders = [];

        function loadOrders() {
            orders = JSON.parse(localStorage.getItem('adminOrders')) || [];
            renderTable();
            updateStats();
        }

        function renderTable() {
            const tbody = document.getElementById('orderTableBody');
            const emptyState = document.getElementById('emptyState');
            tbody.innerHTML = '';

            if (orders.length === 0) {
                emptyState.style.display = 'block';
                return;
            }
            emptyState.style.display = 'none';

            orders.forEach(order => {
                const tr = document.createElement('tr');
                let statusClass = '';
                if (order.status === 'Approved') statusClass = 'success';
                else if (order.status === 'Pending') statusClass = 'warning';
                else statusClass = 'danger';

                tr.innerHTML = `
                    <td><span style="color: #aaa; font-family: monospace;">${order.id}</span></td>
                    <td>
                        <div style="font-weight: 600;">${order.customer}</div>
                        <div style="font-size: 0.8rem; color: #666;">${order.date}</div>
                    </td>
                    <td>${order.item}</td>
                    <td style="text-align: center;">${order.quantity}</td>
                    <td style="color: var(--gold); font-weight: 600;">${order.price}</td>
                    <td><span class="status ${statusClass}">${order.status}</span></td>
                    <td>
                        <div style="display: flex;">
                            <button onclick="viewDetail('${order.id}')" class="action-btn btn-view" title="Lihat Detail"><i class="fas fa-eye"></i></button>
                            ${order.status === 'Pending' ? `
                                <button onclick="updateStatus('${order.id}', 'Approved')" class="action-btn btn-approve" title="Setujui"><i class="fas fa-check"></i></button>
                                <button onclick="updateStatus('${order.id}', 'Rejected')" class="action-btn btn-reject" title="Tolak"><i class="fas fa-times"></i></button>
                            ` : ''}
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function updateStatus(id, newStatus) {
            const index = orders.findIndex(o => o.id === id);
            if (index !== -1) {
                if (confirm(`Apakah Anda yakin ingin mengubah status menjadi ${newStatus}?`)) {
                    orders[index].status = newStatus;
                    saveOrders();
                    loadOrders();
                    closeModal(); // Close modal if open
                }
            }
        }

        function saveOrders() {
            localStorage.setItem('adminOrders', JSON.stringify(orders));
        }

        function updateStats() {
            // Update stats logic here if needed based on real data
            const total = orders.length;
            const pending = orders.filter(o => o.status === 'Pending').length;
        }

        // --- MODAL LOGIC ---
        let currentModalId = null;

        function viewDetail(id) {
            const order = orders.find(o => o.id === id);
            if (!order) return;

            currentModalId = id;
            const modalDetails = document.getElementById('modalDetails');
            const modalApproveBtn = document.getElementById('modalApproveBtn');
            const modalRejectBtn = document.getElementById('modalRejectBtn');

            // Populate Modal
            modalDetails.innerHTML = `
                <div class="detail-row">
                    <span class="detail-label">ID Transaksi</span>
                    <span class="detail-value" style="color: var(--gold); font-family: monospace;">${order.id}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: ${order.status === 'Approved' ? '#0f0' : (order.status === 'Rejected' ? '#f00' : 'orange')}">${order.status}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Pelanggan</span>
                    <span class="detail-value">${order.customer}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nomor WhatsApp</span>
                    <span class="detail-value">${order.phone}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Alamat Pengiriman</span>
                    <span class="detail-value" style="max-width: 200px;">${order.address}</span>
                </div>
                <div style="margin: 1.5rem 0; border-top: 1px solid #333; padding-top: 1rem;">
                    <h4 style="margin-bottom: 1rem; color: #aaa;">Item Detail</h4>
                    <div class="detail-row">
                        <span class="detail-label">${order.item}</span>
                        <span class="detail-value">x ${order.quantity}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Harga</span>
                        <span class="detail-value" style="color: var(--gold);">${order.price}</span>
                    </div>
                </div>
            `;

            // Adjust Buttons
            if (order.status !== 'Pending') {
                modalApproveBtn.style.display = 'none';
                modalRejectBtn.style.display = 'none';
            } else {
                modalApproveBtn.style.display = 'block';
                modalRejectBtn.style.display = 'block';
                modalApproveBtn.onclick = () => updateStatus(id, 'Approved');
                modalRejectBtn.onclick = () => updateStatus(id, 'Rejected');
            }

            document.getElementById('detailModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('detailModal').style.display = 'none';
            currentModalId = null;
        }

        // Close modal on outside click
        window.onclick = function (event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // --- INITIALIZATION ---
        window.addEventListener('load', () => {
            // Remove Preloader
            setTimeout(() => {
                const preloader = document.getElementById('preloader');
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }, 1000); // 1.5s delay for dramatic effect

            // No auth check needed here, PHP handled it
            seedMockData(); // Initialize mock data if empty
            loadOrders(); // Render UI
        });

        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>

</html>