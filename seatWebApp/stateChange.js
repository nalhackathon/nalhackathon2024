

// var vacantCount = 0;
// var squares = document.querySelectorAll('.field div, .ufield div');
// squares.forEach(function(square) {
//     if (square.textContent === '空席') {

//       reserveButton.style.display = "block";

//         document.getElementById(square).addEventListener("click", function(){
//             document.getElementById('square1').style.backgroundColor = 'purple'; //座席の色変更
//             square.firstChild.nodeValue ='予約中';
//             popupWrapper.style.display = "block"; //ポップアップの表示
//         })
        
//         // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
//         popupWrapper.addEventListener('click', e => {
//           if (e.target.id === popupWrapper.id || e.target.id === close.id) {
//             popupWrapper.style.display = 'none'; // ポップアップの削除
//             document.getElementById('square1').style.backgroundColor = ' #59b8f3'; //座席の色変更
//             square1.firstChild.nodeValue ='空席';
//           }
//         });

//     }
// });


window.onload = function() {
    var squares = document.querySelectorAll("[id^='square']");
    squares.forEach(function(square) {
        if (square.textContent === "空席") {
            square.style.display = "block";
        }
    });
};
