// 予約用---------------------------------------------------------------------------------------
// 座席2
const clickBtn2 = document.getElementById('click-btn2');
const popupWrapper2 = document.getElementById('popup-wrapper2');
const close2 = document.getElementById('close2');
const sq2btn = document.getElementById('sq2btn');
// 座席3
const clickBtn3 = document.getElementById('click-btn3');
const popupWrapper3 = document.getElementById('popup-wrapper3');
const close3 = document.getElementById('close3');
const sq3btn = document.getElementById('sq3btn');
// 座席4
const clickBtn4 = document.getElementById('click-btn4');
const popupWrapper4 = document.getElementById('popup-wrapper4');
const close4 = document.getElementById('close4');
const sq4btn = document.getElementById('sq4btn');
// 座席5
const clickBtn5 = document.getElementById('click-btn5');
const popupWrapper5 = document.getElementById('popup-wrapper5');
const close5 = document.getElementById('close5');
const sq5btn = document.getElementById('sq5btn');
// 座席6
const clickBtn66 = document.getElementById('click-btn66');
const popupWrapper66 = document.getElementById('popup-wrapper66');
const close66 = document.getElementById('close66');
const sq6btn = document.getElementById('sq6btn');

// キャンセル用-------------------------------------------------------------------------------

// 座席1
const clickBtn1 = document.getElementById('click-btn1');
const popupWrapper1 = document.getElementById('popup-wrapper1');
const close1 = document.getElementById('close1');
const sq1btn_c = document.getElementById('sq1btn_c');

// 座席2
const clickBtn2c = document.getElementById('click-btn2c');
const popupWrapper2c = document.getElementById('popup-wrapper2c');
const close2c = document.getElementById('close2c');
const sq2btn_c = document.getElementById('sq2btn_c');

// 座席3
const clickBtn3c = document.getElementById('click-btn3c');
const popupWrapper3c = document.getElementById('popup-wrapper3c');
const close3c = document.getElementById('close3c');
const sq3btn_c = document.getElementById('sq3btn_c');

// 座席4
const clickBtn4c = document.getElementById('click-btn4c');
const popupWrapper4c = document.getElementById('popup-wrapper4c');
const close4c = document.getElementById('close4c');
const sq4btn_c = document.getElementById('sq4btn_c');

// 座席5
const clickBtn5c = document.getElementById('click-btn5c');
const popupWrapper5c = document.getElementById('popup-wrapper5c');
const close5c = document.getElementById('close5c');
const sq5btn_c = document.getElementById('sq5btn_c');

// 座席6
const clickBtn6c = document.getElementById('click-btn6c');
const popupWrapper6c = document.getElementById('popup-wrapper6c');
const close6c = document.getElementById('close6c');
const sq6btn_c = document.getElementById('sq6btn_c');


//--------------------------------------------------------------------------------------------
//座席2---------------------------------------------------------------------------------------
if(sq2btn != null){
    sq2btn.addEventListener('click',function(){
    document.getElementById('square2').style.backgroundColor = 'purple'; //座席の色変更
    square2.firstChild.nodeValue ='予約中';
    popupWrapper2.style.display = "block"; //ポップアップの表示
  })
}

if(popupWrapper2 != null){
// ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper2.addEventListener('click', e => {
if (e.target.id === popupWrapper2.id || e.target.id === close2.id) {
  popupWrapper2.style.display = 'none'; // ポップアップの削除
  document.getElementById('square2').style.backgroundColor = ' #59b8f3'; //座席の色変更
  square2.firstChild.nodeValue ='空席';
    }
  })
}

//座席3---------------------------------------------------------------------------------------
if(sq3btn != null){
sq3btn.addEventListener('click',function(){
  document.getElementById('square3').style.backgroundColor = 'purple'; //座席の色変更
  square3.firstChild.nodeValue ='予約中';
  popupWrapper3.style.display = "block"; //ポップアップの表示

})
}

if(popupWrapper3 != null){
// ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper3.addEventListener('click', e => {
if (e.target.id === popupWrapper3.id || e.target.id === close3.id) {
popupWrapper3.style.display = 'none'; // ポップアップの削除
document.getElementById('square3').style.backgroundColor = ' #59b8f3'; //座席の色変更
square3.firstChild.nodeValue ='空席';
  }
})
}

//座席4---------------------------------------------------------------------------------------
if(sq4btn != null){
sq4btn.addEventListener('click',function(){
  document.getElementById('square4').style.backgroundColor = 'purple'; //座席の色変更
  square4.firstChild.nodeValue ='予約中';
  popupWrapper4.style.display = "block"; //ポップアップの表示

})
}

if(popupWrapper4 != null){
// ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper4.addEventListener('click', e => {
if (e.target.id === popupWrapper4.id || e.target.id === close4.id) {
popupWrapper4.style.display = 'none'; // ポップアップの削除
document.getElementById('square4').style.backgroundColor = ' #59b8f3'; //座席の色変更
square4.firstChild.nodeValue ='空席';
  }
})
}

//座席5---------------------------------------------------------------------------------------
if(sq5btn != null){
sq5btn.addEventListener('click',function(){
  document.getElementById('square5').style.backgroundColor = 'purple'; //座席の色変更
  square5.firstChild.nodeValue ='予約中';
  popupWrapper5.style.display = "block"; //ポップアップの表示

})
}

if(popupWrapper5 != null){
// ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper5.addEventListener('click', e => {
if (e.target.id === popupWrapper5.id || e.target.id === close5.id) {
popupWrapper5.style.display = 'none'; // ポップアップの削除
document.getElementById('square5').style.backgroundColor = ' #59b8f3'; //座席の色変更
square5.firstChild.nodeValue ='空席';
  }
})
}

