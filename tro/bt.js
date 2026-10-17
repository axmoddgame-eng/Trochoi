// bt.js - Logic Bộ Thẻ Chủ Đề & Tùy Biến Mặt Lưng
let selectedDeckCode = 'chom_sao';
let currentSelectedBack = localStorage.getItem('selected_card_back') || 'back-trongdong';

const sampleDecks = [
    { code: 'chom_sao', name: 'Chòm Sao Vũ Trụ', category: 'Khoa học & Vũ Trụ', rarity: 'Huyền thoại', badgeClass: 'badge-legend', icon: '🌌', unlocked: true, active: true, price: 0 },
    { code: 'dan_gian', name: 'Thần Thoại Dân Gian', category: 'Dân gian & Truyền thống', rarity: 'Hiếm', badgeClass: 'badge-rare', icon: '🐉', unlocked: false, active: false, price: 300 },
    { code: 'dong_vat', name: 'Thế Giới Động Vật', category: 'Thiên nhiên & Động vật', rarity: 'Phổ biến', badgeClass: 'badge-common', icon: '🦁', unlocked: true, active: false, price: 150 },
    { code: 'am_thuc', name: 'Ẩm Thực Việt Nam', category: 'Ẩm thực', rarity: 'Phổ biến', badgeClass: 'badge-common', icon: '🍜', unlocked: false, active: false, price: 200 },
    { code: 'su_kien', name: 'Nhật Thực Huyền Bằng', category: 'Sự kiện giới hạn', rarity: 'Sự kiện giới hạn', badgeClass: 'badge-legend', icon: '☀️', unlocked: false, active: false, price: 1000 }
];

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    renderDecks(sampleDecks);
    selectBackPattern(currentSelectedBack);
});

function initTheme() {
    if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');
    document.getElementById('dark-toggle').addEventListener('click', () => {
        document.body.classList.toggle('light-mode');
        localStorage.setItem('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark');
    });
}

function renderDecks(decks) {
    const grid = document.getElementById('decks-grid');
    grid.innerHTML = '';

    decks.forEach(deck => {
        const div = document.createElement('div');
        div.className = `deck-card ${deck.active ? 'active' : ''}`;
        div.innerHTML = `
            <div>
                <div class="deck-icon">${deck.icon}</div>
                <h3 class="deck-name">${deck.name}</h3>
                <p class="deck-cat">${deck.category}</p>
                <span class="badge ${deck.badgeClass}">${deck.rarity}</span>
            </div>
            <div>
                ${deck.active ? 
                    `<button class="btn btn-primary btn-block" disabled>Đang Sử Dụng</button>` :
                    deck.unlocked ?
                    `<button class="btn btn-primary btn-block" onclick="useDeck('${deck.code}')">Kích Hoạt</button>` :
                    `<button class="btn btn-secondary btn-block" onclick="unlockDeck('${deck.code}', ${deck.price})">Mở Khóa (${deck.price} ⭐)</button>`
                }
            </div>
        `;
        grid.appendChild(div);
    });
}

// Realtime Filter & Search
function filterDecks() {
    const query = document.getElementById('search-input').value.toLowerCase().trim();
    const activeCat = document.querySelector('.cat-tab.active').dataset.cat;

    const filtered = sampleDecks.filter(deck => {
        const matchesQuery = deck.name.toLowerCase().includes(query) || deck.category.toLowerCase().includes(query);
        let matchesCat = true;
        if (activeCat === 'using') matchesCat = deck.active;
        else if (activeCat === 'unlocked') matchesCat = deck.unlocked;
        else if (activeCat !== 'all') matchesCat = deck.category === activeCat;

        return matchesQuery && matchesCat;
    });

    renderDecks(filtered);
}

function selectCat(btn, cat) {
    document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterDecks();
}

function useDeck(code) {
    sampleDecks.forEach(d => d.active = (d.code === code));
    filterDecks();
    localStorage.setItem('selected_deck', code);
}

function unlockDeck(code, price) {
    if (confirm(`Bạn có muốn dùng ${price} sao để mở khóa bộ thẻ này?`)) {
        const currentStars = parseInt(document.getElementById('stars-count').textContent);
        if (currentStars >= price) {
            document.getElementById('stars-count').textContent = currentStars - price;
            const target = sampleDecks.find(d => d.code === code);
            if (target) target.unlocked = true;
            useDeck(code);
        } else {
            alert('Bạn chưa đủ sao tích lũy!');
        }
    }
}

// Card Back Customization & Interactive 3D Preview
function selectBackPattern(pattern) {
    currentSelectedBack = pattern;
    document.querySelectorAll('.back-card-option').forEach(opt => {
        opt.classList.toggle('active', opt.dataset.back === pattern);
    });

    const previewBack = document.getElementById('preview-back-face');
    previewBack.className = `stage-card-face stage-card-back ${pattern}`;
}

function applyCardBack() {
    localStorage.setItem('selected_card_back', currentSelectedBack);
    
    const formData = new FormData();
    formData.append('card_back', currentSelectedBack);
    fetch('au.php?action=save_card_back', { method: 'POST', body: formData })
        .then(() => {
            alert('Đã áp dụng thành công mặt lưng thẻ bài vào đấu trường!');
        });
}
