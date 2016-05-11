<?php
use Carbon\Carbon;

require_once "function.php" ?>
<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Mato</title>

    <!-- Bootstrap -->
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700&subset=latin,latin-ext' rel='stylesheet'
          type='text/css'>


    <link href="assets/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>

</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-header">
                <h1>Pre Mata
                    <small>vypracoval Michal Čech</small>
                </h1>
            </div>
        </div>
    </div>

    <div class="row">
<!--        --><?php //foreach (Stat::fetchAll() as $stat): ?>
<!--            --><?php //dd($stat) ?>
<!--        --><?php //endforeach; ?>
    </div>
    <div class="row">
        <div style="width:100%;">
            <canvas id="canvas"></canvas>
        </div>
    </div>

</div>

<?php

$data = [];
$datasets = [];
$stats_group_by_date = [];
foreach (Stat::fetchAll() as $stat):
    $stats_group_by_date[$stat->day_pivot][] = $stat;
endforeach;


$one_final_obj1 = [
    'label' => 'Temp',
    'data' => [],
];
$one_final_obj2 = [
    'label' => 'Humi',
    'data' => [],
];
$one_final_obj3 = [
    'label' => 'Dewp',
    'data' => [],
];
$labels = [];
foreach($stats_group_by_date as $key => $stats_group){
    $labels[] = Carbon::createFromFormat('ymd', $key)->format('l');

    $sum_temp = 0;
    $sum_humi = 0;
    $sum_dewp = 0;
    foreach($stats_group as $day_stat){
        $sum_temp += $day_stat->temp;
        $sum_humi += $day_stat->humi;
        $sum_dewp += $day_stat->dewp;
    }
    $one_final_obj1['data'][] = $sum_temp;
    $one_final_obj2['data'][] = $sum_humi;
    $one_final_obj3['data'][] = $sum_dewp;
}
$data['labels'] = $labels;
$data['datasets'] = [
    $one_final_obj1, $one_final_obj2, $one_final_obj3
];

//echo json_encode($data);
//dd(0);
?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.2/Chart.min.js"></script>

<script>
//    var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    //    var randomScalingFactor = function() {
    //        return Math.round(Math.random() * 100);
    //        //return 0;
    //    };
        var randomColorFactor = function() {
            return Math.round(Math.random() * 255);
        };
        var randomColor = function(opacity) {
            return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
        };
    var config = {
        type: 'line',
        data: <?php echo json_encode($data); ?>
//        data: {
//            labels: ["January", "February", "March", "April", "May", "June", "July"],
//            datasets: [{
//                label: "My First dataset",
//                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
//                fill: false,
//                borderDash: [5, 5]
//            }, {
//                hidden: true,
//                label: 'hidden dataset',
//                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
//            }, {
//                label: "My Second dataset",
//                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
//            }]
//        },
        ,
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Chart.js Line Chart'
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    // beforeTitle: function() {
                    //     return '...beforeTitle';
                    // },
                    // afterTitle: function() {
                    //     return '...afterTitle';
                    // },
                    // beforeBody: function() {
                    //     return '...beforeBody';
                    // },
                    // afterBody: function() {
                    //     return '...afterBody';
                    // },
                    // beforeFooter: function() {
                    //     return '...beforeFooter';
                    // },
                    // footer: function() {
                    //     return 'Footer';
                    // },
                    // afterFooter: function() {
                    //     return '...afterFooter';
                    // },
                }
            },
            hover: {
                mode: 'dataset'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Day'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Value'
                    },
                    ticks: {
                        suggestedMin: -10,
                        suggestedMax: 250,
                    }
                }]
            }
        }
    };
    $.each(config.data.datasets, function (i, dataset) {
        dataset.borderColor = randomColor(0.4);
        dataset.backgroundColor = randomColor(0.5);
        dataset.pointBorderColor = randomColor(0.7);
        dataset.pointBackgroundColor = randomColor(0.5);
        dataset.pointBorderWidth = 1;
    });
    window.onload = function () {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx, config);
    };
    //    $('#randomizeData').click(function() {
    //        $.each(config.data.datasets, function(i, dataset) {
    //            dataset.data = dataset.data.map(function() {
    //                return randomScalingFactor();
    //            });
    //        });
    //        window.myLine.update();
    //    });
    //    $('#changeDataObject').click(function() {
    //        config.data = {
    //            labels: ["July", "August", "September", "October", "November", "December"],
    //            datasets: [{
    //                label: "My First dataset",
    //                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
    //                fill: false,
    //            }, {
    //                label: "My Second dataset",
    //                fill: false,
    //                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
    //            }]
    //        };
    //        $.each(config.data.datasets, function(i, dataset) {
    //            dataset.borderColor = randomColor(0.4);
    //            dataset.backgroundColor = randomColor(0.5);
    //            dataset.pointBorderColor = randomColor(0.7);
    //            dataset.pointBackgroundColor = randomColor(0.5);
    //            dataset.pointBorderWidth = 1;
    //        });
    //        // Update the chart
    //        window.myLine.update();
    //    });
    //    $('#addDataset').click(function() {
    //        var newDataset = {
    //            label: 'Dataset ' + config.data.datasets.length,
    //            borderColor: randomColor(0.4),
    //            backgroundColor: randomColor(0.5),
    //            pointBorderColor: randomColor(0.7),
    //            pointBackgroundColor: randomColor(0.5),
    //            pointBorderWidth: 1,
    //            data: [],
    //        };
    //        for (var index = 0; index < config.data.labels.length; ++index) {
    //            newDataset.data.push(randomScalingFactor());
    //        }
    //        config.data.datasets.push(newDataset);
    //        window.myLine.update();
    //    });
    //    $('#addData').click(function() {
    //        if (config.data.datasets.length > 0) {
    //            var month = MONTHS[config.data.labels.length % MONTHS.length];
    //            config.data.labels.push(month);
    //            $.each(config.data.datasets, function(i, dataset) {
    //                dataset.data.push(randomScalingFactor());
    //            });
    //            window.myLine.update();
    //        }
    //    });
    //    $('#removeDataset').click(function() {
    //        config.data.datasets.splice(0, 1);
    //        window.myLine.update();
    //    });
    //    $('#removeData').click(function() {
    //        config.data.labels.splice(-1, 1); // remove the label first
    //        config.data.datasets.forEach(function(dataset, datasetIndex) {
    //            dataset.data.pop();
    //        });
    //        window.myLine.update();
    //    });
</script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>


</body>
</html>