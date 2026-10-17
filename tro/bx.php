<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Xếp Hạng - Siêu Trí Nhớ</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="bx.css">
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="nav-container">
            <a href="tc.php" class="logo">
                <span class="material-symbols-outlined logo-icon">style</span>
                <span class="logo-text">Siêu Trí Nhớ</span>
            </a>
            <nav class="nav-menu">
                <a href="tc.php" class="nav-link">Trang Chủ</a>
                <a href="bt.php" class="nav-link">Bộ Thẻ Chủ Đề</a>
                <a href="bx.php" class="nav-link active">Bảng Xếp Hạng</a>
            </nav>
            <div class="nav-right">
                <button id="dark-toggle" class="icon-btn" title="Chuyển chế độ ban đêm">
                    <span class="material-symbols-outlined">dark_mode</span>
                </button>
            </div>
        </div>
    </header>

    <main class="main-container">
        <div class="page-header">
            <h1 class="page-title">🏆 Bảng Xếp Hạng Cao Thủ</h1>
            <p class="page-desc">Vinh danh các siêu trí nhớ xuất sắc nhất hệ thống!</p>
        </div>

        <!-- Mode Filters -->
        <div class="mode-tabs">
            <button class="mode-tab active" onclick="loadLeaderboard('4x4', this)">
                <span class="material-symbols-outlined">grid_view</span> Dễ (4x4)
            </button>
            <button class="mode-tab" onclick="loadLeaderboard('6x4', this)">
                <span class="material-symbols-outlined">grid_on</span> Vừa (6x4)
            </button>
            <button class="mode-tab" onclick="loadLeaderboard('13x6', this)">
                <span class="material-symbols-outlined">apps</span> Khó (13x6)
            </button>
        </div>

        <!-- Podium Top 3 -->
        <div class="podium-container" id="podium-area">
            <!-- Rendered dynamically -->
        </div>

        <!-- Leaderboard Table -->
        <div class="table-card">
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Hạng</th>
                        <th>Người Chơi</th>
                        <th>Bộ Thẻ</th>
                        <th>Điểm Số</th>
                        <th>Thời Gian</th>
                        <th>Độ Chuẩn Xác</th>
                    </tr>
                </thead>
                <tbody id="leaderboard-tbody">
                    <!-- Rendered dynamically -->
                </tbody>
            </table>
        </div>
    </main>

    <script src="bx.js"></script>
</body>
</html>
