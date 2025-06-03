// public/js/base.js
document.addEventListener('DOMContentLoaded', function() {

      // Toggle visibility of the add exercise form
      document.getElementById('addUnitButton').addEventListener('click', function() {
         var form = document.getElementById('addUnitForm');
         form.style.display = form.style.display === 'none' ? 'block' : 'none';
      });

      document.getElementById('exerciseSelect').addEventListener('change', function() {
         var selectedOption = this.options[this.selectedIndex];
         var usesWeight = selectedOption.getAttribute('data-uses-weight') === '1';
         document.getElementById('weight').style.display = usesWeight ? 'inline' : 'none';
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

