-- Database: sieu_tri_nho
CREATE DATABASE IF NOT EXISTS sieu_tri_nho CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sieu_tri_nho;

DROP TABLE IF EXISTS game_history;
DROP TABLE IF EXISTS user_decks;
DROP TABLE IF EXISTS decks;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    sao_tich_luy INT DEFAULT 500,
    active_back VARCHAR(50) DEFAULT 'back-trongdong',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE decks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    deck_code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    rarity VARCHAR(50) NOT NULL,
    price_stars INT DEFAULT 0,
    description TEXT,
    icon_symbol VARCHAR(20) DEFAULT '🌟',
    is_default TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_decks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    deck_id INT NOT NULL,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (deck_id) REFERENCES decks(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE game_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    username VARCHAR(50) DEFAULT 'Khách',
    deck_name VARCHAR(100) DEFAULT 'Chòm Sao Vũ Trụ',
    mode VARCHAR(10) NOT NULL, -- 4x4, 6x4, 13x6
    time_seconds INT NOT NULL,
    accuracy INT NOT NULL,
    score INT NOT NULL,
    stars_earned INT DEFAULT 0,
    status VARCHAR(20) DEFAULT 'COMPLETED', -- COMPLETED, ABANDONED
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, password, fullname, sao_tich_luy, active_back) VALUES
('admin', '123456', 'Quản Trị Viên', 5000, 'back-galaxy'),
('RongThien', '123456', 'Trần Long', 2400, 'back-trongdong'),
('SaoSang99', '123456', 'Nguyễn Minh', 1800, 'back-geometry'),
('HackerPro', '123456', 'Lê Hoàng', 3200, 'back-galaxy'),
('ThanhGame', '123456', 'Phạm Thanh', 1200, 'back-trongdong');

INSERT INTO decks (deck_code, name, category, rarity, price_stars, description, icon_symbol, is_default) VALUES
('chom_sao', 'Chòm Sao Vũ Trụ', 'Khoa học & Vũ Trụ', 'Huyền thoại', 0, 'Bộ thẻ 20 chòm sao rực rỡ trong thiên văn học.', '🌌', 1),
('dan_gian', 'Thần Thoại Dân Gian', 'Dân gian & Truyền thống', 'Hiếm', 300, 'Các nhân vật cổ tích truyền thuyết Việt Nam.', '🐉', 0),
('dong_vat', 'Thế Giới Động Vật', 'Thiên nhiên & Động vật', 'Phổ biến', 150, 'Bộ sưu tập các loài muông thú dễ thương.', '🦁', 0),
('am_thuc', 'Ẩm Thực Việt Nam', 'Ẩm thực', 'Phổ biến', 200, 'Món ăn đặc sản 3 miền Bắc Trung Nam.', '🍜', 0),
('su_kien', 'Sự Kiện Nhật Cực', 'Sự kiện giới hạn', 'Sự kiện giới hạn', 1000, 'Bộ thẻ độc quyền sự kiện nhật thực toàn phần.', '☀️', 0);

INSERT INTO user_decks (user_id, deck_id) VALUES (1, 1), (2, 1), (3, 1), (4, 1), (5, 1);

INSERT INTO game_history (user_id, username, deck_name, mode, time_seconds, accuracy, score, stars_earned, status, played_at) VALUES
(1, 'admin', 'Chòm Sao Vũ Trụ', '4x4', 22, 100, 1500, 500, 'COMPLETED', NOW() - INTERVAL 1 DAY),
(4, 'HackerPro', 'Chòm Sao Vũ Trụ', '4x4', 25, 95, 1420, 500, 'COMPLETED', NOW() - INTERVAL 2 DAY),
(2, 'RongThien', 'Thần Thoại Dân Gian', '4x4', 29, 90, 1350, 500, 'COMPLETED', NOW() - INTERVAL 3 DAY),
(3, 'SaoSang99', 'Chòm Sao Vũ Trụ', '4x4', 35, 85, 1200, 300, 'COMPLETED', NOW() - INTERVAL 4 DAY),
(5, 'ThanhGame', 'Ẩm Thực Việt Nam', '4x4', 42, 80, 1050, 300, 'COMPLETED', NOW() - INTERVAL 5 DAY),
(NULL, 'Khách 102', 'Chòm Sao Vũ Trụ', '4x4', 50, 75, 900, 100, 'ABANDONED', NOW() - INTERVAL 6 DAY),

(4, 'HackerPro', 'Chòm Sao Vũ Trụ', '6x4', 48, 98, 2850, 1000, 'COMPLETED', NOW() - INTERVAL 1 DAY),
(1, 'admin', 'Chòm Sao Vũ Trụ', '6x4', 52, 94, 2700, 1000, 'COMPLETED', NOW() - INTERVAL 2 DAY),
(2, 'RongThien', 'Thần Thoại Dân Gian', '6x4', 61, 90, 2450, 1000, 'COMPLETED', NOW() - INTERVAL 3 DAY),
(3, 'SaoSang99', 'Thế Giới Động Vật', '6x4', 70, 85, 2200, 500, 'COMPLETED', NOW() - INTERVAL 4 DAY),
(5, 'ThanhGame', 'Chòm Sao Vũ Trụ', '6x4', 85, 80, 1900, 500, 'COMPLETED', NOW() - INTERVAL 5 DAY),
(2, 'RongThien', 'Thần Thoại Dân Gian', '6x4', 30, 50, 400, 0, 'ABANDONED', NOW() - INTERVAL 1 HOUR),

(1, 'admin', 'Chòm Sao Vũ Trụ', '13x6', 145, 96, 5800, 1500, 'COMPLETED', NOW() - INTERVAL 1 DAY),
(4, 'HackerPro', 'Chòm Sao Vũ Trụ', '13x6', 160, 92, 5400, 1500, 'COMPLETED', NOW() - INTERVAL 2 DAY),
(2, 'RongThien', 'Chòm Sao Vũ Trụ', '13x6', 185, 88, 4900, 1500, 'COMPLETED', NOW() - INTERVAL 3 DAY),
(3, 'SaoSang99', 'Chòm Sao Vũ Trụ', '13x6', 210, 82, 4300, 1000, 'COMPLETED', NOW() - INTERVAL 4 DAY),
(5, 'ThanhGame', 'Chòm Sao Vũ Trụ', '13x6', 240, 78, 3800, 1000, 'COMPLETED', NOW() - INTERVAL 5 DAY),
(NULL, 'Khách 888', 'Chòm Sao Vũ Trụ', '13x6', 90, 40, 600, 0, 'ABANDONED', NOW() - INTERVAL 2 HOUR);
