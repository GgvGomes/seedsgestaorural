<div class="col-lg-6 col-xs-12" style="padding:0px;margin:0px">
    <canvas id="SaidaFilho"></canvas>
</div>
<script>
    var ctx = document.getElementById('SaidaFilho').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [100, 50],
                backgroundColor: ['blue', 'yellow'],

            }],
            labels: ['Fazenda']
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Talhão 1'
            },
            legend: {
                display: false
            }
        }
    });
</script>