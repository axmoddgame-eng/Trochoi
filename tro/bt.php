<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bộ Thẻ Chủ Đề - Siêu Trí Nhớ</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="bt.css">
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
                <a href="bt.php" class="nav-link active">Bộ Thẻ Chủ Đề</a>
                <a href="bx.php" class="nav-link">Bảng Xếp Hạng</a>
            </nav>
            <div class="nav-right">
                <button id="dark-toggle" class="icon-btn" title="Chuyển chế độ ban đêm">
                    <span class="material-symbols-outlined">dark_mode</span>
                </button>
                <div id="user-stars-badge" class="user-badge">
                    <span class="material-symbols-outlined">star</span>
                    <span id="stars-count">500</span>
                </div>
            </div>
        </div>
    </header>

    <main class="main-container">
        <!-- Header Section -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Thư Viện Bộ Thẻ Chủ Đề</h1>
                <p class="page-desc">Sưu tầm bộ thẻ chòm sao huyền thoại và tùy biến mặt lưng bài để thi đấu!</p>
            </div>
            <a href="mc.php" class="btn btn-primary btn-lg">
                <span class="material-symbols-outlined">sports_esports</span> Vào Đấu Trường Ngay
            </a>
        </div>

        <!-- Realtime Search & Category Filters -->
        <div class="filter-bar">
            <div class="search-box">
                <span class="material-symbols-outlined search-icon">search</span>
                <input type="text" id="search-input" placeholder="Tìm kiếm bộ thẻ theo tên (gõ từ khóa)..." onkeyup="filterDecks()">
            </div>
            <div class="category-tabs">
                <button class="cat-tab active" data-cat="all" onclick="selectCat(this, 'all')">Tất Cả</button>
                <button class="cat-tab" data-cat="using" onclick="selectCat(this, 'using')">Đang Sử Dụng</button>
                <button class="cat-tab" data-cat="unlocked" onclick="selectCat(this, 'unlocked')">Đã Mở Khóa</button>
                <button class="cat-tab" data-cat="Khoa học & Vũ Trụ" onclick="selectCat(this, 'Khoa học & Vũ Trụ')">Khoa Học & Vũ Trụ</button>
                <button class="cat-tab" data-cat="Dân gian & Truyền thống" onclick="selectCat(this, 'Dân gian & Truyền thống')">Dân Gian & Truyền Thống</button>
                <button class="cat-tab" data-cat="Thiên nhiên & Động vật" onclick="selectCat(this, 'Thiên nhiên & Động vật')">Thiên Nhiên & Động Vật</button>
                <button class="cat-tab" data-cat="Ẩm thực" onclick="selectCat(this, 'Ẩm thực')">Ẩm Thực</button>
            </div>
        </div>

        <!-- Decks Grid -->
        <div id="decks-grid" class="decks-grid">
            <!-- Rendered dynamically -->
        </div>

        <!-- Custom Card Back Section with Interactive 3D Preview -->
        <section class="card-customization-section">
            <div class="section-header">
                <h2><span class="material-symbols-outlined">palette</span> Tùy Biến Mặt Lưng Thẻ Bài</h2>
                <p class="sub-text">Chọn thiết kế mặt lưng thể hiện phong cách riêng của bạn khi thi đấu!</p>
            </div>

            <div class="custom-layout">
                <!-- Selector Buttons -->
                <div class="back-options">
                    <button class="back-card-option active" data-back="back-trongdong" onclick="selectBackPattern('back-trongdong')">
                        <div class="back-preview-mini back-trongdong"></div>
                        <div class="back-info">
                            <h4>Trống Đồng Đông Sơn</h4>
                            <p>Đặc trưng văn hóa truyền thống Việt Nam</p>
                            <span class="badge badge-free">Miễn Phí</span>
                        </div>
                    </button>

                    <button class="back-card-option" data-back="back-geometry" onclick="selectBackPattern('back-geometry')">
                        <div class="back-preview-mini back-geometry"></div>
                        <div class="back-info">
                            <h4>Hình Học Neon</h4>
                            <p>Phong cách hiện đại, góc cạnh sắc nét</p>
                            <span class="badge badge-free">Miễn Phí</span>
                        </div>
                    </button>

                    <button class="back-card-option" data-back="back-galaxy" onclick="selectBackPattern('back-galaxy')">
                        <div class="back-preview-mini back-galaxy"></div>
                        <div class="back-info">
                            <h4>Vũ Trụ Huyền Bí</h4>
                            <p>Dải ngân hà rực rỡ sắc màu huyền ảo</p>
                            <span class="badge badge-legend">Huyền Thoại</span>
                        </div>
                    </button>
                </div>

                <!-- Interactive 3D Card Preview -->
                <div class="preview-stage">
                    <div class="stage-card" id="stage-3d-card">
                        <div class="stage-card-inner">
                            <div class="stage-card-face stage-card-back back-trongdong" id="preview-back-face">
                                <span class="material-symbols-outlined">style</span>
                                <span>MẶT LƯNG</span>
                            </div>
                            <div class="stage-card-face stage-card-front">
                                <span style="font-size: 3.5rem;">🌌</span>
                                <span style="font-weight: 700; color: var(--primary);">Chòm Sao</span>
                            </div>
                        </div>
                    </div>
                    <p class="preview-tip">💡 Rê chuột hoặc chạm vào thẻ để lật xem thử mặt lưng!</p>
                    <button class="btn btn-primary" onclick="applyCardBack()">
                        <span class="material-symbols-outlined">check_circle</span> Áp Dụng Mặt Lưng Phù Hợp
                    </button>
                </div>
            </div>
        </section>
    </main>

    <script src="bt.js"></script>
</body>
</html>
