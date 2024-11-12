function startGame() {
    fetch('game.php?action=start')
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                document.getElementById('start-screen').style.display = 'none';
                document.getElementById('game-board').style.display = 'block';
                document.getElementById('turn-count').textContent = data.turn;
                updateHand(data.hand);
            }
        });
}

function drawCard() {
    fetch('game.php?action=draw')
        .then(response => response.json())
        .then(data => {
            if (data.card) {
                updateHand(data.hand);
            }
        });
}

function discardCard(index) {
    fetch(`game.php?action=discard&index=${index}`)
        .then(response => response.json())
        .then(data => {
            if (data.endGame) {
                endGame(data.hand);
            } else {
                document.getElementById('turn-count').textContent = data.turn;
                updateHand(data.hand);
            }
        });
}

function updateHand(hand) {
    const cardList = document.getElementById('card-list');
    cardList.innerHTML = '';
    hand.forEach((card, index) => {
        const cardItem = document.createElement('li');
        cardItem.textContent = card;
        cardItem.onclick = () => discardCard(index);
        cardList.appendChild(cardItem);
    });
}

function endGame(finalHand) {
    document.getElementById('game-board').style.display = 'none';
    document.getElementById('end-screen').style.display = 'block';
    const finalHandList = document.getElementById('final-hand');
    finalHandList.innerHTML = '';
    finalHand.forEach(card => {
        const cardItem = document.createElement('li');
        cardItem.textContent = card;
        finalHandList.appendChild(cardItem);
    });
}

function resetGame() {
    document.getElementById('start-screen').style.display = 'block';
    document.getElementById('game-board').style.display = 'none';
    document.getElementById('end-screen').style.display = 'none';
}
