// mc.js - Logic Màn Chơi & Game Loop
let gameMode = localStorage.getItem('selected_mode') || '4x4';
let selectedCardBack = localStorage.getItem('selected_card_back') || 'back-trongdong';

let cardsArray = [];
let flippedCards = [];
let matchedPairs = 0;
let totalPairs = 8;
let movesCount = 0;
let timeSeconds = 0;
let timerInterval = null;
let isPaused = false;
let isPreviewing = false;

// 20 Constellation Symbols & Names
const constellations = [
    { symbol: '♈', name: 'Bạch Dương' }, { symbol: '♉', name: 'Kim Ngưu' },
    { symbol: '♊', name: 'Song Tử' },   { symbol: '♋', name: 'Cự Giải' },
    { symbol: '♌', name: 'Sư Tử' },    { symbol: '♍', name: 'Xử Nữ' },
    { symbol: '♎', name: 'Thiên Bình' }, { symbol: '♏', name: 'Bọ Cạp' },
    { symbol: '♐', name: 'Nhân Mã' },   { symbol: '♑', name: 'Ma Kết' },
    { symbol: '♒', name: 'Bảo Bình' },  { symbol: '♓', name: 'Song Ngư' },
    { symbol: '🐉', name: 'Rồng Thiên' }, { symbol: '🦅', name: 'Đại Bàng' },
    { symbol: '🦄', name: 'Kỳ Lân' },    { symbol: '🌌', name: 'Ngân Hà' },
    { symbol: '🏹', name: 'Cung Thủ' },  { symbol: '🐻', name: 'Đại Hùng' },
    { symbol: '🌟', name: 'Sao Bắc Đẩu' }, { symbol: '⛎', name: 'Xà Phu' }
];

document.addEventListener('DOMContentLoaded', () => {
    initGame();
});

function initGame() {
    document.getElementById('hud-mode').textContent = gameMode;
    
    // Set layout class
    const grid = document.getElementById('game-grid');
    grid.className = `game-grid grid-${gameMode}`;

    if (gameMode === '4x4') totalPairs = 8;
    else if (gameMode === '6x4') totalPairs = 12;
    else if (gameMode === '13x6') totalPairs = 39;

    document.getElementById('hud-matched').textContent = `0 / ${totalPairs}`;

    setupBoard();
    startPreview3Seconds();
}

function setupBoard() {
    const grid = document.getElementById('game-grid');
    grid.innerHTML = '';
    
    // Select required number of pairs
    let selectedSymbols = [];
    while (selectedSymbols.length < totalPairs) {
        let item = constellations[selectedSymbols.length % constellations.length];
        selectedSymbols.push(item);
    }

    // Duplicate for pairs
    cardsArray = [...selectedSymbols, ...selectedSymbols];
    
    // Shuffle 100% randomly
    cardsArray.sort(() => Math.random() - 0.5);

    // Render cards
    cardsArray.forEach((item, idx) => {
        const cardElem = document.createElement('div');
        cardElem.className = 'card-item';
        cardElem.dataset.index = idx;
        cardElem.dataset.symbol = item.symbol;

        cardElem.innerHTML = `
            <div class="card-inner">
                <div class="card-face card-back ${selectedCardBack}">
                    <span class="material-symbols-outlined">style</span>
                </div>
                <div class="card-face card-front">
                    <span class="card-symbol">${item.symbol}</span>
                    <span class="card-name">${item.name}</span>
                </div>
            </div>
        `;

        cardElem.addEventListener('click', () => onCardClick(cardElem));
        grid.appendChild(cardElem);
    });
}

// 3 Seconds Preview Count Down
function startPreview3Seconds() {
    isPreviewing = true;
    const banner = document.getElementById('preview-banner');
    banner.style.display = 'flex';
    
    // Flip all cards face up
    document.querySelectorAll('.card-item').forEach(card => card.classList.add('flipped'));

    let secondsLeft = 3;
    document.getElementById('preview-text').textContent = `Ghi nhớ vị trí bài: ${secondsLeft}s`;

    const previewTimer = setInterval(() => {
        secondsLeft--;
        if (secondsLeft > 0) {
            document.getElementById('preview-text').textContent = `Ghi nhớ vị trí bài: ${secondsLeft}s`;
        } else {
            clearInterval(previewTimer);
            banner.style.display = 'none';
            // Flip all cards face down
            document.querySelectorAll('.card-item').forEach(card => card.classList.remove('flipped'));
            isPreviewing = false;
            startTimer();
        }
    }, 1000);
}

