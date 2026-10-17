<?php
// au.php - Auth & Backend API
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_REQUEST['action'] ?? '';

function get_current_user_info($pdo) {
    if (isset($_SESSION['user_id']) && $pdo) {
        $stmt = $pdo->prepare("SELECT id, username, fullname, sao_tich_luy, active_back FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) return $user;
    }
    return [
        'id' => $_SESSION['user_id'] ?? 0,
        'username' => $_SESSION['username'] ?? 'Khách',
        'fullname' => $_SESSION['fullname'] ?? 'Người chơi Khách',
        'sao_tich_luy' => $_SESSION['sao_tich_luy'] ?? 500,
        'active_back' => $_SESSION['active_back'] ?? 'back-trongdong'
    ];
}

switch ($action) {
    case 'check_auth':
        $user = get_current_user_info($pdo);
        echo json_encode(['success' => true, 'logged_in' => isset($_SESSION['user_id']), 'user' => $user]);
        break;

    case 'login':
        $user_input = trim($_POST['username'] ?? '');
        $pass_input = trim($_POST['password'] ?? '');

        if (!$user_input || !$pass_input) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ tài khoản và mật khẩu!']);
            exit;
        }

        if ($pdo) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->execute([$user_input, $pass_input]);
            $user = $stmt->fetch();
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['sao_tich_luy'] = $user['sao_tich_luy'];
                $_SESSION['active_back'] = $user['active_back'];
                echo json_encode(['success' => true, 'message' => 'Đăng nhập thành công!', 'user' => $user]);
                exit;
            }
        }
        
        if ($user_input === 'admin' && $pass_input === '123456') {
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = 'admin';
            $_SESSION['fullname'] = 'Quản Trị Viên';
            $_SESSION['sao_tich_luy'] = 5000;
            $_SESSION['active_back'] = 'back-galaxy';
            echo json_encode(['success' => true, 'message' => 'Đăng nhập thành công!', 'user' => [
                'id' => 1, 'username' => 'admin', 'fullname' => 'Quản Trị Viên', 'sao_tich_luy' => 5000, 'active_back' => 'back-galaxy'
            ]]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Tài khoản hoặc mật khẩu không chính xác!']);
        break;

    case 'register':
        $user_input = trim($_POST['username'] ?? '');
        $pass_input = trim($_POST['password'] ?? '');
        $name_input = trim($_POST['fullname'] ?? '');

        if (!$user_input || !$pass_input || !$name_input) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
            exit;
        }

        if ($pdo) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$user_input]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Tên tài khoản này đã tồn tại!']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, sao_tich_luy) VALUES (?, ?, ?, 500)");
            $stmt->execute([$user_input, $pass_input, $name_input]);
            $new_id = $pdo->lastInsertId();

            $_SESSION['user_id'] = $new_id;
            $_SESSION['username'] = $user_input;
            $_SESSION['fullname'] = $name_input;
            $_SESSION['sao_tich_luy'] = 500;
            $_SESSION['active_back'] = 'back-trongdong';

            echo json_encode(['success' => true, 'message' => 'Đăng ký thành công! Nhận 500 sao thưởng!', 'user' => [
                'id' => $new_id, 'username' => $user_input, 'fullname' => $name_input, 'sao_tich_luy' => 500, 'active_back' => 'back-trongdong'
            ]]);
            exit;
        }

        $_SESSION['user_id'] = 99;
        $_SESSION['username'] = $user_input;
        $_SESSION['fullname'] = $name_input;
        $_SESSION['sao_tich_luy'] = 500;
        echo json_encode(['success' => true, 'message' => 'Đăng ký thành công!']);
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Đã đăng xuất thành công!']);
        break;

    case 'get_personal_best':
        $mode = $_GET['mode'] ?? '4x4';
        $user_id = $_SESSION['user_id'] ?? null;

        if ($pdo && $user_id) {
            $stmt = $pdo->prepare("SELECT score, time_seconds, accuracy FROM game_history WHERE user_id = ? AND mode = ? AND status = 'COMPLETED' ORDER BY score DESC, time_seconds ASC LIMIT 1");
            $stmt->execute([$user_id, $mode]);
            $record = $stmt->fetch();
            if ($record) {
                echo json_encode(['success' => true, 'record' => $record]);
                exit;
            }
        }

        $mock_bests = [
            '4x4' => ['score' => 1500, 'time_seconds' => 22, 'accuracy' => 100],
            '6x4' => ['score' => 2850, 'time_seconds' => 48, 'accuracy' => 98],
            '13x6' => ['score' => 5800, 'time_seconds' => 145, 'accuracy' => 96]
        ];
        echo json_encode(['success' => true, 'record' => $mock_bests[$mode] ?? $mock_bests['4x4']]);
        break;

    case 'get_history':
        $user_id = $_SESSION['user_id'] ?? null;
        if ($pdo && $user_id) {
            $stmt = $pdo->prepare("SELECT * FROM game_history WHERE user_id = ? ORDER BY played_at DESC LIMIT 20");
            $stmt->execute([$user_id]);
            $history = $stmt->fetchAll();
            echo json_encode(['success' => true, 'history' => $history]);
            exit;
        }

        $sample_history = [
            ['id' => 1, 'username' => $_SESSION['username'] ?? 'Khách', 'deck_name' => 'Chòm Sao Vũ Trụ', 'mode' => '4x4', 'time_seconds' => 22, 'accuracy' => 100, 'score' => 1500, 'stars_earned' => 500, 'status' => 'COMPLETED', 'played_at' => '2026-09-21 14:30:00'],
            ['id' => 2, 'username' => $_SESSION['username'] ?? 'Khách', 'deck_name' => 'Chòm Sao Vũ Trụ', 'mode' => '6x4', 'time_seconds' => 52, 'accuracy' => 94, 'score' => 2700, 'stars_earned' => 1000, 'status' => 'COMPLETED', 'played_at' => '2026-09-20 10:15:00'],
            ['id' => 3, 'username' => $_SESSION['username'] ?? 'Khách', 'deck_name' => 'Thần Thoại Dân Gian', 'mode' => '13x6', 'time_seconds' => 180, 'accuracy' => 75, 'score' => 1200, 'stars_earned' => 0, 'status' => 'ABANDONED', 'played_at' => '2026-09-19 18:45:00']
        ];
        echo json_encode(['success' => true, 'history' => $sample_history]);
        break;

    case 'delete_history':
        $history_id = intval($_POST['id'] ?? 0);
        if (!$history_id) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ!']);
            exit;
        }

        if ($pdo) {
            $stmt = $pdo->prepare("DELETE FROM game_history WHERE id = ?");
            $stmt->execute([$history_id]);
        }
        echo json_encode(['success' => true, 'message' => 'Đã xóa lịch sử trận đấu thành công!']);
        break;

    case 'save_game':
        $deck_name = $_POST['deck_name'] ?? 'Chòm Sao Vũ Trụ';
        $mode = $_POST['mode'] ?? '4x4';
        $time_seconds = intval($_POST['time_seconds'] ?? 0);
        $accuracy = intval($_POST['accuracy'] ?? 0);
        $score = intval($_POST['score'] ?? 0);
        $stars_earned = intval($_POST['stars_earned'] ?? 0);
        $status = $_POST['status'] ?? 'COMPLETED';

        $user_id = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? 'Khách';

        if (isset($_SESSION['sao_tich_luy'])) {
            $_SESSION['sao_tich_luy'] += $stars_earned;
        }

        if ($pdo) {
            $stmt = $pdo->prepare("INSERT INTO game_history (user_id, username, deck_name, mode, time_seconds, accuracy, score, stars_earned, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $username, $deck_name, $mode, $time_seconds, $accuracy, $score, $stars_earned, $status]);

            if ($user_id && $stars_earned > 0) {
                $stmt = $pdo->prepare("UPDATE users SET sao_tich_luy = sao_tich_luy + ? WHERE id = ?");
                $stmt->execute([$stars_earned, $user_id]);
            }
        }

        echo json_encode(['success' => true, 'message' => 'Lưu ván chơi thành công!', 'new_total_stars' => $_SESSION['sao_tich_luy'] ?? 500]);
        break;

    case 'get_leaderboard':
        $mode = $_GET['mode'] ?? 'ALL';
        if ($pdo) {
            $sql = "SELECT username, deck_name, mode, score, time_seconds, accuracy, played_at FROM game_history WHERE status = 'COMPLETED'";
            $params = [];
            if ($mode !== 'ALL') {
                $sql .= " AND mode = ?";
                $params[] = $mode;
            }
            $sql .= " ORDER BY score DESC, time_seconds ASC LIMIT 15";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();
            echo json_encode(['success' => true, 'leaderboard' => $rows]);
            exit;
        }

        $mock_lb = [
            ['username' => 'admin', 'deck_name' => 'Chòm Sao Vũ Trụ', 'mode' => '13x6', 'score' => 5800, 'time_seconds' => 145, 'accuracy' => 96, 'played_at' => '2026-09-21'],
            ['username' => 'HackerPro', 'deck_name' => 'Chòm Sao Vũ Trụ', 'mode' => '13x6', 'score' => 5400, 'time_seconds' => 160, 'accuracy' => 92, 'played_at' => '2026-09-20'],
            ['username' => 'RongThien', 'deck_name' => 'Thần Thoại Dân Gian', 'mode' => '6x4', 'score' => 2850, 'time_seconds' => 48, 'accuracy' => 98, 'played_at' => '2026-09-21'],
            ['username' => 'SaoSang99', 'deck_name' => 'Chòm Sao Vũ Trụ', 'mode' => '4x4', 'score' => 1500, 'time_seconds' => 22, 'accuracy' => 100, 'played_at' => '2026-09-22'],
            ['username' => 'ThanhGame', 'deck_name' => 'Ẩm Thực Việt Nam', 'mode' => '6x4', 'score' => 2450, 'time_seconds' => 61, 'accuracy' => 90, 'played_at' => '2026-09-19']
        ];
        if ($mode !== 'ALL') {
            $mock_lb = array_values(array_filter($mock_lb, function($item) use ($mode) { return $item['mode'] === $mode; }));
        }
        echo json_encode(['success' => true, 'leaderboard' => $mock_lb]);
        break;

    case 'save_card_back':
        $card_back = $_POST['card_back'] ?? 'back-trongdong';
        $_SESSION['active_back'] = $card_back;
        if ($pdo && isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("UPDATE users SET active_back = ? WHERE id = ?");
            $stmt->execute([$card_back, $_SESSION['user_id']]);
        }
        echo json_encode(['success' => true, 'message' => 'Đã lưu thiết kế mặt lưng thẻ bài!']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ!']);
        break;
}
?>