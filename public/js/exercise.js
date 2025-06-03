document.addEventListener('DOMContentLoaded', function () {

    // initialize charts for displaying exercise data-------------------------------------------------------------------------

    // rep chart
    const repGraph = document.getElementById('repGraph');
    // check if canvas elements exist before initializing chart
    if (repGraph) {
        const ctx = repGraph.getContext('2d');
        // Die folgenden Variablen werden im Twig als globale JS-Variablen gesetzt!
        if (typeof exerciseLabels !== 'undefined' && typeof repData !== 'undefined' && typeof exerciseName !== 'undefined') {
            const data = {
                labels: exerciseLabels,
                datasets: [{
                    label: exerciseName,
                    data: repData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }]
            };
            const config = {
                type: 'line',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };
            new Chart(ctx, config);
        }
    }

    // weight chart
    const weightGraph = document.getElementById('weightGraph');
    // check if canvas elements exist before initializing chart
    if (weightGraph) {
        const weightCtx = weightGraph.getContext('2d');
        if (typeof exerciseLabels !== 'undefined' && typeof weightData !== 'undefined' && typeof exerciseName !== 'undefined') {
            const weightChartData = {
                labels: exerciseLabels,
                datasets: [{
                    label: exerciseName + ' - Gewicht',
                    data: weightData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true
                }]
            };
            const weightConfig = {
                type: 'line',
                data: weightChartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };
            new Chart(weightCtx, weightConfig);
        }
    }
});