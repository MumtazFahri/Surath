<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WhatsApp Server Control Panel' ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Socket.IO Client -->
    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #fff8f0 0%, #ffedd5 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #5a4a42;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(251, 146, 60, 0.1);
            border: 1px solid #fed7aa;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 8px 25px rgba(251, 146, 60, 0.15);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(135deg, #fb923c 0%, #ea580c 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            border: none;
            font-weight: 600;
        }
        
        .status-badge {
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            margin: 5px;
            font-weight: 600;
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        
        .qr-container {
            text-align: center;
            padding: 40px;
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border-radius: 15px;
            margin: 20px 0;
            border: 2px dashed #fb923c;
        }
        
        .qr-container img {
            max-width: 300px;
            border: 3px solid #fb923c;
            padding: 15px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(251, 146, 60, 0.2);
        }
        
        .log-container {
            background: #1e1e1e;
            color: #00ff00;
            padding: 20px;
            border-radius: 10px;
            max-height: 400px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .log-container::-webkit-scrollbar {
            width: 10px;
        }
        
        .log-container::-webkit-scrollbar-track {
            background: #2d2d2d;
            border-radius: 10px;
        }
        
        .log-container::-webkit-scrollbar-thumb {
            background: #fb923c;
            border-radius: 10px;
        }
        
        .btn-custom {
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(251, 146, 60, 0.3);
        }
        
        /* Warna khusus untuk 3 button Control Panel */
        .btn-refresh {
            background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
            color: #7c2d12;
            border: 1px solid #fdba74;
        }
        
        .btn-refresh:hover {
            background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
            color: #7c2d12;
            border: 1px solid #fb923c;
        }
        
        .btn-restart {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        
        .btn-restart:hover {
            background: linear-gradient(135deg, #fde68a 0%, #fcd34d 100%);
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .btn-open-ui {
            background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
            color: #7c2d12;
            border: 1px solid #fdba74;
        }
        
        .btn-open-ui:hover {
            background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
            color: #7c2d12;
            border: 1px solid #fb923c;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #fb923c 0%, #ea580c 100%);
            border: none;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .spinner-border-custom {
            width: 20px;
            height: 20px;
            border-width: 2px;
        }
        
        .instruction-box {
            background: #fffbeb;
            border-left: 5px solid #f59e0b;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            color: #92400e;
        }
        
        .stats-card {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
            color: #7c2d12;
            border-radius: 15px;
            margin-bottom: 15px;
            border: 1px solid #fdba74;
        }
        
        .stats-card h3 {
            margin: 0;
            font-size: 24px;
            color: #ea580c;
        }
        
        .stats-card p {
            margin: 5px 0 0 0;
            opacity: 0.8;
        }
        
        .table-hover tbody tr:hover {
            background-color: #fff7ed;
        }
        
        .table thead th {
            background-color: #ffedd5;
            color: #7c2d12;
            border-bottom: 2px solid #fdba74;
        }
        
        .badge.bg-success {
            background-color: #10b981 !important;
        }
        
        .badge.bg-secondary {
            background-color: #9ca3af !important;
        }
        
        .alert-info {
            background-color: #dbeafe;
            border-color: #93c5fd;
            color: #1e40af;
        }
        
        .text-muted {
            color: #a8a29e !important;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: #7c2d12;
        }
        
        .text-white {
            color: #7c2d12 !important;
        }
        
        .text-white-50 {
            color: #a16207 !important;
        }
        
        /* Header khusus untuk judul utama */
        .main-header {
            background: linear-gradient(135deg, #fb923c 0%, #ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Modal styling */
        .modal-content {
            border: 1px solid #fdba74;
            border-radius: 15px;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
            border-bottom: 1px solid #fdba74;
            color: #7c2d12;
        }
        
        .form-control:focus {
            border-color: #fb923c;
            box-shadow: 0 0 0 0.25rem rgba(251, 146, 60, 0.25);
        }
        
        /* Loading Bar Styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }
        
        .loading-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .loading-title {
            color: #7c2d12;
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .loading-description {
            color: #666;
            margin-bottom: 25px;
        }
        
        .progress-container {
            width: 100%;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            height: 20px;
            margin: 20px 0;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #fb923c 0%, #ea580c 100%);
            border-radius: 10px;
            width: 0%;
            transition: width 0.3s ease;
            position: relative;
        }
        
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-image: linear-gradient(
                -45deg,
                rgba(255, 255, 255, 0.2) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, 0.2) 50%,
                rgba(255, 255, 255, 0.2) 75%,
                transparent 75%,
                transparent
            );
            z-index: 1;
            background-size: 50px 50px;
            animation: move 2s linear infinite;
            border-radius: 10px;
        }
        
        @keyframes move {
            0% { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }
        
        .progress-text {
            color: #7c2d12;
            font-weight: 600;
            margin-top: 10px;
            font-size: 18px;
        }
        
        .loading-steps {
            text-align: left;
            margin-top: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .loading-step {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #666;
            font-size: 14px;
        }
        
        .loading-step.active {
            color: #ea580c;
            font-weight: 600;
        }
        
        .loading-step.completed {
            color: #10b981;
        }
        
        .loading-step i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        /* WA Connection Progress Bar */
        .wa-connection-progress {
            margin-top: 20px;
            background: #f3f4f6;
            border-radius: 10px;
            padding: 15px;
            display: none;
        }
        
        .wa-progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .wa-progress-title {
            font-weight: 600;
            color: #7c2d12;
        }
        
        .wa-progress-percent {
            font-weight: 600;
            color: #ea580c;
        }
        
        .wa-progress-bar-container {
            height: 10px;
            background: #e5e7eb;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .wa-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #fb923c 0%, #ea580c 100%);
            width: 0%;
            transition: width 0.5s ease;
        }
        
        .wa-progress-steps {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 12px;
            color: #9ca3af;
        }
        
        .wa-step {
            text-align: center;
            flex: 1;
            position: relative;
        }
        
        .wa-step.active {
            color: #ea580c;
            font-weight: 600;
        }
        
        .wa-step.completed {
            color: #10b981;
        }
        
        /* Refresh Button Loading Animation */
        .refresh-loading {
            position: relative;
        }
        
        .refresh-loading .spinner-border {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -10px;
            margin-left: -10px;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <h3 class="loading-title" id="loadingTitle">Memeriksa Status Server</h3>
            <p class="loading-description" id="loadingDescription">Harap tunggu, sedang memeriksa status server WhatsApp...</p>
            
            <div class="progress-container">
                <div class="progress-bar" id="loadingBar"></div>
            </div>
            <div class="progress-text" id="loadingPercent">0%</div>
            
            <div class="loading-steps" id="loadingSteps">
                <!-- Steps akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>
    
    <div class="dashboard-container">
        <!-- Header -->
        <div class="text-center mb-4 fade-in">
            <h1 class="main-header mb-3">
                <i class="fab fa-whatsapp"></i> WhatsApp Server Control Panel
            </h1>
            <p class="text-white-50">Kelola WhatsApp Broadcast Server dari Browser</p>
        </div>
        
        <!-- Status Cards -->
        <div class="row mb-4 fade-in">
            <div class="col-md-6">
                <div class="stats-card">
                    <h3><i class="fas fa-server"></i> Server Status</h3>
                    <p id="server-status-text">Checking...</p>
                    <span id="server-status-badge" class="status-badge">
                        <i class="fas fa-circle-notch fa-spin"></i> Checking...
                    </span>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="stats-card">
                    <h3><i class="fab fa-whatsapp"></i> WhatsApp Status</h3>
                    <p id="wa-status-text">Not Connected</p>
                    <span id="wa-status-badge" class="status-badge">
                        <i class="fas fa-times-circle"></i> Disconnected
                    </span>
                </div>
            </div>
        </div>
        
        <!-- WhatsApp Connection Progress Bar -->
        <div class="wa-connection-progress fade-in" id="waConnectionProgress">
            <div class="wa-progress-header">
                <span class="wa-progress-title" id="waProgressTitle">Menghubungkan ke WhatsApp...</span>
                <span class="wa-progress-percent" id="waProgressPercent">0%</span>
            </div>
            <div class="wa-progress-bar-container">
                <div class="wa-progress-bar" id="waProgressBar"></div>
            </div>
            <div class="wa-progress-steps">
                <div class="wa-step" id="step1">
                    <div>1. Initializing</div>
                </div>
                <div class="wa-step" id="step2">
                    <div>2. Connecting</div>
                </div>
                <div class="wa-step" id="step3">
                    <div>3. Authenticating</div>
                </div>
                <div class="wa-step" id="step4">
                    <div>4. Ready</div>
                </div>
            </div>
        </div>
        
        <!-- Control Panel -->
        <div class="card fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-sliders-h"></i> Control Panel</h4>
            </div>
            <div class="card-body">
                <!-- Server Control Buttons -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <button class="btn btn-success btn-custom w-100" id="btn-start-server" onclick="startServer()" <?= $is_running ? 'disabled' : '' ?>>
                            <i class="fas fa-play-circle"></i> <span id="start-text">Start Server</span>
                        </button>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <button class="btn btn-danger btn-custom w-100" id="btn-stop-server" onclick="stopServer()" <?= !$is_running ? 'disabled' : '' ?>>
                            <i class="fas fa-stop-circle"></i> <span id="stop-text">Stop Server</span>
                        </button>
                    </div>
                </div>
                
                <hr>
                
                <!-- Other Control Buttons - SUDAH DISESUAIKAN -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-refresh btn-custom w-100" id="btn-refresh" onclick="checkStatusWithLoading()">
                            <i class="fas fa-sync-alt"></i> <span id="refresh-text">Refresh Status</span>
                        </button>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-restart btn-custom w-100" onclick="restartServer()">
                            <i class="fas fa-redo"></i> Restart Server
                        </button>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-open-ui btn-custom w-100" onclick="openServerInterface()">
                            <i class="fas fa-external-link-alt"></i> Open Server UI
                        </button>
                    </div>
                </div>
                
                <div class="instruction-box">
                    <strong><i class="fas fa-info-circle"></i> Cara Penggunaan:</strong>
                    <ol class="mb-0 mt-2">
                        <li><strong>Klik "Start Server"</strong> untuk menjalankan WhatsApp server</li>
                        <li>Tunggu beberapa detik hingga server siap</li>
                        <li>Jika belum scan QR, QR Code akan muncul otomatis di bawah</li>
                        <li>Scan QR Code dengan WhatsApp di HP Anda</li>
                        <li>Setelah terhubung, status akan berubah menjadi "Connected"</li>
                        <li>Pesan akan otomatis terkirim saat ada pengajuan baru</li>
                        <li>Klik "Stop Server" untuk menghentikan server</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- QR Code Section -->
        <div id="qr-section" class="card fade-in" style="display: none;">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-qrcode"></i> Scan QR Code</h4>
            </div>
            <div class="card-body">
                <div class="qr-container">
                    <img id="qr-code" src="" alt="QR Code" class="pulse">
                    <div class="mt-4">
                        <h5 class="text-muted"><i class="fas fa-mobile-alt"></i> Langkah-langkah:</h5>
                        <ol class="text-start text-muted" style="max-width: 400px; margin: 0 auto;">
                            <li>Buka WhatsApp di HP Anda</li>
                            <li>Tap menu <strong>⋮</strong> (titik tiga)</li>
                            <li>Pilih <strong>Linked Devices</strong></li>
                            <li>Tap <strong>Link a Device</strong></li>
                            <li>Scan QR Code di atas</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Management Nomor Penerima -->
        <div class="card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-users"></i> Management Nomor Penerima</h4>
                <button class="btn btn-light btn-sm" onclick="openAddModal()" style="background-color: #fb923c; color: white; border: none;">
                    <i class="fas fa-plus"></i> Tambah Penerima
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Info:</strong> Pesan WhatsApp akan otomatis dikirim ke SEMUA nomor yang statusnya <span class="badge bg-success">Aktif</span> saat ada pengajuan baru.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover" id="recipients-table">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Nama</th>
                                <th width="20%">Nomor WhatsApp</th>
                                <th width="20%">Jabatan</th>
                                <th width="15%">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recipients)): ?>
                                <?php foreach ($recipients as $index => $r): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($r->nama) ?></strong></td>
                                    <td>
                                        <i class="fab fa-whatsapp text-success"></i> 
                                        <?= htmlspecialchars($r->nomor) ?>
                                    </td>
                                    <td><?= htmlspecialchars($r->jabatan ?? '-') ?></td>
                                    <td>
                                        <?php if ($r->is_active): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times-circle"></i> Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" onclick="editRecipient(<?= $r->id ?>)" title="Edit" style="background-color: #f59e0b; border: none;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm <?= $r->is_active ? 'btn-secondary' : 'btn-success' ?>" 
                                                onclick="toggleRecipient(<?= $r->id ?>)" 
                                                title="<?= $r->is_active ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                                style="border: none;">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteRecipient(<?= $r->id ?>)" title="Hapus" style="border: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada data penerima</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Add/Edit Recipient -->
        <div class="modal fade" id="recipientModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Penerima</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="recipientForm">
                            <input type="hidden" id="recipient_id" name="id">
                            
                            <div class="mb-3">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="recipient_nama" name="nama" required placeholder="Contoh: Admin Utama">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="recipient_nomor" name="nomor" required placeholder="Contoh: 6282119509135">
                                <small class="text-muted">Format: 62xxx (tanpa +, tanpa spasi)</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="recipient_jabatan" name="jabatan" placeholder="Contoh: Administrator">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #9ca3af; border: none;">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="saveRecipient()" style="border: none;">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Logs -->
        <div class="card fade-in">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-terminal"></i> Server Logs</h4>
            </div>
            <div class="card-body">
                <div id="logs" class="log-container">
                    <span style="color: #00ff00;">Waiting for server connection...</span>
                </div>
                <button class="btn btn-sm btn-secondary mt-2" onclick="clearLogs()" style="background-color: #fb923c; border: none; color: white;">
                    <i class="fas fa-trash"></i> Clear Logs
                </button>
            </div>
        </div>
        
        <!-- Back Button -->
        <div class="text-center mt-4 mb-4">
            <a href="<?= base_url('list-surat-tugas') ?>" class="btn btn-light btn-custom" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fbbf24;">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Socket.IO connection
        const socket = io('http://localhost:3000');
        
        // Connection status
        socket.on('connect', () => {
            addLog('✅ Connected to WhatsApp server', 'success');
            updateServerStatus(true);
            checkStatus();
        });
        
        socket.on('disconnect', () => {
            addLog('❌ Disconnected from server', 'error');
            updateServerStatus(false);
        });
        
        // QR Code received
        socket.on('qr', (qrData) => {
            addLog('📱 QR Code received - Please scan with WhatsApp', 'info');
            document.getElementById('qr-section').style.display = 'block';
            document.getElementById('qr-code').src = qrData;
            
            // Update status
            updateWAStatus('qr_ready', 'Scan QR Code');
            
            // Update WA connection progress
            updateWAConnectionProgress(50, 'QR Code Generated', 2);
        });
        
        // WhatsApp ready
        socket.on('ready', () => {
            addLog('✅ WhatsApp client ready!', 'success');
            document.getElementById('qr-section').style.display = 'none';
            updateWAStatus('connected', 'Connected');
            
            // Update WA connection progress
            updateWAConnectionProgress(100, 'Connected to WhatsApp', 4);
            
            // Hide progress bar after 3 seconds
            setTimeout(() => {
                hideWAConnectionProgress();
            }, 3000);
        });
        
        // Status update
        socket.on('status', (data) => {
            addLog('Status: ' + data.message, 'info');
            
            if (data.status === 'connected') {
                updateWAStatus('connected', 'Connected');
                updateWAConnectionProgress(100, 'Connected to WhatsApp', 4);
                setTimeout(() => hideWAConnectionProgress(), 3000);
            } else if (data.status === 'disconnected') {
                updateWAStatus('disconnected', 'Disconnected');
                updateWAConnectionProgress(0, 'Disconnected', 1);
            } else if (data.status === 'qr_ready') {
                updateWAStatus('qr_ready', 'Scan QR Code');
                updateWAConnectionProgress(50, 'QR Code Generated', 2);
            }
        });
        
        // Loading progress
        socket.on('loading', (data) => {
            addLog('📥 Loading: ' + data.percent + '%', 'info');
            
            // Update WA connection progress
            const percent = data.percent;
            let step = 2; // Default to connecting step
            
            if (percent < 30) step = 2; // Connecting
            else if (percent < 60) step = 3; // Authenticating
            else if (percent < 90) step = 3; // Still authenticating
            else step = 4; // Almost ready
            
            updateWAConnectionProgress(percent, 'Loading: ' + percent + '%', step);
        });
        
        // Update server status display
        function updateServerStatus(isOnline) {
            const badge = document.getElementById('server-status-badge');
            const text = document.getElementById('server-status-text');
            
            if (isOnline) {
                badge.className = 'status-badge';
                badge.style.backgroundColor = '#d1fae5';
                badge.style.color = '#065f46';
                badge.style.borderColor = '#10b981';
                badge.innerHTML = '<i class="fas fa-check-circle"></i> Online';
                text.textContent = 'Server is running';
            } else {
                badge.className = 'status-badge';
                badge.style.backgroundColor = '#fee2e2';
                badge.style.color = '#991b1b';
                badge.style.borderColor = '#ef4444';
                badge.innerHTML = '<i class="fas fa-times-circle"></i> Offline';
                text.textContent = 'Server not responding';
            }
        }
        
        // Update WhatsApp status display
        function updateWAStatus(status, message) {
            const badge = document.getElementById('wa-status-badge');
            const text = document.getElementById('wa-status-text');
            
            text.textContent = message;
            
            switch(status) {
                case 'connected':
                    badge.className = 'status-badge';
                    badge.style.backgroundColor = '#d1fae5';
                    badge.style.color = '#065f46';
                    badge.style.borderColor = '#10b981';
                    badge.innerHTML = '<i class="fas fa-check-circle"></i> Connected';
                    break;
                case 'qr_ready':
                    badge.className = 'status-badge';
                    badge.style.backgroundColor = '#fef3c7';
                    badge.style.color = '#92400e';
                    badge.style.borderColor = '#f59e0b';
                    badge.innerHTML = '<i class="fas fa-qrcode"></i> Scan QR Code';
                    break;
                case 'disconnected':
                    badge.className = 'status-badge';
                    badge.style.backgroundColor = '#fee2e2';
                    badge.style.color = '#991b1b';
                    badge.style.borderColor = '#ef4444';
                    badge.innerHTML = '<i class="fas fa-times-circle"></i> Disconnected';
                    break;
                default:
                    badge.className = 'status-badge';
                    badge.style.backgroundColor = '#f3f4f6';
                    badge.style.color = '#4b5563';
                    badge.style.borderColor = '#9ca3af';
                    badge.innerHTML = '<i class="fas fa-question-circle"></i> Unknown';
            }
        }
        
        // Add log entry
        function addLog(message, type = 'info') {
            const logs = document.getElementById('logs');
            const timestamp = new Date().toLocaleTimeString();
            
            let color = '#00ff00';
            if (type === 'error') color = '#ff0000';
            if (type === 'warning') color = '#ffff00';
            if (type === 'success') color = '#00ff00';
            
            logs.innerHTML += `<span style="color: ${color};">[${timestamp}] ${message}</span>\n`;
            logs.scrollTop = logs.scrollHeight;
        }
        
        // Clear logs
        function clearLogs() {
            document.getElementById('logs').innerHTML = '<span style="color: #00ff00;">Logs cleared</span>\n';
        }
        
        // ========================================
        // LOADING BAR FUNCTIONS
        // ========================================
        
        // Show loading overlay
        function showLoading(title, description, steps = []) {
            document.getElementById('loadingTitle').textContent = title;
            document.getElementById('loadingDescription').textContent = description;
            document.getElementById('loadingBar').style.width = '0%';
            document.getElementById('loadingPercent').textContent = '0%';
            
            // Set loading steps
            const stepsContainer = document.getElementById('loadingSteps');
            stepsContainer.innerHTML = '';
            
            if (steps.length > 0) {
                steps.forEach(step => {
                    const stepEl = document.createElement('div');
                    stepEl.className = 'loading-step';
                    stepEl.id = `loading-step-${step.id}`;
                    stepEl.innerHTML = `
                        <i class="fas fa-circle"></i>
                        <span>${step.text}</span>
                    `;
                    stepsContainer.appendChild(stepEl);
                });
            }
            
            document.getElementById('loadingOverlay').style.display = 'flex';
        }
        
        // Update loading progress
        function updateLoadingProgress(percent, currentStep = null) {
            const bar = document.getElementById('loadingBar');
            const percentText = document.getElementById('loadingPercent');
            
            bar.style.width = percent + '%';
            percentText.textContent = percent + '%';
            
            // Update steps
            if (currentStep !== null) {
                // Reset all steps
                document.querySelectorAll('.loading-step').forEach(step => {
                    step.classList.remove('active', 'completed');
                    step.querySelector('i').className = 'fas fa-circle';
                });
                
                // Mark previous steps as completed
                for (let i = 1; i < currentStep; i++) {
                    const stepEl = document.getElementById(`loading-step-${i}`);
                    if (stepEl) {
                        stepEl.classList.add('completed');
                        stepEl.querySelector('i').className = 'fas fa-check-circle';
                    }
                }
                
                // Mark current step as active
                const currentStepEl = document.getElementById(`loading-step-${currentStep}`);
                if (currentStepEl) {
                    currentStepEl.classList.add('active');
                    currentStepEl.querySelector('i').className = 'fas fa-spinner fa-pulse';
                }
            }
        }
        
        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }
        
        // ========================================
        // WA CONNECTION PROGRESS BAR FUNCTIONS
        // ========================================
        
        // Show WA connection progress bar
        function showWAConnectionProgress() {
            document.getElementById('waConnectionProgress').style.display = 'block';
            updateWAConnectionProgress(0, 'Initializing connection...', 1);
        }
        
        // Update WA connection progress
        function updateWAConnectionProgress(percent, title, activeStep) {
            const bar = document.getElementById('waProgressBar');
            const percentText = document.getElementById('waProgressPercent');
            const titleText = document.getElementById('waProgressTitle');
            
            bar.style.width = percent + '%';
            percentText.textContent = percent + '%';
            titleText.textContent = title;
            
            // Update steps
            for (let i = 1; i <= 4; i++) {
                const stepEl = document.getElementById(`step${i}`);
                stepEl.classList.remove('active', 'completed');
                
                if (i < activeStep) {
                    stepEl.classList.add('completed');
                } else if (i === activeStep) {
                    stepEl.classList.add('active');
                }
            }
        }
        
        // Hide WA connection progress bar
        function hideWAConnectionProgress() {
            document.getElementById('waConnectionProgress').style.display = 'none';
        }
        
        // ========================================
        // REFRESH STATUS WITH LOADING BAR
        // ========================================
        
        // Check status with loading bar
        function checkStatusWithLoading() {
            const btn = document.getElementById('btn-refresh');
            const btnText = document.getElementById('refresh-text');
            const originalText = btnText.textContent;
            
            // Disable button and show loading on button
            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Checking...';
            
            // Show loading overlay
            showLoading('Memeriksa Status Server', 'Sedang memeriksa status server dan koneksi WhatsApp...', [
                { id: 1, text: 'Memeriksa status server...' },
                { id: 2, text: 'Memeriksa koneksi WhatsApp...' },
                { id: 3, text: 'Memuat data status...' },
                { id: 4, text: 'Menyelesaikan pemeriksaan...' }
            ]);
            
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 15;
                let step = 1;
                
                if (progress < 25) step = 1;
                else if (progress < 50) step = 2;
                else if (progress < 75) step = 3;
                else step = 4;
                
                updateLoadingProgress(progress, step);
                
                if (progress >= 85) {
                    clearInterval(progressInterval);
                }
            }, 200);
            
            addLog('🔍 Checking server status...', 'info');
            
            $.ajax({
                url: '<?= base_url("whatsapp/get_status") ?>',
                method: 'GET',
                dataType: 'json',
                timeout: 10000,
                success: function(response) {
                    clearInterval(progressInterval);
                    updateLoadingProgress(100, 4);
                    
                    setTimeout(() => {
                        hideLoading();
                        
                        // Re-enable button
                        btn.disabled = false;
                        btnText.textContent = originalText;
                        
                        if (response.online && response.is_running) {
                            updateServerStatus(true);
                            
                            // Update button states
                            document.getElementById('btn-start-server').disabled = true;
                            document.getElementById('btn-stop-server').disabled = false;
                            
                            if (response.ready) {
                                updateWAStatus('connected', 'WhatsApp Connected');
                                addLog('✅ WhatsApp is ready', 'success');
                                hideWAConnectionProgress();
                            } else {
                                updateWAStatus(response.whatsapp_status, 'Checking...');
                                addLog('⚠️ WhatsApp not ready yet', 'warning');
                                
                                // Show WA connection progress if not connected
                                if (response.whatsapp_status === 'qr_ready' || response.whatsapp_status === 'connecting') {
                                    showWAConnectionProgress();
                                }
                            }
                        } else {
                            updateServerStatus(false);
                            updateWAStatus('disconnected', 'Server Offline');
                            addLog('❌ Server is offline', 'error');
                            hideWAConnectionProgress();
                            
                            // Update button states
                            document.getElementById('btn-start-server').disabled = false;
                            document.getElementById('btn-stop-server').disabled = true;
                        }
                    }, 800);
                },
                error: function() {
                    clearInterval(progressInterval);
                    updateLoadingProgress(100, 4);
                    
                    setTimeout(() => {
                        hideLoading();
                        
                        // Re-enable button
                        btn.disabled = false;
                        btnText.textContent = originalText;
                        
                        updateServerStatus(false);
                        addLog('❌ Failed to check status', 'error');
                        hideWAConnectionProgress();
                        
                        // Update button states
                        document.getElementById('btn-start-server').disabled = false;
                        document.getElementById('btn-stop-server').disabled = true;
                        
                        showAlert('danger', 'Gagal memeriksa status server');
                    }, 800);
                }
            });
        }
        
        // Original check status function (for auto-refresh)
        function checkStatus() {
            addLog('🔍 Checking server status...', 'info');
            
            $.ajax({
                url: '<?= base_url("whatsapp/get_status") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.online && response.is_running) {
                        updateServerStatus(true);
                        
                        // Update button states
                        document.getElementById('btn-start-server').disabled = true;
                        document.getElementById('btn-stop-server').disabled = false;
                        
                        if (response.ready) {
                            updateWAStatus('connected', 'WhatsApp Connected');
                            addLog('✅ WhatsApp is ready', 'success');
                            hideWAConnectionProgress();
                        } else {
                            updateWAStatus(response.whatsapp_status, 'Checking...');
                            addLog('⚠️ WhatsApp not ready yet', 'warning');
                            
                            // Show WA connection progress if not connected
                            if (response.whatsapp_status === 'qr_ready' || response.whatsapp_status === 'connecting') {
                                showWAConnectionProgress();
                            }
                        }
                    } else {
                        updateServerStatus(false);
                        updateWAStatus('disconnected', 'Server Offline');
                        addLog('❌ Server is offline', 'error');
                        hideWAConnectionProgress();
                        
                        // Update button states
                        document.getElementById('btn-start-server').disabled = false;
                        document.getElementById('btn-stop-server').disabled = true;
                    }
                },
                error: function() {
                    updateServerStatus(false);
                    addLog('❌ Failed to check status', 'error');
                    hideWAConnectionProgress();
                    
                    // Update button states
                    document.getElementById('btn-start-server').disabled = false;
                    document.getElementById('btn-stop-server').disabled = true;
                }
            });
        }
        
        // Restart server with loading bar
        function restartServer() {
            if (!confirm('Restart WhatsApp server? Koneksi akan terputus sementara.')) {
                return;
            }
            
            addLog('🔄 Restarting server...', 'warning');
            
            // Show loading bar
            showLoading('Restarting Server', 'Server WhatsApp sedang direstart. Harap tunggu...', [
                { id: 1, text: 'Menghentikan server...' },
                { id: 2, text: 'Memulai ulang proses...' },
                { id: 3, text: 'Menghubungkan ke WhatsApp...' },
                { id: 4, text: 'Memuat ulang antarmuka...' }
            ]);
            
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 10;
                let step = 1;
                
                if (progress < 25) step = 1;
                else if (progress < 50) step = 2;
                else if (progress < 75) step = 3;
                else step = 4;
                
                updateLoadingProgress(progress, step);
                
                if (progress >= 90) {
                    clearInterval(progressInterval);
                }
            }, 500);
            
            $.ajax({
                url: '<?= base_url("whatsapp/restart") ?>',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    clearInterval(progressInterval);
                    updateLoadingProgress(100, 4);
                    
                    if (response.success) {
                        addLog('✅ Server restarting...', 'success');
                        
                        setTimeout(() => {
                            hideLoading();
                            showAlert('success', 'Server berhasil direstart!');
                            
                            // Check status after 5 seconds
                            setTimeout(() => {
                                checkStatus();
                                showWAConnectionProgress();
                            }, 2000);
                        }, 1000);
                    } else {
                        setTimeout(() => {
                            hideLoading();
                            showAlert('danger', response.error);
                        }, 1000);
                    }
                },
                error: function() {
                    clearInterval(progressInterval);
                    updateLoadingProgress(100, 4);
                    
                    setTimeout(() => {
                        hideLoading();
                        addLog('❌ Failed to restart server', 'error');
                        showAlert('danger', 'Gagal merestart server');
                    }, 1000);
                }
            });
        }
        
        // Open server UI in new tab
        function openServerInterface() {
            window.open('http://localhost:3000', '_blank');
        }
        
        // Start server with loading bar
        function startServer() {
            // Show loading bar
            showLoading('Starting WhatsApp Server', 'Server WhatsApp sedang dimulai. Proses ini mungkin memakan waktu beberapa detik...', [
                { id: 1, text: 'Initializing server process...' },
                { id: 2, text: 'Loading WhatsApp client...' },
                { id: 3, text: 'Establishing connection...' },
                { id: 4, text: 'Ready to connect...' }
            ]);
            
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 8;
                let step = 1;
                
                if (progress < 20) step = 1;
                else if (progress < 50) step = 2;
                else if (progress < 80) step = 3;
                else step = 4;
                
                updateLoadingProgress(progress, step);
                
                if (progress >= 85) {
                    clearInterval(progressInterval);
                }
            }, 500);
            
            const btn = document.getElementById('btn-start-server');
            const btnText = document.getElementById('start-text');
            const originalText = btnText.textContent;
            
            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Starting...';
            
            addLog('🚀 Starting WhatsApp server...', 'info');
            
            $.ajax({
                url: '<?= base_url("whatsapp/start_server") ?>',
                method: 'POST',
                dataType: 'json',
                timeout: 15000,
                success: function(response) {
                    clearInterval(progressInterval);
                    updateLoadingProgress(100, 4);
                    
                    if (response.success) {
                        addLog('✅ ' + response.message, 'success');
                        
                        setTimeout(() => {
                            hideLoading();
                            
                            // Update button states
                            btn.disabled = true;
                            document.getElementById('btn-stop-server').disabled = false;
                            
                            // Show WA connection progress
                            showWAConnectionProgress();
                            
                            // Show success alert
                            showAlert('success', response.message);
                            
                            // Check status after 3 seconds
                            setTimeout(() => {
                                checkStatus();
                            }, 3000);
                        }, 1000);
                    } else {
                        setTimeout(() => {
                            hideLoading();
                            btn.disabled = false;
                            addLog('❌ ' + response.message, 'error');
                            showAlert('danger', response.message);
                        }, 1000);
                    }
                    
                    btnText.textContent = originalText;
                },
                error: function(xhr, status, error) {
                    clearInterval(progressInterval);
                    updateLoadingProgress(100, 4);
                    
                    setTimeout(() => {
                        hideLoading();
                        btn.disabled = false;
                        btnText.textContent = originalText;
                        addLog('❌ Error: ' + error, 'error');
                        showAlert('danger', 'Gagal menjalankan server: ' + error);
                    }, 1000);
                }
            });
        }

        // Stop server with loading bar
        function stopServer() {
            if (!confirm('Stop WhatsApp server? Koneksi WhatsApp akan terputus.')) {
                return;
            }
            
            // Show loading bar
            showLoading('Stopping Server', 'Server WhatsApp sedang dihentikan. Harap tunggu...', [
                { id: 1, text: 'Disconnecting WhatsApp...' },
                { id: 2, text: 'Stopping processes...' },
                { id: 3, text: 'Cleaning up resources...' },
                { id: 4, text: 'Server stopped' }
            ]);
            
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 15;
                let step = 1;
                
                if (progress < 25) step = 1;
                else if (progress < 50) step = 2;
                else if (progress < 75) step = 3;
                else step = 4;
                
                updateLoadingProgress(progress, step);
                
                if (progress >= 90) {
                    clearInterval(progressInterval);
                }
            }, 300);
            
            const btn = document.getElementById('btn-stop-server');
            const btnText = document.getElementById('stop-text');
            const originalText = btnText.textContent;
            
            btn.disabled = true;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Stopping...';
            
            addLog('🛑 Stopping WhatsApp server...', 'warning');
            
            $.ajax({
                url: '<?= base_url("whatsapp/stop_server") ?>',
                method: 'POST',
                dataType: 'json',
                timeout: 10000,
                success: function(response) {
                    clearInterval(progressInterval);
                    updateLoadingProgress(100, 4);
                    
                    if (response.success) {
                        addLog('✅ ' + response.message, 'success');
                        
                        setTimeout(() => {
                            hideLoading();
                            
                            // Update button states
                            btn.disabled = true;
                            document.getElementById('btn-start-server').disabled = false;
                            
                            // Update status
                            updateServerStatus(false);
                            updateWAStatus('disconnected', 'Server Stopped');
                            hideWAConnectionProgress();
                            
                            // Hide QR section
                            document.getElementById('qr-section').style.display = 'none';
                            
                            showAlert('success', response.message);
                        }, 1000);
                    } else {
                        setTimeout(() => {
                            hideLoading();
                            btn.disabled = false;
                            addLog('❌ ' + response.message, 'error');
                            showAlert('danger', response.message);
                        }, 1000);
                    }
                    
                    btnText.textContent = originalText;
                },
                error: function(xhr, status, error) {
                    clearInterval(progressInterval);
                    updateLoadingProgress(100, 4);
                    
                    setTimeout(() => {
                        hideLoading();
                        btn.disabled = false;
                        btnText.textContent = originalText;
                        addLog('❌ Error: ' + error, 'error');
                        showAlert('danger', 'Gagal menghentikan server: ' + error);
                    }, 1000);
                }
            });
        }

        // Show alert notification
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; background-color: ${type === 'success' ? '#d1fae5' : '#fee2e2'}; color: ${type === 'success' ? '#065f46' : '#991b1b'}; border-color: ${type === 'success' ? '#10b981' : '#ef4444'}">
                    <strong>${type === 'success' ? '✅ Berhasil!' : '❌ Error!'}</strong><br>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('body').append(alertHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut(() => {
                    $('.alert').remove();
                });
            }, 5000);
        }

        // Auto-check status on load
        $(document).ready(function() {
            checkStatus();
            
            // Auto-refresh every 30 seconds
            setInterval(checkStatus, 30000);
            
            // Show WA connection progress if server is running but WA not connected
            setTimeout(() => {
                if (document.getElementById('btn-stop-server').disabled === false && 
                    document.getElementById('wa-status-badge').innerHTML.includes('Disconnected')) {
                    showWAConnectionProgress();
                }
            }, 2000);
        });
        
        // ========================================
        // RECIPIENT MANAGEMENT FUNCTIONS
        // ========================================

        // Open add modal
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Penerima';
            document.getElementById('recipientForm').reset();
            document.getElementById('recipient_id').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('recipientModal'));
            modal.show();
        }

        // Edit recipient
        function editRecipient(id) {
            // Get data from table row
            const row = event.target.closest('tr');
            const nama = row.cells[1].textContent.trim();
            const nomor = row.cells[2].textContent.trim().replace(/\D/g, ''); // Remove non-digits
            const jabatan = row.cells[3].textContent.trim();
            
            // Fill form
            document.getElementById('modalTitle').textContent = 'Edit Penerima';
            document.getElementById('recipient_id').value = id;
            document.getElementById('recipient_nama').value = nama;
            document.getElementById('recipient_nomor').value = nomor;
            document.getElementById('recipient_jabatan').value = jabatan === '-' ? '' : jabatan;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('recipientModal'));
            modal.show();
        }

        // Save recipient (add or update)
        function saveRecipient() {
            const form = document.getElementById('recipientForm');
            const formData = new FormData(form);
            
            // Validasi
            const nama = formData.get('nama').trim();
            const nomor = formData.get('nomor').trim();
            
            if (!nama || !nomor) {
                showAlert('danger', 'Nama dan nomor harus diisi');
                return;
            }
            
            // Clean nomor (remove non-digits)
            const nomorClean = nomor.replace(/\D/g, '');
            if (nomorClean.length < 10) {
                showAlert('danger', 'Nomor WhatsApp tidak valid');
                return;
            }
            
            formData.set('nomor', nomorClean);
            
            const id = formData.get('id');
            const url = id ? '<?= base_url("whatsapp/update_recipient") ?>' : '<?= base_url("whatsapp/add_recipient") ?>';
            
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('recipientModal')).hide();
                        
                        // Reload page after 1 second
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
                }
            });
        }

        // Toggle active status
        function toggleRecipient(id) {
            if (!confirm('Ubah status penerima ini?')) {
                return;
            }
            
            $.ajax({
                url: '<?= base_url("whatsapp/toggle_recipient") ?>',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        
                        // Reload page after 1 second
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'Terjadi kesalahan');
                }
            });
        }

        // Delete recipient
        function deleteRecipient(id) {
            if (!confirm('Hapus penerima ini? Data tidak bisa dikembalikan!')) {
                return;
            }
            
            $.ajax({
                url: '<?= base_url("whatsapp/delete_recipient") ?>',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        
                        // Reload page after 1 second
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'Terjadi kesalahan saat menghapus');
                }
            });
        }
    </script>
</body>
</html>