
// テキストボックスのDOMを取得
  const username = document.getElementById("username");
  const username2 = document.getElementById("username2");

  const button = document.getElementById("button");
  const button3 = document.getElementById("button3");

  

  // testbutton.addEventListener('click', () => {
  // document.getElementById('textbox').addEventListener('submit', function(event) {
    
  //   event.preventDefault(); // フォームの通常の送信を停止
  //   const text = username.value;
  //   console.log(text);

  //   if(text) {

  //     // 入力フォームの内容を取得
  //     let inputForm = document.getElementById('textbox').username.value;
  //     // 入力内容を画面に出力
  //     document.getElementById('output').textContent = `${inputForm}`;
  //     window.location.href = 'yoyaku.html'; // 画面の遷移

  //   } else {

  //     alert('エラー:氏名を入力してください');
  //   }
    
  // })

  // document.getElementById('textbox2').addEventListener('submit', function(event) {
    
  //   event.preventDefault(); // フォームの通常の送信を停止
  //   const text2 = username2.value;
  //   console.log(text2);

  //   if(text2) {

  //     // 入力フォームの内容を取得
  //     let inputForm = document.getElementById('textbox2').username2.value;
  //     // 入力内容を画面に出力
  //     document.getElementById('output').textContent = `${inputForm}`;

  //     window.location.href = 'cancel.php'; // 画面の遷移

  //   } else {

  //     alert('エラー:氏名を入力してください');
  //   }
    
  // })


  function executePHP(phpname) { // javascriptからphpを開く
    fetch(phpname, { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
  }




  