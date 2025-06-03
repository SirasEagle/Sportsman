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
                    label: exerciseName, // shows the name of the exercise in the chart legend
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
                    tooltips: {
                        // Custom tooltip to show average and sets for each data point (e.g. "Sit ups: 10 (8, 10, 12)")
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var idx = tooltipItem.index; // current index of the tooltip item
                                var avg = tooltipItem.yLabel; // comes from repData that gets used for y-axis-values
                                var sets = setValues[idx]; // setValues given in Twig
                                return exerciseName + ': ' + avg + ' (' + sets.join(', ') + ')';
                            }
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: { beginAtZero: true } // Start y-axis at zero
                        }]
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
                        yAxes: [{
                            ticks: { beginAtZero: true }
                        }]
                    }
                }
            };
            new Chart(weightCtx, weightConfig);
        }
    }
});