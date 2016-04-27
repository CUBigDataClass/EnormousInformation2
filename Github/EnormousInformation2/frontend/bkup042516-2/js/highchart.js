var LOCATION = 'Boulder';
var CATEGORY = 'restaurants';

$(function () {

    function buildChart(newSeries){

        $('#graph').highcharts({
            chart: {
                type: 'area',
                backgroundColor: '#212121'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Sunday',
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday'
                ],
                crosshair: true
            },
            yAxis: {
                visible: false,
                title: {
                    text: ''
                }
            },
            /*tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },*/
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: newSeries
        });

    }

    $('#categoryNav li').on('click', function(e){
        $(this).addClass('active').siblings().removeClass('active');
        CATEGORY = $(this).data('cat');
        $('.map-title .category').html($(this).text());
        buildSeries(LOCATION, CATEGORY);
    });

    $('.locationNav ul li').on('click', function(e){
        $(this).addClass('active').siblings().removeClass('active');
        LOCATION = $(this).data('location');
        $('.map-title .location').html(LOCATION);
        buildSeries(LOCATION, CATEGORY);
    });



function buildSeries(city, term){

    var output = [];

    $.ajax({url: "/app.php/?q=1&city="+city+"&term="+term, success: function(result){
        results = JSON.parse(result);

        for (var key in results) {

            var weekArr = results[key].split("|");

            var result = '{ "name": "'+key+'", "data": ['+weekArr[0]+', '+weekArr[1]+', '+weekArr[2]+', '+weekArr[3]+', '+weekArr[4]+', '+weekArr[5]+', '+weekArr[6]+'], "color": "'+getColor()+'" }';

            var obj = jQuery.parseJSON(result);

            output.push(obj);
        }

        buildChart(output);

    }});

    return;
}


function getColor(){
    var colors = [
        'F44336','FF8A80','E91E63','FF80AB','9C27B0','EA80FC','673AB7','B388FF','3F51B5','8C9EFF','2196F3','82B1FF',
        '03A9F4','80D8FF','00BCD4','84FFFF','009688','A7FFEB','4CAF50','B9F6CA','8BC34A','CCFF90','CDDC39','F4FF81',
        'FFEB3B','FFFF8D','FFC107','FFE57F','FF9800','FFD180','FF5722','FF9E80','795548','8D6E63','9E9E9E','E0E0E0'
    ];
    return '#'+colors[Math.floor(Math.random() * colors.length)];
}

buildSeries('Boulder', 'restaurants');

});

/*
            series: [{name: 'Tokyo', data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6],color: '#ffbb99'}, {
                name: 'New York',
                data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0],
                color: '#ffff8d'

            }, {
                name: 'London',
                data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0],
                color: '#99ffcc'

            }, {
                name: 'Berlin',
                data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4],
                color: '#ff8a80'

            }, {
                name: 'Germany',
                data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4],
                color: '#b388ff'

            }, {
                name: 'This Place',
                data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4],
                color: '#b388ff'

            }, {
                name: 'That Place',
                data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4],
                color: '#b388ff'

            }]

            */