// public/js/base.js
document.addEventListener('DOMContentLoaded', function() {

      // Toggle visibility of the add exercise form
      document.getElementById('addUnitButton').addEventListener('click', function() {
         var form = document.getElementById('addUnitForm');
         form.style.display = form.style.display === 'none' ? 'block' : 'none';
      });

      // set visiblity of set1, set2, set3 on load depending on the selected initial exercise
      var selectedOption = exerciseSelect.options[exerciseSelect.selectedIndex];
      var isSingleUnit = selectedOption.getAttribute('data-is-single-unit') === '0';
      document.getElementById('set1').style.display = isSingleUnit ? 'inline' : 'none';
      document.getElementById('set2').style.display = isSingleUnit ? 'inline' : 'none';
      document.getElementById('set3').style.display = isSingleUnit ? 'inline' : 'none';

      document.getElementById('exerciseSelect').addEventListener('change', function() {
         selectedOption = this.options[this.selectedIndex];
         var usesWeight = selectedOption.getAttribute('data-uses-weight') === '1';
         isSingleUnit = selectedOption.getAttribute('data-is-single-unit') === '0';

         // Toggle visibility of weight and sets based on selected exercise
         document.getElementById('weight').style.display = usesWeight ? 'inline' : 'none';
         document.getElementById('set1').style.display = isSingleUnit ? 'inline' : 'none';
         document.getElementById('set2').style.display = isSingleUnit ? 'inline' : 'none';
         document.getElementById('set3').style.display = isSingleUnit ? 'inline' : 'none';
      });
      document.getElementById('unitForm').addEventListener('submit', function(event) {
         event.preventDefault();
         var formData = new FormData(this);
         fetch('/unit/add-xhr', {
            method: 'POST',
            body: formData
         })
         .then(response => response.json())
         .then(data => {
            console.log(data);
            if (data.success) {
               console.log('Unit added successfully');
               document.getElementById('unitMessage').innerText = 'Unit hinzugefügt';
               document.getElementById('unitMessage').style.display = 'block';
               location.reload(); // Reload the page to show the new unit
            } else {
               console.error(data.error);
               document.getElementById('unitMessage').innerText = data.error;
               document.getElementById('unitMessage').style.display = 'block';
            }
         })
         .catch(error => console.error('Error:', error));
      });
});