//座席6---------------------------------------------------------------------------------------
if(sq6btn != null){
sq6btn.addEventListener('click',function(){
  document.getElementById('square6').style.backgroundColor = 'purple'; //座席の色変更
  square6.firstChild.nodeValue ='予約中';
  popupWrapper66.style.display = "block"; //ポップアップの表示

})
}
if(popupWrapper66 != null){
// ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper66.addEventListener('click', e => {
if (e.target.id === popupWrapper66.id || e.target.id === close66.id) {
popupWrapper66.style.display = 'none'; // ポップアップの削除
document.getElementById('square6').style.backgroundColor = ' #59b8f3'; //座席の色変更
square6.firstChild.nodeValue ='空席';
  }
})
}

// キャンセル用-------------------------------------------------------------------------------
//座席1---------------------------------------------------------------------------------------
if(sq1btn_c != null){
  sq1btn_c.addEventListener('click',function(){
  document.getElementById('square1').style.backgroundColor = 'yellow'; //座席の色変更
  square1.firstChild.nodeValue ='利用中';
  popupWrapper1.style.display = "block"; //ポップアップの表示
  })
}

if(popupWrapper1 != null){

  // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper1.addEventListener('click', e => {
  if (e.target.id === popupWrapper1.id || e.target.id === close1.id) {
  popupWrapper1.style.display = 'none'; // ポップアップの削除
  document.getElementById('square1').style.backgroundColor = ' #eb2052'; //座席の色変更
  square1.firstChild.nodeValue ='利用中';
}
});
}

//座席2---------------------------------------------------------------------------------------
if(sq2btn_c != null){
  sq2btn_c.addEventListener('click',function(){
  document.getElementById('square2').style.backgroundColor = 'yellow'; //座席の色変更
  square2.firstChild.nodeValue ='利用中';
  popupWrapper2c.style.display = "block"; //ポップアップの表示
  })
}

if(popupWrapper2c != null){

  // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper2c.addEventListener('click', e => {
  if (e.target.id === popupWrapper2c.id || e.target.id === close2c.id) {
  popupWrapper2c.style.display = 'none'; // ポップアップの削除
  document.getElementById('square2').style.backgroundColor = ' #eb2052'; //座席の色変更
  square2.firstChild.nodeValue ='利用中';
}
});
}

//座席3---------------------------------------------------------------------------------------
if(sq3btn_c != null){
  sq3btn_c.addEventListener('click',function(){
  document.getElementById('square3').style.backgroundColor = 'yellow'; //座席の色変更
  square3.firstChild.nodeValue ='利用中';
  popupWrapper3c.style.display = "block"; //ポップアップの表示
  })
}

if(popupWrapper3c != null){

  // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper3c.addEventListener('click', e => {
  if (e.target.id === popupWrapper3c.id || e.target.id === close3c.id) {
  popupWrapper3c.style.display = 'none'; // ポップアップの削除
  document.getElementById('square3').style.backgroundColor = ' #eb2052'; //座席の色変更
  square3.firstChild.nodeValue ='利用中';
}
});
}

//座席4---------------------------------------------------------------------------------------
if(sq4btn_c != null){
  sq4btn_c.addEventListener('click',function(){
  document.getElementById('square4').style.backgroundColor = 'yellow'; //座席の色変更
  square4.firstChild.nodeValue ='利用中';
  popupWrapper4c.style.display = "block"; //ポップアップの表示
  })
}

if(popupWrapper4c != null){

  // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper4c.addEventListener('click', e => {
  if (e.target.id === popupWrapper4c.id || e.target.id === close4c.id) {
  popupWrapper4c.style.display = 'none'; // ポップアップの削除
  document.getElementById('square4').style.backgroundColor = ' #eb2052'; //座席の色変更
  square4.firstChild.nodeValue ='利用中';
}
});
}

//座席5---------------------------------------------------------------------------------------
if(sq5btn_c != null){
  sq5btn_c.addEventListener('click',function(){
  document.getElementById('square5').style.backgroundColor = 'yellow'; //座席の色変更
  square5.firstChild.nodeValue ='利用中';
  popupWrapper5c.style.display = "block"; //ポップアップの表示
  })
}

if(popupWrapper5c != null){

  // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper5c.addEventListener('click', e => {
  if (e.target.id === popupWrapper5c.id || e.target.id === close5c.id) {
  popupWrapper5c.style.display = 'none'; // ポップアップの削除
  document.getElementById('square5').style.backgroundColor = ' #eb2052'; //座席の色変更
  square5.firstChild.nodeValue ='利用中';
}
});
}

//座席6---------------------------------------------------------------------------------------
if(sq6btn_c != null){
  sq5btn_c.addEventListener('click',function(){
  document.getElementById('square6').style.backgroundColor = 'yellow'; //座席の色変更
  square6.firstChild.nodeValue ='利用中';
  popupWrapper6c.style.display = "block"; //ポップアップの表示
  })
}

if(popupWrapper6c != null){

  // ポップアップの外側又は「x」のマークをクリックしたときポップアップを閉じる
popupWrapper6c.addEventListener('click', e => {
  if (e.target.id === popupWrapper6c.id || e.target.id === close6c.id) {
  popupWrapper6c.style.display = 'none'; // ポップアップの削除
  document.getElementById('square6').style.backgroundColor = ' #eb2052'; //座席の色変更
  square6.firstChild.nodeValue ='利用中';
}
});
}