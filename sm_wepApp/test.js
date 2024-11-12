document.addEventListener("DOMContentLoaded", function() {
    const seats = document.querySelectorAll(".seat, .long-seat");
    const seatInfo = document.getElementById("seat-info");

    seats.forEach(seat => {
        seat.addEventListener("click", function() {
            const seatId = this.id;
            const seatNumber = seatId.split("-")[1];
            seatInfo.innerHTML = `<p>座席番号: ${seatNumber}</p>`;
            
            // 座席の予約状態の処理を追加
            this.classList.toggle("reserved");
        });
    });
});
