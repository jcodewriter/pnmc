var chartColor = "#00d6b4";

$('.lineGraph').each(function()
{
    let canvas = $(this),
        interval = parseInt(canvas.data('chart-point-interval')),
        data = canvas.data('chart-data'),
        ctx = this.getContext("2d"),
        gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
    ;

    var gradientChartOptionsConfiguration = {
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        tooltips: {
            bodySpacing: 4,
            mode: "nearest",
            intersect: 0,
            position: "nearest",
            xPadding: 10,
            yPadding: 10,
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, data)
                {
                    let yLabel = parseFloat(tooltipItem.yLabel);

                    yLabel = yLabel.toLocaleString(undefined, {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: yLabel < 1.00 ? 8 : 2
                    });

                    var label = data.datasets[tooltipItem.datasetIndex].label || '';

                    if (label)
                    {
                        label += ': ';
                    }
                    if (canvas.data('chart-series') === 'USD')
                    {
                        label += '$';
                    }

                    label += yLabel;
                    return label;
                }
            }
        },
        responsive: true,
        scales: {
            yAxes: [{
                display: false,
                ticks: {
                    display: false
                },
                gridLines: {
                    zeroLineColor: "transparent",
                    drawTicks: false,
                    display: false,
                    drawBorder: false
                }
            }],
            xAxes: [{
                type: 'time',
                display: false,
                ticks: {
                    display: false
                },
                gridLines: {
                    zeroLineColor: "transparent",
                    drawTicks: false,
                    display: false,
                    drawBorder: false
                },
                time: {
                    tooltipFormat: ('MMMM Do YYYY' + (interval < 86400 ? ', h:mm:ss a' : '')),
                    unit: (interval === 86400 ? 'day' : 'minute')
                }
            }]
        },
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 15,
                bottom: 15
            }
        }
    };

    gradientFill.addColorStop(0, 'rgba(66, 134, 121, 0.2)');
    gradientFill.addColorStop(0.5, 'rgba(66, 134, 121, 0.2)');
    gradientFill.addColorStop(1, 'rgba(66, 134, 121, 0.2)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: $.map(Object.keys(data), function(val) { return parseFloat(val) * 1000; }),
            datasets: [{
                label: canvas.data('chart-series'),
                data: $.map(data, parseFloat),
                backgroundColor: gradientFill,
                borderColor: chartColor,
                pointBackgroundColor: chartColor,
                pointBorderColor: 'rgba(255,255,255,0)',
                pointHoverBackgroundColor: chartColor,
                pointBorderWidth: 2,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 1,
                pointRadius: 2,
                fill: true,
                borderWidth: 2
            }]
        },
        options: gradientChartOptionsConfiguration
    });
});
