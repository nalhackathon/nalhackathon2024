// 予約用の宣言
const clickBtn = document.getElementById('click-btn');
const popupWrapper = document.getElementById('popup-wrapper');
const close = document.getElementById('close');
const numbox = document.getElementById('numbox');
// キャンセル用の宣言
const clickBtn6 = document.getElementById('click-btn6');
const popupWrapper6 = document.getElementById('popup-wrapper6');
const close6 = document.getElementById('close6');
const numbox6 = document.getElementById('numbox6');

// 離席用の宣言
let afkTex = document.getElementById('seetAfk');

const sq1btn = document.getElementById('sq1btn');

// ボタンをクリックしたときにポップアップを表示させる
// function changecolor(){
//     document.getElementById('square1').style.backgroundColor = 'purple'; //座席の色変更
//     square1.firstChild.nodeValue ='予約中';
//     popupWrapper.style.display = "block"; //ポップアップの表示
// }

// if(popupWrapper != null){
// // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
// popupWrapper.addEventListener('click', e => {
//   if (e.target.id === popupWrapper.id || e.target.id === close.id) {
//     popupWrapper.style.display = 'none'; // ポップアップの削除
//     document.getElementById('square1').style.backgroundColor = ' #59b8f3'; //座席の色変更
//     square1.firstChild.nodeValue ='空席';
//   }
// })
// }

// if(numbox != null){
// numbox.addEventListener('submit', function(event) {
    
//   event.preventDefault(); // フォームの通常の送信を停止  
//   square1.firstChild.nodeValue ='利用中';

//   var result = confirm('続けて予約しますか？');
//     if( result ) {
//       alert("予約が完了しました");
//       window.location.href = 'yoyaku.html'; // 画面の遷移;
//     }else {
//       alert("予約が完了しました");
//       window.location.href = 'home.html'; // 画面の遷移;
//   }
// })
// }

function afk(){
  if(afkTex.value === '離席する'){
    document.getElementById('square3').style.backgroundColor = 'yellow';
    afkTex.value = '離席解除'; //ボタン文字変更
    //document.getElementById('square3').textContent= '離席中'; //文字変更
    square3.firstChild.nodeValue ='離席中'; // firstChildは親ブロックの一番目の要素を指定
  }else{
    document.getElementById('square3').style.backgroundColor = '#eb2052';
    afkTex.value = '離席する'; //文字変更
    //document.getElementById('square3').textContent= '利用中'; //文字変更
    square3.firstChild.nodeValue ='利用中';
  }
}

//キャンセル処理

// ボタンをクリックしたときにポップアップを表示させる
function changecolor6(){
  document.getElementById('square6').style.backgroundColor = 'yellow'; //座席の色変更
  square6.firstChild.nodeValue ='利用中';
  popupWrapper6.style.display = "block"; //ポップアップの表示
}

if(popupWrapper6 != null){

  // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper6.addEventListener('click', e => {
  if (e.target.id === popupWrapper6.id || e.target.id === close6.id) {
  popupWrapper6.style.display = 'none'; // ポップアップの削除
  document.getElementById('square6').style.backgroundColor = ' #eb2052'; //座席の色変更
  square6.firstChild.nodeValue ='利用中';
}
});

}

if(numbox6 != null){

numbox6.addEventListener('submit', function(event) {
  console.log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
  
  event.preventDefault(); // フォームの通常の送信を停止
  square6.firstChild.nodeValue ='空席';
  document.getElementById('square6').style.backgroundColor = ' #59b8f3'; //座席の色変更

  alert("キャンセルしました");
  window.location.href = 'home.php'; // 画面の遷移;
})

}

function executePHP(phpname) { // javascriptからphpを開く
  fetch(phpname, { 
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      }
  })
}

// document.addEventListener('DOMContentLoaded', (event) => { // HTMLの要素が全部読み込まれると走る
//   console.log("Adding event listener to button.");
//   const pushState = document.getElementById("Phptest");
//   pushState.addEventListener('click', executePHP("state.php"));// ボタンを押したら実行
// });

// document.addEventListener('DOMContentLoaded', (event) => { // HTMLの要素が全部読み込まれると走る
//   console.log("Adding event listener to button.");
//   const pushState = document.getElementById("sq1btn");
//   pushState.addEventListener('click', executePHP("state.php"));// ボタンを押したら実行
// });


if(sq1btn != null){
sq1btn.addEventListener('click',function(){
  document.getElementById('square1').style.backgroundColor = 'purple'; //座席の色変更
  square1.firstChild.nodeValue ='予約中';
  popupWrapper.style.display = "block"; //ポップアップの表示

})
}

if(popupWrapper != null){
  // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
  popupWrapper.addEventListener('click', e => {
    if (e.target.id === popupWrapper.id || e.target.id === close.id) {
      popupWrapper.style.display = 'none'; // ポップアップの削除
      document.getElementById('square1').style.backgroundColor = ' #59b8f3'; //座席の色変更
      square1.firstChild.nodeValue ='空席';
    }
  })
  }

  if(numbox != null){
    numbox.addEventListener('submit', function(event) {
      //executePHP("state.php");
      //executePHP("passCode.php");

      event.preventDefault(); // フォームの通常の送信を停止  
      square1.firstChild.nodeValue ='予約中';
    
      var result = confirm('続けて予約しますか？');
        if( result ) {
          alert("予約が完了しました");
          popupWrapper.style.display = 'none'; // ポップアップの削除
        }else {
          alert("予約が完了しました");
          window.location.href = 'home.php'; // 画面の遷移;
      }
    })
    }

