let chartColor = "#FFFFFF",
    charts = {}
;

$('.lineGraph').each(function() {
    let canvas = $(this),
        data = canvas.data('chart-data'),
        interval = parseInt(canvas.data('chart-point-interval')),
        ctx = this.getContext("2d"),
        gradientStroke = ctx.createLinearGradient(500, 0, 100, 0),
        gradientFill = ctx.createLinearGradient(0, 200, 0, 50),
        dataSets = []
    ;

    gradientStroke.addColorStop(0, '#80b6f4');
    gradientStroke.addColorStop(1, chartColor);

    gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
    gradientFill.addColorStop(1, "rgba(255, 255, 255, 0.24)");

    $.each(data, function (index, value) {
        if (!dataSets.length)
        {
            $.each(value['values'], function (index2, value2) {
                let color = (typeof value['colors'] === 'undefined' ? chartColor : value['colors'][index2]),
                    backgroundColor = (typeof value['backgroundColors'] === 'undefined' ? "#1e3d60" : value['backgroundColors'][index2])
                ;

                dataSets.push({
                    label: value['legends'][index2],
                    borderColor: color,
                    pointBorderColor: color,
                    pointBackgroundColor: backgroundColor,
                    pointHoverBackgroundColor: backgroundColor,
                    pointHoverBorderColor: color,
                    pointBorderWidth: 1,
                    pointHoverRadius: 7,
                    pointHoverBorderWidth: 2,
                    pointRadius: 5,
                    fill: true,
                    backgroundColor: gradientFill,
                    borderWidth: 2,
                    data: []
                });
            });
        }

        $.each(value['values'], function (index2, value2) {
            dataSets[index2].data.push(parseFloat(value2));
        });
    });

    charts[canvas.data('chart-id')] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: $.map(Object.keys(data), function(val) { return parseFloat(val) * 1000; }),
            datasets: dataSets
        },
        options: {
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: 0,
                    bottom: 0
                }
            },
            title: {
                display: true,
                text: canvas.data('chart-title'),
                fontSize: 18,
                fontColor: chartColor
            },
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: '#fff',
                titleFontColor: '#333',
                bodyFontColor: '#666',
                bodySpacing: 4,
                xPadding: 12,
                mode: "nearest",
                intersect: 0,
                position: "nearest",
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
            legend: {
                position: "bottom",
                fillStyle: "#FFF",
                display: true,
                labels: {
                    fontColor: chartColor
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        fontColor: "rgba(255,255,255,0.4)",
                        fontStyle: "bold",
                        beginAtZero: false,
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function callback(value, index, values) {
                            value = parseFloat(value);
                            value = (value < 1.0 ? value.toPrecision(8) : value.toLocaleString());

                            if (canvas.data('chart-series') === 'USD') {
                                return '$' + value;
                            } else {
                                return value;
                            }
                        }
                    },
                    gridLines: {
                        drawTicks: true,
                        drawBorder: false,
                        display: true,
                        color: "rgba(255,255,255,0.1)",
                        zeroLineColor: "transparent"
                    }

                }],
                xAxes: [{
                    type: 'time',
                    distribution: 'series',
                    gridLines: {
                        zeroLineColor: "transparent",
                        display: false,
                    },
                    ticks: {
                        padding: 10,
                        fontColor: "rgba(255,255,255,0.4)",
                        fontStyle: 'bold',
                        // source: 'labels'
                    },
                    time: {
                        tooltipFormat: ('MMMM Do YYYY' + (interval < 86400 ? ', h:mm:ss a' : '')),
                        unit: (interval === 86400 ? 'day' : 'minute')
                    }
                }]
            }
        }
    });
});

let buttons = $('.chart-update-buttons');
buttons.on('click', 'button', function()
{
    let self = $(this),
        chartId = self.data('chart-id'),
        timePeriod = self.data('time-period')
    ;
    if (self.hasClass('btn-primary'))
    {
        return;
    }

    buttons.find('button').each(function()
    {
        $(this).removeClass('btn-primary').addClass('btn-secondary');
    });

    self.removeClass('btn-secondary').addClass('btn-primary');

    $.get({
        url: 'graph-data',
        data: {
            chart: chartId,
            period: timePeriod
        },
        dataType: 'json',

        success: function(data) {
            if (data.error)
            {
                return;
            }

            if (charts[chartId])
            {
                let chart = charts[chartId];

                chart.data.datasets[0].data = $.map(data.data, parseFloat);
                chart.data.datasets[0].pointRadius = (timePeriod === 'all' ? 2 : 5);
                chart.data.labels = $.map(Object.keys(data.data), function(val) { return parseFloat(val) * 1000; });
                // if (timePeriod !== 'day')
                // {
                //     chart.options.scales.xAxes[0].time.tooltipFormat = 'MMMM Do YYYY';
                // }
                // else
                // {
                //     chart.options.scales.xAxes[0].time.tooltipFormat = 'MMMM Do YYYY';
                // }
                chart.update();
            }
        }
    })
});
