<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
       <!--  Basic line chart showing trends in a dataset. This chart includes the
        <code>series-label</code> module, which adds a label to each line for
        enhanced readability. -->
    </p>
</figure>
<script>
Highcharts.chart('container', {

    title: {
        text: 'Name:<?=$studentName?>  Roll No.:<?=$roll_no?> Class:<?=$classesName?> <?=$sectionName?>'
    },

    // subtitle: {
    //     text: 'Source: thesolarfoundation.com'
    // },

    yAxis: {
        min: 0,
        max: 4,
        title: {
            text: 'CGPA'
        }
    },

    xAxis: {
        // accessibility: {
        //     rangeDescription: 'Range: 2010 to 2017'
        // }
        categories: <?=$exams?>
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },

    series: <?=$gpaarray?>,

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
</script>