document.addEventListener('DOMContentLoaded', function () {
  // 在DOM加載完畢後執行以下代碼
  setTimeout(function () {
    // 移除過場動畫的overlay
    document.querySelector('.overlay').style.display = 'none';
  }, 1000); 
});
