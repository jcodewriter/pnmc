let buttons = $('.chart-update-buttons'),
    overlayButtons = $('.chart-overlay-buttons')
;
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

    overlayButtons.find('button.btn-primary').trigger('click');

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

                $('.chart-overlay-buttons > button').trigger('click');
            }
        }
    })
});

overlayButtons.on('click', 'button', function()
{
    let self = $(this),
        chartId = self.data('chart-id'),
        dataSource = self.data('overlay-source')
    ;

    if (self.hasClass('btn-primary'))
    {
        self.removeClass('btn-primary').addClass('btn-secondary');
        return;
    }

    overlayButtons.find('button').each(function()
    {
        $(this).removeClass('btn-primary').addClass('btn-secondary');
    });

    self.removeClass('btn-secondary').addClass('btn-primary');

    $.get({
        url: 'graph-data',
        data: {
            chart: dataSource,
            period: $('.chart-update-buttons .btn-primary').data('time-period')
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

                if (typeof chart.data.datasets[1] === 'undefined') {
                    let buttonColor = self.data('chart-color');
                    chart.data.datasets[1] = {
                        label: self.html(),
                        data: $.map(data.data, parseFloat),
                        fill: false,
                        // backgroundColor: gradientFill,
                        borderColor: buttonColor ? buttonColor : '#f96332',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        pointBackgroundColor: buttonColor ? buttonColor : '#f96332',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        pointHoverBackgroundColor: buttonColor ? buttonColor : '#f96332',
                        pointBorderWidth: 20,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 15,
                        pointRadius: 4,
                        hidden: true
                    };
                }
                else {
                    chart.data.datasets[1].data = $.map(data.data, parseFloat);
                }
                // chart.data.datasets[0].fill = false;

                chart.update();
            }
        }
    })
});

let chartColor = "#00d6b4",
    charts = {}
;

$('.lineGraph').each(function () {
    let canvas = $(this),
        data = canvas.data('chart-data'),
        ctx = this.getContext("2d"),
        gradientFill = ctx.createLinearGradient(0, 170, 0, 50);

    let gradientChartOptionsConfiguration = {
        title: {
            display: typeof canvas.data('chart-title') !== 'undefined',
            text: canvas.data('chart-title'),
            fontSize: 18,
            fontColor: chartColor
        },
        maintainAspectRatio: false,
        legend: {
            position: "bottom",
            fillStyle: "#FFF",
            display: true
        },
        tooltips: {
            backgroundColor: '#f5f5f5',
            titleFontColor: '#333',
            bodyFontColor: '#666',
            bodySpacing: 4,
            mode: "nearest",
            intersect: 0,
            position: "nearest",
            xPadding: 10,
            yPadding: 10,
            caretPadding: 10,
            callbacks: {
                label: function label(tooltipItem, data) {
                    var yLabel = parseFloat(tooltipItem.yLabel);
                    yLabel = yLabel.toLocaleString(undefined, {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: yLabel < 1.00 ? 8 : 2
                    });
                    var label = data.datasets[tooltipItem.datasetIndex].label || '';

                    if (label) {
                        label += ': ';
                    }

                    if (canvas.data('chart-series') === 'USD') {
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
                ticks: {
                    fontColor: "#9a9a9a",
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
                    color: "rgba(29,140,248,0.0)",
                    zeroLineColor: "transparent"
                }
            }],
            xAxes: [{
                type: 'time',
                distribution: 'series',
                gridLines: {
                    zeroLineColor: "transparent",
                    color: 'rgba(225,78,202,0.1)',
                    display: false
                },
                ticks: {
                    padding: 10,
                    fontColor: "#9a9a9a",
                    fontStyle: 'bold',
                    display: false,
                    // source: 'labels'
                },
                time: {
                    tooltipFormat: 'MMMM Do YYYY, h:mm:ss a',
                    unit: 'minute'
                }
            }]
        },
        layout: {
            padding: {
                left: 0,
                right: 4,
                top: 15,
                bottom: 0
            }
        },
        // onResize: function (chart, newSize)
        // {
        //     chart.options.scales.xAxes[0].ticks.display = newSize.width > 720;
        //     chart.update();
        // }
    };

    gradientFill.addColorStop(0, 'rgba(66, 134, 121, 0.2)');
    gradientFill.addColorStop(0.5, 'rgba(66, 134, 121, 0.2)');
    gradientFill.addColorStop(1, 'rgba(66, 134, 121, 0.2)');

    // if (!window.matchMedia('(min-width: 820px)').matches)
    // {
    //     chart.options.scales.xAxes[0].ticks.display = false;
    //     chart.update();
    // }

    let canvasColor = canvas.data('chart-color');
    charts[canvas.data('chart-id')] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: $.map(Object.keys(data), function (val) {
                return parseFloat(val) * 1000;
            }),
            datasets: [{
                label: canvas.data('chart-label'),
                data: $.map(data, parseFloat),
                fill: false,
                // backgroundColor: gradientFill,
                borderColor: canvasColor ? canvasColor : chartColor,
                borderWidth: 2,
                borderDash: [],
                borderDashOffset: 0.0,
                pointBackgroundColor: canvasColor ? canvasColor : chartColor,
                pointBorderColor: 'rgba(255,255,255,0)',
                pointHoverBackgroundColor: canvasColor ? canvasColor : chartColor,
                pointBorderWidth: 20,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 15,
                pointRadius: 4
            }]
        },
        options: gradientChartOptionsConfiguration
    });

    $('.chart-overlay-buttons > button').trigger('click');
});
