// let deck = [];
// let hand = [];
// let turnCount = 0;
// let currentCardIndex = -1;

// document.getElementById('start-button').addEventListener('click', startGame);

// function startGame() {
//     document.getElementById('start-screen').style.display = 'none';
//     document.getElementById('game-board').style.display = 'block';
    
//     deck = Array.from({ length: 96 }, (_, i) => `Card ${i + 1}`);
//     shuffleDeck();
//     turnCount = 0;
//     updateTurnCount();
// }

// function shuffleDeck() {
//     for (let i = deck.length - 1; i > 0; i--) {
//         const j = Math.floor(Math.random() * (i + 1));
//         [deck[i], deck[j]] = [deck[j], deck[i]];
//     }
// }

// function drawInitialCards() {
//     hand = deck.splice(0, 5);
//     displayHand();
    
//     document.getElementById('draw-five').style.display = 'none';
//     document.getElementById('draw-one').style.display = 'inline-block';
// }

// function drawOneCard() {
//     if (deck.length > 0) {
//         hand.push(deck.shift());
//         displayHand();
        
//         // 手札が6枚になる場合のみカードを捨てる処理を追加
//         if (hand.length === 6) {
//             document.getElementById('draw-one').style.display = 'none';
//             enableDiscarding();
//         }
//     }
// }

// function enableDiscarding() {
//     const handContainer = document.getElementById('hand');
//     handContainer.querySelectorAll('.card').forEach((cardElement, index) => {
//         cardElement.addEventListener('click', () => prepareDiscard(index));
//     });
// }

// function displayHand() {
//     const handContainer = document.getElementById('hand');
//     handContainer.innerHTML = '';
    
//     hand.forEach(card => {
//         const cardElement = document.createElement('div');
//         cardElement.className = 'card';
//         cardElement.textContent = card;
//         handContainer.appendChild(cardElement);
//     });
// }

// function prepareDiscard(index) {
//     currentCardIndex = index;
//     const cardToDiscard = hand[index];

//     // アップ表示エリアに捨てるカードを表示
//     const discardCardContainer = document.getElementById('discard-card');
//     discardCardContainer.textContent = cardToDiscard;

//     document.getElementById('discard-area').style.display = 'block';
// }

// function confirmDiscard() {
//     if (currentCardIndex !== -1) {
//         hand.splice(currentCardIndex, 1);
//         turnCount++;
//         updateTurnCount();
        
//         // 5ターンに達したらゲーム終了
//         if (turnCount >= 5) {
//             endGame();
//         } else {
//             // 6枚目を捨てた後に次のカードを引けるようにする
//             document.getElementById('discard-area').style.display = 'none';
//             document.getElementById('draw-one').style.display = 'inline-block';
//             displayHand();
//         }
//     }
// }

// function updateTurnCount() {
//     document.getElementById('turn-count').textContent = turnCount;
// }

// function endGame() {
//     const finalHandContainer = document.getElementById('final-hand');
//     finalHandContainer.innerHTML = '';
    
//     hand.forEach(card => {
//         const cardContainer = document.createElement('div');
//         cardContainer.className = 'final-card';
        
//         const cardElement = document.createElement('div');
//         cardElement.className = 'card';
//         cardElement.textContent = card;

//         const reasonInput = document.createElement('textarea');
//         reasonInput.placeholder = '理由を記入してください';

//         // カードと理由を横に並べる
//         const wrapper = document.createElement('div');
//         wrapper.style.display = 'flex';
//         wrapper.style.alignItems = 'center';
//         wrapper.style.marginBottom = '10px';

//         wrapper.appendChild(cardElement);
//         wrapper.appendChild(reasonInput);
//         finalHandContainer.appendChild(wrapper);
//     });
    
//     document.getElementById('game-board').style.display = 'none';
//     document.getElementById('end-screen').style.display = 'block';
// }

// function submitHand() {
//     const submissionDetails = document.getElementById('submission-details');
//     submissionDetails.innerHTML = '';
    
//     // 各カードとその理由を表示
//     const finalHandContainer = document.getElementById('final-hand');
//     const textareas = finalHandContainer.querySelectorAll('textarea');

//     hand.forEach((card, index) => {
//         const reason = textareas[index].value;
//         const detail = document.createElement('div');
//         detail.textContent = `${card}: ${reason}`;
//         submissionDetails.appendChild(detail);
//     });

//     document.getElementById('end-screen').style.display = 'none';
//     document.getElementById('submission-screen').style.display = 'block';
// }

// function resetGame() {
//     document.getElementById('game-board').style.display = 'none';
//     document.getElementById('submission-screen').style.display = 'none';
//     document.getElementById('end-screen').style.display = 'none';
//     document.getElementById('start-screen').style.display = 'block';
    
//     document.getElementById('draw-five').style.display = 'inline-block';
//     document.getElementById('draw-one').style.display = 'none';
    
//     hand = [];
//     turnCount = 0;
//     deck = [];
//     currentCardIndex = -1;
//     document.getElementById('discard-area').style.display = 'none';
// }

let deck = [];
let hand = [];
let turnCount = 0;
let currentCardIndex = -1;
let discardedCards = []; // 捨てたカードと理由を格納する配列

document.getElementById('start-button').addEventListener('click', startGame);

