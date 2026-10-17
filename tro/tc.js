// tc.js - JavaScript cho Trang Chủ
let currentSelectedMode = '4x4';

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    checkAuth();
    loadPersonalBest('4x4');
});

// Dark Mode Toggle
function initTheme() {
    const isLight = localStorage.getItem('theme') === 'light';
    if (isLight) document.body.classList.add('light-mode');
    
    document.getElementById('dark-toggle').addEventListener('click', () => {
        document.body.classList.toggle('light-mode');
        const theme = document.body.classList.contains('light-mode') ? 'light' : 'dark';
        localStorage.setItem('theme', theme);
    });
}

// Check Authentication
function checkAuth() {
    fetch('au.php?action=check_auth')
        .then(res => res.json())
        .then(data => {
            const authArea = document.getElementById('user-auth-area');
            if (data.logged_in && data.user) {
                authArea.innerHTML = `
                    <div class="user-badge">
                        <span class="material-symbols-outlined">star</span>
                        <span>${data.user.sao_tich_luy}</span>
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="logout()">${data.user.username}</button>
                `;
            } else {
                authArea.innerHTML = `
                    <button class="btn btn-primary btn-sm" onclick="openModal('auth-modal')">
                        <span class="material-symbols-outlined">account_circle</span> Đăng Nhập
                    </button>
                `;
            }
        });
}

// Select Game Mode & Fetch Best Record
function selectMode(mode) {
    currentSelectedMode = mode;
    document.querySelectorAll('.mode-card').forEach(card => {
        card.classList.toggle('active', card.dataset.mode === mode);
    });
    document.getElementById('current-mode-label').textContent = mode;
    loadPersonalBest(mode);
}

function loadPersonalBest(mode) {
    fetch(`au.php?action=get_personal_best&mode=${mode}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.record) {
                document.getElementById('record-score').textContent = data.record.score || 0;
                document.getElementById('record-time').textContent = (data.record.time_seconds || 0) + 's';
                document.getElementById('record-acc').textContent = (data.record.accuracy || 0) + '%';
            }
        });
}

// Start Game Play
function startPlay() {
    localStorage.setItem('selected_mode', currentSelectedMode);
    window.location.href = 'mc.php';
}

// Modal Controllers
function openModal(id) {
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

// History Modal
function openHistoryModal() {
    openModal('history-modal');
    fetch('au.php?action=get_history')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('history-table-body');
            tbody.innerHTML = '';
            if (data.history && data.history.length > 0) {
                data.history.forEach(item => {
                    const statusClass = item.status === 'COMPLETED' ? 'status-completed' : 'status-abandoned';
                    const statusLabel = item.status === 'COMPLETED' ? 'Hoàn Thành' : 'Bỏ Dở';
                    const tr = document.createElement('tr');
                    tr.id = `history-row-${item.id}`;
                    tr.innerHTML = `
                        <td><strong>${item.deck_name}</strong></td>
                        <td>${item.mode}</td>
                        <td>${item.time_seconds}s</td>
                        <td>${item.accuracy}%</td>
                        <td><strong style="color: var(--primary);">${item.score}</strong></td>
                        <td><span class="badge-status ${statusClass}">${statusLabel}</span></td>
                        <td>
                            <button class="btn btn-outline btn-sm" onclick="deleteHistoryItem(${item.id})" title="Xóa lịch sử trận này">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:20px;">Chưa có lịch sử thi đấu nào!</td></tr>';
            }
        });
}

// Delete History Item
function deleteHistoryItem(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa lịch sử trận đấu này không?')) return;
    
    const formData = new FormData();
    formData.append('id', id);
    fetch('au.php?action=delete_history', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`history-row-${id}`);
                if (row) row.remove();
            } else {
                alert(data.message || 'Lỗi khi xóa lịch sử!');
            }
        });
}

// Auth Handlers
function switchAuthTab(type) {
    document.getElementById('tab-login-btn').classList.toggle('active', type === 'login');
    document.getElementById('tab-register-btn').classList.toggle('active', type === 'register');
    document.getElementById('login-form').style.display = type === 'login' ? 'block' : 'none';
    document.getElementById('register-form').style.display = type === 'register' ? 'block' : 'none';
}

function handleAuth(event, type) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(`au.php?action=${type}`, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal('auth-modal');
                checkAuth();
            } else {
                alert(data.message);
            }
        });
}

function logout() {
    fetch('au.php?action=logout')
        .then(res => res.json())
        .then(() => {
            checkAuth();
        });
}
