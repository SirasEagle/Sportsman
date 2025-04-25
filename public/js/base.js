// public/js/base.js
document.addEventListener('DOMContentLoaded', function() {
   const showTableButton = document.getElementById('showTableButton');
   const hiddenArea = document.getElementById('hiddenArea');
   const saveUnitToExercise = document.getElementById('saveUnitToExercise');
   const set1Input = document.getElementById('set1Input');
   const set2Input = document.getElementById('set2Input');
   const set3Input = document.getElementById('set3Input');
   const exerciseId = document.getElementById('exerciseOption');
   const weightCell = document.getElementById('weightCell');
   const weightInput = document.getElementById('weightInput');
   const workoutId = document.getElementById('workoutId');
   const unitInfo = document.getElementById('unitInfo');

   showTableButton.addEventListener('click', function() {
       if (hiddenArea.style.display === 'none') {
           hiddenArea.style.display = 'table';
           showTableButton.textContent = "-";
       } else {
           hiddenArea.style.display = 'none';
           showTableButton.textContent = "+";
       }
   });

    exerciseId.addEventListener('change', function() {
        const selected = exerciseId.selectedOptions[0];
        const usesWeight = selected.dataset.usesWeight === '1';
        if (usesWeight) {
            weightCell.style.display = '';   // wieder einblenden
        } else {
            weightCell.style.display = 'none';
            weightInput.value = '0.0kg';     // Reset
        }
    });

    saveUnitToExercise.addEventListener('click', function() {
        if ((set1Input.value < 0) || (set2Input.value < 0) || (set3Input.value < 0)) {
            alert("Geben Sie eine positive Zahl ein");
        } else {
            // AJAX-Anfrage senden
            $.ajax({
                url: '/save-unit', // Die URL zu deinem Symfony-Controller
                method: 'POST',
                data: {
                    set1: set1Input.value,
                    set2: set2Input.value,
                    set3: set3Input.value,
                    exId: exerciseId.value,
                    wId: workoutId.value,
                    unW: weightInput.value,
                    unitInfo: unitInfo.value
                },
                success: function(response) {
                    // Erfolgreiche Antwort vom Server, hier kannst du entsprechende Aktionen ausführen
                    set1Input.value = '0';
                    set2Input.value = '0';
                    set3Input.value = '0';
                    unitInfo.value = '';
                    location.reload(); // Beispiel: Seite neu laden
                },
                error: function(xhr, status, error) {
                    // Bei einem Fehler kannst du hier entsprechende Fehlerbehandlung vornehmen
                    alert('Fehler beim Speichern der Daten.' + error);
                }
            });
        }
    });
});