function startGame() {
    document.getElementById('start-screen').style.display = 'none';
    document.getElementById('game-board').style.display = 'block';
    
    deck = Array.from({ length: 96 }, (_, i) => `Card ${i + 1}`);
    shuffleDeck();
    turnCount = 0;
    updateTurnCount();
    discardedCards = []; // 初期化
}

function shuffleDeck() {
    for (let i = deck.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [deck[i], deck[j]] = [deck[j], deck[i]];
    }
}

function drawInitialCards() {
    hand = deck.splice(0, 5);
    displayHand();
    
    document.getElementById('draw-five').style.display = 'none';
    document.getElementById('draw-one').style.display = 'inline-block';
}

function drawOneCard() {
    if (deck.length > 0) {
        hand.push(deck.shift());
        displayHand();
        
        if (hand.length === 6) {
            document.getElementById('draw-one').style.display = 'none';
            enableDiscarding();
        }
    }
}

function enableDiscarding() {
    const handContainer = document.getElementById('hand');
    handContainer.querySelectorAll('.card').forEach((cardElement, index) => {
        cardElement.addEventListener('click', () => prepareDiscard(index));
    });
}

function displayHand() {
    const handContainer = document.getElementById('hand');
    handContainer.innerHTML = '';
    
    hand.forEach(card => {
        const cardElement = document.createElement('div');
        cardElement.className = 'card';
        cardElement.textContent = card;
        handContainer.appendChild(cardElement);
    });
}

function prepareDiscard(index) {
    currentCardIndex = index;
    const cardToDiscard = hand[index];

    const discardCardContainer = document.getElementById('discard-card');
    discardCardContainer.textContent = cardToDiscard;

    document.getElementById('discard-area').style.display = 'block';
}

function confirmDiscard() {
    if (currentCardIndex !== -1) {
        const reason = document.getElementById('discard-reason').value;
        discardedCards.push({ card: hand[currentCardIndex], reason: reason }); // 捨てたカードと理由を追加

        hand.splice(currentCardIndex, 1);
        turnCount++;
        updateTurnCount();

        document.getElementById('discard-reason').value = '';
        
        if (turnCount >= 5) {
            endGame();
        } else {
            document.getElementById('discard-area').style.display = 'none';
            document.getElementById('draw-one').style.display = 'inline-block';
            displayHand();
        }
    }
}

function updateTurnCount() {
    document.getElementById('turn-count').textContent = turnCount;
}

function endGame() {
    const finalHandContainer = document.getElementById('final-hand');
    finalHandContainer.innerHTML = '';

    // 残ったカードを表示するセクション
    const remainingSection = document.createElement('div');
    remainingSection.innerHTML = '<h3>最終手札</h3>';
    hand.forEach(card => {
        const wrapper = document.createElement('div');
        wrapper.style.display = 'flex';
        wrapper.style.alignItems = 'center';
        wrapper.style.marginBottom = '10px';

        const cardElement = document.createElement('div');
        cardElement.className = 'card';
        cardElement.textContent = card;

        const reasonInput = document.createElement('textarea');
        reasonInput.placeholder = '理由を記入してください';

        wrapper.appendChild(cardElement);
        wrapper.appendChild(reasonInput);
        remainingSection.appendChild(wrapper);
    });
    finalHandContainer.appendChild(remainingSection);
    
    // 捨てたカード一覧を表示するセクション
    const discardedSection = document.createElement('div');
    discardedSection.innerHTML = '<h3>捨てたカード一覧</h3>';
    discardedCards.forEach(({ card, reason }) => {
        const entry = document.createElement('div');
        entry.textContent = `${card}: ${reason}`;
        discardedSection.appendChild(entry);
    });
    finalHandContainer.appendChild(discardedSection);
    
    document.getElementById('game-board').style.display = 'none';
    document.getElementById('end-screen').style.display = 'block';
}

function submitHand() {
    const submissionDetails = document.getElementById('submission-details');
    submissionDetails.innerHTML = '';
    
    const finalHandContainer = document.getElementById('final-hand');
    const textareas = finalHandContainer.querySelectorAll('textarea');

    // 残ったカードと理由を表示
    const remainingSection = document.createElement('div');
    remainingSection.innerHTML = '<h3>最終手札</h3>';
    hand.forEach((card, index) => {
        const reason = textareas[index].value;
        const detail = document.createElement('div');
        detail.textContent = `${card}: ${reason}`;
        remainingSection.appendChild(detail);
    });
    submissionDetails.appendChild(remainingSection);

    // 捨てたカードと理由を表示
    const discardedSection = document.createElement('div');
    discardedSection.innerHTML = '<h3>捨てたカード一覧</h3>';
    discardedCards.forEach(({ card, reason }) => {
        const detail = document.createElement('div');
        detail.textContent = `${card}: ${reason}`;
        discardedSection.appendChild(detail);
    });
    submissionDetails.appendChild(discardedSection);

    document.getElementById('end-screen').style.display = 'none';
    document.getElementById('submission-screen').style.display = 'block';
}

function resetGame() {
    document.getElementById('game-board').style.display = 'none';
    document.getElementById('submission-screen').style.display = 'none';
    document.getElementById('end-screen').style.display = 'none';
    document.getElementById('start-screen').style.display = 'block';
    
    document.getElementById('draw-five').style.display = 'inline-block';
    document.getElementById('draw-one').style.display = 'none';
    
    hand = [];
    turnCount = 0;
    deck = [];
    currentCardIndex = -1;
    discardedCards = [];
    document.getElementById('discard-area').style.display = 'none';
}

