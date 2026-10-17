<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Màn Chơi - Siêu Trí Nhớ</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="mc.css">
</head>
<body>
    <!-- Game Header (Clean Top Bar, NO main navbar menu) -->
    <header class="game-header">
        <div class="header-container">
            <button class="btn btn-outline btn-sm" onclick="confirmExitGame()">
                <span class="material-symbols-outlined">arrow_back</span> Trang Chủ
            </button>

            <div class="game-hud">
                <div class="hud-card">
                    <span class="hud-label">Mức Độ</span>
                    <span class="hud-value" id="hud-mode">4x4</span>
                </div>
                <div class="hud-card">
                    <span class="hud-label">Thời Gian</span>
                    <span class="hud-value" id="hud-timer">00:00</span>
                </div>
                <div class="hud-card">
                    <span class="hud-label">Số Lượt Lật</span>
                    <span class="hud-value" id="hud-moves">0</span>
                </div>
                <div class="hud-card">
                    <span class="hud-label">Cặp Đã Ghép</span>
                    <span class="hud-value" id="hud-matched">0 / 8</span>
                </div>
            </div>

            <div class="header-actions">
                <button id="pause-btn" class="btn btn-warning" onclick="pauseGame()">
                    <span class="material-symbols-outlined">pause</span> Tạm Dừng
                </button>
            </div>
        </div>
    </header>

    <!-- Preview Banner Count Down -->
    <div id="preview-banner" class="preview-banner" style="display: none;">
        <span class="material-symbols-outlined">visibility</span>
        <span id="preview-text">Ghi nhớ vị trí bài: 3s</span>
    </div>

    <!-- Main Game Board Container -->
    <main class="game-main">
        <div id="game-grid" class="game-grid grid-4x4">
            <!-- Cards generated dynamically via JavaScript -->
        </div>
    </main>

    <!-- Modal: Tạm Dừng Game -->
    <div id="pause-modal" class="modal-overlay">
        <div class="modal-container modal-sm text-center">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined">pause_circle</span> Đã Tạm Dừng</h2>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 20px; color: var(--text-muted);">Trò chơi đang được tạm dừng. Đồng hồ thời gian đã đóng băng!</p>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <button class="btn btn-primary btn-block" onclick="resumeGame()">
                        <span class="material-symbols-outlined">play_arrow</span> Tiếp Tục Chơi
                    </button>
                    <button class="btn btn-secondary btn-block" onclick="restartGame()">
                        <span class="material-symbols-outlined">refresh</span> Chơi Lại Từ Đầu
                    </button>
                    <button class="btn btn-danger btn-block" onclick="exitGame('ABANDONED')">
                        <span class="material-symbols-outlined">exit_to_app</span> Thoát Về Trang Chủ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Báo Kết Quả Chiến Thắng -->
    <div id="victory-modal" class="modal-overlay">
        <div class="modal-container text-center">
            <div class="modal-header">
                <h2><span class="material-symbols-outlined" style="color: var(--accent); font-size: 32px;">military_tech</span> Chiến Thắng Xuất Sắc!</h2>
            </div>
            <div class="modal-body">
                <div class="victory-stars">
                    <span class="material-symbols-outlined star-icon">star</span>
                    <span class="material-symbols-outlined star-icon main">star</span>
                    <span class="material-symbols-outlined star-icon">star</span>
                </div>
                <h3 id="victory-title" style="margin-top: 10px;">Xếp Hạng Bậc Thầy!</h3>
                <p id="victory-desc" style="color: var(--text-muted); margin-bottom: 20px;">Bạn đã ghép thành công toàn bộ bộ thẻ chòm sao!</p>

                <div class="victory-stats">
                    <div class="v-stat">
                        <span>Thời Gian</span>
                        <strong id="v-time">0s</strong>
                    </div>
                    <div class="v-stat">
                        <span>Lượt Lật</span>
                        <strong id="v-moves">0</strong>
                    </div>
                    <div class="v-stat">
                        <span>Độ Chuẩn Xác</span>
                        <strong id="v-acc">100%</strong>
                    </div>
                    <div class="v-stat">
                        <span>Thưởng Sao</span>
                        <strong id="v-stars" style="color: var(--accent);">+500 ⭐</strong>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button class="btn btn-secondary btn-block" onclick="window.location.href='tc.php'">
                        Trang Chủ
                    </button>
                    <button class="btn btn-primary btn-block" onclick="restartGame()">
                        Chơi Lại
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="mc.js"></script>
</body>
</html>
