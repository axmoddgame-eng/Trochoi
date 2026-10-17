<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siêu Trí Nhớ - Trò Chơi Thẻ Bài Trí Tuệ</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="tc.css">
</head>
<body>
    <!-- Header Navbar -->
    <header class="navbar">
        <div class="nav-container">
            <a href="tc.php" class="logo">
                <span class="material-symbols-outlined logo-icon">style</span>
                <span class="logo-text">Siêu Trí Nhớ</span>
            </a>
            <nav class="nav-menu">
                <a href="tc.php" class="nav-link active">Trang Chủ</a>
                <a href="bt.php" class="nav-link">Bộ Thẻ Chủ Đề</a>
                <a href="bx.php" class="nav-link">Bảng Xếp Hạng</a>
            </nav>
            <div class="nav-right">
                <button id="dark-toggle" class="icon-btn" title="Chuyển chế độ ban đêm">
                    <span class="material-symbols-outlined">dark_mode</span>
                </button>
                <div id="user-auth-area">
                    <!-- Dynamic JS content: Login button or User stars -->
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <span class="badge">🌟 Game Trí Tuệ Hàng Đầu</span>
                <h1 class="hero-title">Rèn Luyện Bộ Não <br><span class="highlight">Bằng Game Thẻ Bài</span></h1>
                <p class="hero-desc">Khám phá các chòm sao bí ẩn, nâng cao khả năng tập trung và chinh phục đỉnh cao bảng xếp hạng ngay hôm nay!</p>
                <div class="hero-actions">
                    <button class="btn btn-primary btn-lg" onclick="startPlay()">
                        <span class="material-symbols-outlined">play_arrow</span> Bắt Đầu Ngay
                    </button>
                    <button class="btn btn-secondary btn-lg" onclick="openModal('guide-modal')">
                        <span class="material-symbols-outlined">help</span> Hướng Dẫn Cách Chơi
                    </button>
                </div>
            </div>
            <div class="hero-preview">
                <div class="card-float card-1">
                    <div class="card-inner">
                        <div class="card-front"><span class="symbol">♈</span><span class="label">Bạch Dương</span></div>
                    </div>
                </div>
                <div class="card-float card-2">
                    <div class="card-inner">
                        <div class="card-front"><span class="symbol">🌌</span><span class="label">Ngân Hà</span></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mode & Record Section -->
        <section class="mode-section">
            <h2 class="section-title">Chọn Mức Độ Thử Thách</h2>
            <div class="mode-grid">
                <button class="mode-card active" data-mode="4x4" onclick="selectMode('4x4')">
                    <div class="mode-icon"><span class="material-symbols-outlined">grid_view</span></div>
                    <div class="mode-info">
                        <h3>Dễ (4x4)</h3>
                        <p>16 Thẻ bài • Thích hợp khởi động</p>
                    </div>
                </button>
                <button class="mode-card" data-mode="6x4" onclick="selectMode('6x4')">
                    <div class="mode-icon"><span class="material-symbols-outlined">grid_on</span></div>
                    <div class="mode-info">
                        <h3>Vừa (6x4)</h3>
                        <p>24 Thẻ bài • Thử thách trí nhớ</p>
                    </div>
                </button>
                <button class="mode-card" data-mode="13x6" onclick="selectMode('13x6')">
                    <div class="mode-icon"><span class="material-symbols-outlined">apps</span></div>
                    <div class="mode-info">
                        <h3>Khó (13x6)</h3>
                        <p>78 Thẻ bài • Đỉnh cao siêu trí nhớ</p>
                    </div>
                </button>
            </div>

            <!-- Personal Best Record Card (MySQL Connected) -->
            <div class="record-box">
                <div class="record-header">
                    <div class="record-title">
                        <span class="material-symbols-outlined trophy-icon">trophy</span>
                        <div>
                            <h3>Kỷ Lục Của Bạn (<span id="current-mode-label">4x4</span>)</h3>
                            <p class="sub-text">Cập nhật tự động từ MySQL sau mỗi ván chơi</p>
                        </div>
                    </div>
                    <button class="btn btn-outline btn-sm" onclick="openHistoryModal()">
                        <span class="material-symbols-outlined">history</span> Xem Lịch Sử
                    </button>
                </div>
                <div class="record-stats">
                    <div class="stat-item">
                        <span class="stat-label">Điểm Cao Nhất</span>
                        <span class="stat-value" id="record-score">0</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Thời Gian</span>
                        <span class="stat-value" id="record-time">0s</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Tỉ Lệ Chuẩn Xác</span>
                        <span class="stat-value" id="record-acc">0%</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal: Hướng Dẫn Cách Chơi -->
    <div id="guide-modal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">menu_book</span> Hướng Dẫn Cách Chơi</h2>
                <button class="modal-close" onclick="closeModal('guide-modal')">&times;</button>
            </div>
            <div class="modal-body guide-body">
                <div class="guide-step">
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <h4>Xem Trước & Ghi Nhớ (3 Giây)</h4>
                        <p>Khi bắt đầu màn chơi, tất cả thẻ bài sẽ lật ngửa trong 3 giây để bạn nhanh trí ghi nhớ vị trí các biểu tượng.</p>
                        <div class="step-img"><span class="symbol">👁️ 3 Giây Xem Trước</span></div>
                    </div>
                </div>
                <div class="guide-step">
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <h4>Lật Mở Cặp Thẻ Giống Nhau</h4>
                        <p>Nhấp vào 2 thẻ bài để lật. Nếu đúng cặp hình giống nhau, chúng sẽ biến mất/sáng lên. Nếu sai, thẻ sẽ tự động úp lại.</p>
                        <div class="step-img"><span class="symbol">🃏 ♈ = ♈</span></div>
                    </div>
                </div>
                <div class="guide-step">
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <h4>Hoàn Thành & Thưởng Sao</h4>
                        <p>Ghép cặp hết tất cả các thẻ để chiến thắng. Thưởng sao tích lũy dựa vào thời gian và tỉ lệ lật chuẩn xác (100 - 1500 ⭐)!</p>
                        <div class="step-img"><span class="symbol">⭐ Thưởng Sao Tích Lũy</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Lịch Sử Thi Đấu -->
    <div id="history-modal" class="modal-overlay">
        <div class="modal-container modal-lg">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">history</span> Lịch Sử Thi Đấu Cá Nhân</h2>
                <button class="modal-close" onclick="closeModal('history-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Bộ Thẻ</th>
                                <th>Mức Độ</th>
                                <th>Thời Gian</th>
                                <th>Độ Chuẩn Xác</th>
                                <th>Điểm Số</th>
                                <th>Tình Trạng</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Đăng Nhập / Đăng Ký -->
    <div id="auth-modal" class="modal-overlay">
        <div class="modal-container modal-sm">
            <div class="modal-header">
                <div class="auth-tabs">
                    <button class="auth-tab active" id="tab-login-btn" onclick="switchAuthTab('login')">Đăng Nhập</button>
                    <button class="auth-tab" id="tab-register-btn" onclick="switchAuthTab('register')">Đăng Ký</button>
                </div>
                <button class="modal-close" onclick="closeModal('auth-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="login-form" onsubmit="handleAuth(event, 'login')">
                    <div class="form-group">
                        <label>Tên tài khoản</label>
                        <input type="text" name="username" class="form-input" placeholder="Nhập tên tài khoản..." required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" class="form-input" placeholder="Nhập mật khẩu..." required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Đăng Nhập Ngay</button>
                </form>

                <form id="register-form" style="display: none;" onsubmit="handleAuth(event, 'register')">
                    <div class="form-group">
                        <label>Họ và Tên</label>
                        <input type="text" name="fullname" class="form-input" placeholder="Nhập họ và tên..." required>
                    </div>
                    <div class="form-group">
                        <label>Tên tài khoản</label>
                        <input type="text" name="username" class="form-input" placeholder="Tạo tên tài khoản..." required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" class="form-input" placeholder="Tạo mật khẩu..." required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Đăng Ký Ngay (+500 ⭐)</button>
                </form>
            </div>
        </div>
    </div>

    <script src="tc.js"></script>
</body>
</html>
