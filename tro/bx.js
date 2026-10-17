// bx.js - Logic Bảng Xếp Hạng
document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');
    document.getElementById('dark-toggle').addEventListener('click', () => {
        document.body.classList.toggle('light-mode');
        localStorage.setItem('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark');
    });

    loadLeaderboard('4x4');
});

function loadLeaderboard(mode, tabElem = null) {
    if (tabElem) {
        document.querySelectorAll('.mode-tab').forEach(t => t.classList.remove('active'));
        tabElem.classList.add('active');
    }

    fetch(`au.php?action=get_leaderboard&mode=${mode}`)
        .then(res => res.json())
        .then(data => {
            const list = data.leaderboard || [];
            renderPodium(list.slice(0, 3));
            renderTable(list);
        });
}

function renderPodium(top3) {
    const podium = document.getElementById('podium-area');
    podium.innerHTML = '';

    const ranks = [
        { pos: 2, class: 'rank-2', icon: '🥈' },
        { pos: 1, class: 'rank-1', icon: '👑' },
        { pos: 3, class: 'rank-3', icon: '🥉' }
    ];

    ranks.forEach(r => {
        const item = top3[r.pos - 1] || { username: 'Đang chờ...', score: 0 };
        const div = document.createElement('div');
        div.className = `podium-item ${r.class}`;
        div.innerHTML = `
            <div class="podium-avatar">${r.icon}</div>
            <div class="podium-name">${item.username}</div>
            <div class="podium-score">${item.score} điểm</div>
            <small style="color: var(--text-muted);">${item.deck_name || 'Chòm Sao'}</small>
        `;
        podium.appendChild(div);
    });
}

function renderTable(list) {
    const tbody = document.getElementById('leaderboard-tbody');
    tbody.innerHTML = '';

    list.forEach((item, idx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><div class="rank-badge">${idx + 1}</div></td>
            <td><strong>${item.username}</strong></td>
            <td>${item.deck_name || 'Chòm Sao Vũ Trụ'}</td>
            <td><strong style="color: var(--primary);">${item.score}</strong></td>
            <td>${item.time_seconds}s</td>
            <td>${item.accuracy}%</td>
        `;
        tbody.appendChild(tr);
    });
}
