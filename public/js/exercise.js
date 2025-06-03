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
                                return exerciseName + ': ' + avg.toFixed(2) + ' (' + sets.join(', ') + ')';
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

    // both charts combined in one graph
    const combinedGraph = document.getElementById('combinedGraph');
// check if canvas elements exist before initializing chart
if (combinedGraph) {
    const combinedCtx = combinedGraph.getContext('2d');
    if (
        typeof exerciseLabels !== 'undefined' &&
        typeof repData !== 'undefined' &&
        typeof weightData !== 'undefined' &&
        typeof exerciseName !== 'undefined'
    ) {
        const combinedData = {
            labels: exerciseLabels,
            datasets: [
                {
                    label: exerciseName + ' - Wiederholungen',
                    data: repData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    yAxisID: 'y-reps'
                },
                {
                    label: exerciseName + ' - Gewicht',
                    data: weightData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                    yAxisID: 'y-weight'
                }
            ]
        };
        const combinedConfig = {
            type: 'line',
            data: combinedData,
            options: {
                // Custom tooltip to show average reps and sets for each data point
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            // Prüfe, ob es die "Wiederholungen"-Datenreihe ist (yAxisID: 'y-reps')
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            if (dataset.yAxisID === 'y-reps') {
                                var idx = tooltipItem.index;
                                var avgReps = repData[idx];
                                var sets = setValues[idx];
                                return exerciseName + ': ' + avgReps.toFixed(2) + ' Wiederholungen (' + sets.join(', ') + ')';
                            } else {
                                // Standard-Tooltip für Gewicht
                                return dataset.label + ': ' + tooltipItem.yLabel + ' kg';
                            }
                        }
                    }
                },
                scales: {
                    yAxes: [
                        {
                            id: 'y-reps',
                            type: 'linear',
                            position: 'left',
                            ticks: { beginAtZero: true },
                            scaleLabel: {
                                display: true,
                                labelString: 'Wiederholungen'
                            }
                        },
                        {
                            id: 'y-weight',
                            type: 'linear',
                            position: 'right',
                            ticks: { beginAtZero: true },
                            scaleLabel: {
                                display: true,
                                labelString: 'Gewicht'
                            },
                            gridLines: {
                                drawOnChartArea: false // verhindert Überlagerung der Gitterlinien
                            }
                        }
                    ]
                }
            }
        };
        new Chart(combinedCtx, combinedConfig);
    }
}
});