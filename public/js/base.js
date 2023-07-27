// public/js/base.js
document.addEventListener('DOMContentLoaded', function() {
   const showTableButton = document.getElementById('showTableButton');
   const myTable = document.getElementById('myTable');
   const reloadButton = document.getElementById('reloadButton');

   showTableButton.addEventListener('click', function() {
       if (myTable.style.display === 'none') {
           myTable.style.display = 'table';
       } else {
           myTable.style.display = 'none';
       }
   });

   reloadButton.addEventListener('click', function() {
       location.reload();
   });
});

