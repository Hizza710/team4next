// www/assets/js/app.js
document.addEventListener('DOMContentLoaded', () => {
    console.log('TEAM for NEXT loaded.');
    
    // フェードイン要素への一括処理など
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach(el => {
        el.style.opacity = '1';
    });
});