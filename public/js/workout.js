// TODO: von workout/index.html/twig
document.addEventListener('DOMContentLoaded', function() {
   const tab1 = document.getElementById('tab1');
   const tab2 = document.getElementById('tab2');
   const tab1Content = document.querySelector('.tab1-content');
   const tab2Content = document.querySelector('.tab2-content');

   tab1.addEventListener('click', () => {
      tab1Content.style.display = 'block';
      tab2Content.style.display = 'none';
      tab1.classList.add('active-tab');
      tab2.classList.remove('active-tab');
   });

   tab2.addEventListener('click', () => {
      tab1Content.style.display = 'none';
      tab2Content.style.display = 'block';
      tab1.classList.remove('active-tab');
      tab2.classList.add('active-tab');
   });
});