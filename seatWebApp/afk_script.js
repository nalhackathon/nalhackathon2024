// let afkTex = document.getElementById('seatAfk');
// let tex = document.getElementById('square3');

// function afk(){
//     if(tex.textContent === '離席中'){
       
//       tex.style.backgroundColor = 'yellow';
//       afkTex.value = '離席解除'; //ボタン文字変更

//     //   square3.firstChild.nodeValue ='離席中'; // firstChildは親ブロックの一番目の要素を指定
//     }else{
//       tex.style.backgroundColor = '#eb2052';
//       afkTex.value = '離席する'; //文字変更

//     //   square3.firstChild.nodeValue ='利用中';
//     }
//   }
const afkbtn1 = document.getElementById('afkbtn1');
//座席2---------------------------------------------------------------------------------------
if(afkbtn1 != null){
  afkbtn1.addEventListener('click',function(){
  document.getElementById('square1').style.backgroundColor = 'yellow'; //座席の色変更
  square1.firstChild.nodeValue ='離席中';
})
}