// Timer Logic
function startTimer() {
    timeSeconds = 0;
    updateTimerDisplay();
    clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        if (!isPaused && !isPreviewing) {
            timeSeconds++;
            updateTimerDisplay();
        }
    }, 1000);
}

function updateTimerDisplay() {
    const mins = String(Math.floor(timeSeconds / 60)).padStart(2, '0');
    const secs = String(timeSeconds % 60).padStart(2, '0');
    document.getElementById('hud-timer').textContent = `${mins}:${secs}`;
}

// Card Click Event
function onCardClick(cardElem) {
    if (isPaused || isPreviewing) return;
    if (cardElem.classList.contains('flipped') || cardElem.classList.contains('matched')) return;
    if (flippedCards.length >= 2) return;

    cardElem.classList.add('flipped');
    flippedCards.push(cardElem);

    if (flippedCards.length === 2) {
        movesCount++;
        document.getElementById('hud-moves').textContent = movesCount;
        checkMatch();
    }
}

function checkMatch() {
    const [card1, card2] = flippedCards;
    if (card1.dataset.symbol === card2.dataset.symbol) {
        card1.classList.add('matched');
        card2.classList.add('matched');
        flippedCards = [];
        matchedPairs++;
        document.getElementById('hud-matched').textContent = `${matchedPairs} / ${totalPairs}`;

        if (matchedPairs === totalPairs) {
            onGameVictory();
        }
    } else {
        setTimeout(() => {
            card1.classList.remove('flipped');
            card2.classList.remove('flipped');
            flippedCards = [];
        }, 900);
    }
}

// Pause Game Logic
function pauseGame() {
    isPaused = true;
    document.getElementById('pause-modal').classList.add('active');
}

function resumeGame() {
    isPaused = false;
    document.getElementById('pause-modal').classList.remove('active');
}

function restartGame() {
    document.getElementById('pause-modal').classList.remove('active');
    document.getElementById('victory-modal').classList.remove('active');
    isPaused = false;
    movesCount = 0;
    matchedPairs = 0;
    document.getElementById('hud-moves').textContent = '0';
    initGame();
}

function confirmExitGame() {
    if (confirm('Bạn có muốn tạm dừng và thoát về Trang chủ không?')) {
        exitGame('ABANDONED');
    }
}

function exitGame(status = 'ABANDONED') {
    saveGameResult(status);
    window.location.href = 'tc.php';
}

// Victory Event & Reward
function onGameVictory() {
    clearInterval(timerInterval);
    
    // Calculate Reward Stars & Score
    let starsEarned = 100;
    let baseScore = 1000;
    if (gameMode === '6x4') { starsEarned = 500; baseScore = 2000; }
    if (gameMode === '13x6') { starsEarned = 1500; baseScore = 5000; }

    const accuracy = Math.round((totalPairs / Math.max(movesCount, totalPairs)) * 100);
    const finalScore = Math.max(100, baseScore + (accuracy * 10) - (timeSeconds * 5));

    document.getElementById('v-time').textContent = `${timeSeconds}s`;
    document.getElementById('v-moves').textContent = movesCount;
    document.getElementById('v-acc').textContent = `${accuracy}%`;
    document.getElementById('v-stars').textContent = `+${starsEarned} ⭐`;

    document.getElementById('victory-modal').classList.add('active');

    saveGameResult('COMPLETED', finalScore, accuracy, starsEarned);
}

function saveGameResult(status, score = 0, accuracy = 0, starsEarned = 0) {
    const formData = new FormData();
    formData.append('deck_name', 'Chòm Sao Vũ Trụ');
    formData.append('mode', gameMode);
    formData.append('time_seconds', timeSeconds);
    formData.append('accuracy', accuracy);
    formData.append('score', score);
    formData.append('stars_earned', starsEarned);
    formData.append('status', status);

    fetch('au.php?action=save_game', { method: 'POST', body: formData });
}
