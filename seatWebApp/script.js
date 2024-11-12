
// テキストボックスのDOMを取得
const num = document.getElementById("num");
const username2 = document.getElementById("username2");

const button = document.getElementById("button");
const button2 = document.getElementById("button2");

// testbutton.addEventListener('click', () => {
document.getElementById('numbox').addEventListener('submit', function(event) {
  
  event.preventDefault(); // フォームの通常の送信を停止
  const text = num.value;
  console.log(text);

  if(text) {
    window.location.href = 'home.html'; // 画面の遷移

  } else {

    alert('エラー');
  }

})