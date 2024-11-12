
function countVacant() {
    var vacantCount = 0;
    var squares = document.querySelectorAll('.field div, .ufield div');
    squares.forEach(function(square) {
        if (square.textContent === '空席') {
            vacantCount++;
        }
    });
    document.getElementById('vacantCount').textContent = vacantCount;
}

window.onload = countVacant;