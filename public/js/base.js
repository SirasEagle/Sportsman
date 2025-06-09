// public/js/base.js
document.addEventListener('DOMContentLoaded', function() {

      // Toggle visibility of the add exercise form
      document.getElementById('addUnitButton').addEventListener('click', function() {
         var form = document.getElementById('addUnitForm');
         form.style.display = form.style.display === 'none' ? 'block' : 'none';
      });

      // set visiblity of set1, set2, set3 on load depending on the selected initial exercise
      const exerciseSelect = document.getElementById('exerciseSelect');
      if (exerciseSelect) {
         var selectedOption = exerciseSelect.options[exerciseSelect.selectedIndex];
         var isSingleUnit = selectedOption.getAttribute('data-is-single-unit') === '0';
         document.getElementById('set1').style.display = isSingleUnit ? 'inline' : 'none';
         document.getElementById('set2').style.display = isSingleUnit ? 'inline' : 'none';
         document.getElementById('set3').style.display = isSingleUnit ? 'inline' : 'none';
         
         // change visibility of set1, set2, set3 and weight based on the selected exercise
         document.getElementById('exerciseSelect').addEventListener('change', function() {
            selectedOption = this.options[this.selectedIndex];
            var usesWeight = selectedOption.getAttribute('data-uses-weight') === '1';
            isSingleUnit = selectedOption.getAttribute('data-is-single-unit') === '0';
   
            document.getElementById('weight').style.display = usesWeight ? 'inline' : 'none';
            document.getElementById('set1').style.display = isSingleUnit ? 'inline' : 'none';
            document.getElementById('set2').style.display = isSingleUnit ? 'inline' : 'none';
            document.getElementById('set3').style.display = isSingleUnit ? 'inline' : 'none';
         });
      }

      // reveal form for adding a new unit in the workout view
      if (document.getElementById('unitForm')) {
         document.getElementById('unitForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('/unit/add-xhr', {
               method: 'POST',
               body: formData
            })
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                  if (document.getElementById('unitMessage')) {
                     document.getElementById('unitMessage').innerText = 'Unit hinzugefügt';
                     document.getElementById('unitMessage').style.display = 'block';
                  }
                  document.getElementById('unitForm').reset(); // Reset the form
                  location.reload(); // Reload the page to show the new unit
               } else {
                  console.error(data.error);
                  document.getElementById('unitMessage').innerText = data.error;
                  document.getElementById('unitMessage').style.display = 'block';
               }
            })
            .catch(error => console.error('Error:', error));
         });
      }
});

