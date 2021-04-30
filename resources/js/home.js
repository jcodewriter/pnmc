const Highcharts = require('highcharts');
require('highcharts/modules/boost')(Highcharts);

Highcharts.SparkLine = function (a, b, c) {
    let hasRenderToArg = typeof a === 'string' || a.nodeName,
        options = arguments[hasRenderToArg ? 1 : 0],
        defaultOptions = {
            chart: {
                renderTo: (options.chart && options.chart.renderTo) || this,
                backgroundColor: null,
                borderWidth: 0,
                type: 'area',
                margin: [2, 0, 2, 0],
                width: 150,
                height: 20,
                style: {
                    overflow: 'visible'
                },

                // small optimalization, saves 1-2 ms each sparkline
                skipClone: true
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            xAxis: {
                visible: false
            },
            yAxis: {
                endOnTick: false,
                startOnTick: false,
                gridLineColor: '',
                labels: {
                    enabled: false
                },
                title: {
                    text: null
                },
                tickPositions: [0]
            },
            legend: {
                enabled: false
            },
            tooltip: {
                enabled: false
            },
            boost: {
                useGPUTranslations: true
            },
            plotOptions: {
                series: {
                    animation: false,
                    lineWidth: 2,
                    shadow: false,
                    states: {
                        hover: {
                            enabled: false
                        }
                    },
                    marker: {
                        radius: 1,
                        states: {
                            hover: {
                                radius: 2
                            }
                        }
                    },
                    fillOpacity: 0
                }
            }
        };

    options = Highcharts.merge(defaultOptions, options);

    return hasRenderToArg ?
        new Highcharts.Chart(a, options, c) :
        new Highcharts.Chart(options, b);
};

Highcharts.setOptions({
    credits: {
        enabled: false
    },
    lang: {
        thousandsSep: ',',
        decimalPoint: '.'
    }
});

$('.lineGraph').each(function()
{
    let canvas = $(this),
        start = parseInt(canvas.data('chart-point-start')),
        interval = parseInt(canvas.data('chart-point-interval'))
    ;

    Highcharts.chart({
        chart: {
            renderTo: this
        },
        title: {
            text: canvas.data('chart-title')
        },
        xAxis: {
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        labels: {
            formatter: function() {
                return Highcharts.numberFormat(this.value, 2);
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        tooltip: {
            xDateFormat: '%A, %b %e' + (interval < 86400 ? ' %H:%M' : ''),
            pointFormatter: function() {
                if (canvas.data('chart-series') === 'USD')
                {
                    return '<b>$' + Highcharts.numberFormat(this.y, this.y < 1 ? 8 : 2) + ' <br/>';
                }
                else
                {
                    return '<b>' + Highcharts.numberFormat(this.y, 0) + ' <br/>';
                }
            },
            shared: true
        },
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: (start * 1000),
                pointInterval: (interval * 1000)
            }
        },
        series: [{
            name: canvas.data('chart-series'),
            data: $.map(canvas.data('chart-data'), parseFloat)
        }]
    });
});

/*
$('[data-toggle="popover"]').popover({
    content: function ()
    {
        return '';
    }
}).on('inserted.bs.popover', function () {
    let self = $(this),
        popover = $('#' + self.attr('aria-describedby')),
        data = $.map(self.data('sparkline-content'), parseFloat),
        chart = {},
        minValue = data ? Math.min.apply(null, data) : 0,
        maxValue = data ? Math.max.apply(null, data) : 0
    ;

    popover.find('.popover-body').highcharts('SparkLine', {
        yAxis: {
            floor: minValue,
            ceiling: maxValue
        },
        series: [{
            data: data,
            pointStart: 1,
            marker: {
                enabled: false
            }
        }],
        chart: chart
    });
});
 */

let start = +new Date(),
    $tds = $('td[data-sparkline]'),
    n = 0;

function doChunk()
{
    let time = +new Date(),
        i,
        len = $tds.length,
        $td,
        data,
        chart;

    for (i = 0; i < len; i += 1)
    {
        $td = $($tds[i]);
        data = $.map($td.data('sparkline'), parseFloat);
        chart = {};

        let minValue = data ? Math.min.apply(null, data) : 0,
            maxValue = data ? Math.max.apply(null, data) : 0
        ;

        $td.highcharts('SparkLine', {
            yAxis: {
                floor: minValue,
                ceiling: maxValue
            },
            series: [{
                data: data,
                pointStart: 1,
                marker: {
                    enabled: false
                }
            }],
            chart: chart
        });

        n += 1;

        // If the process takes too much time, run a timeout to allow interaction with the browser
        if (new Date() - time > 500)
        {
            $tds.splice(0, i + 1);
            setTimeout(doChunk, 0);
            break;
        }
    }
}

doChunk();

/*
jQuery.fn.extend({
    parseDate: function()
    {
        return this.each(function()
        {
            let self = $(this),
                now = new Date(),
                countFrom = (new Date(self.data('timestamp') * 1000)).getTime(),
                timeDifference = (now - countFrom),
                secondsInADay = 60 * 60 * 1000 * 24,
                secondsInAHour = 60 * 60 * 1000,
                days = Math.floor(timeDifference / (secondsInADay)),
                hours = Math.floor((timeDifference % (secondsInADay)) / (secondsInAHour)),
                minutes = Math.floor(((timeDifference % (secondsInADay)) % (secondsInAHour)) / (60 * 1000)),
                seconds = Math.floor((((timeDifference % (secondsInADay)) % (secondsInAHour)) % (60 * 1000)) / 1000)
            ;

            self.show();
            self.find('.days').text(days);
            self.find('.hours').text(hours);
            self.find('.minutes').text(minutes);
            self.find('.seconds').text(seconds);
        });
    }
});

$('.count-up').parseDate();

setInterval(function()
{
    $('.count-up').parseDate();
}, 1000);
 */

require('./dataTables');
