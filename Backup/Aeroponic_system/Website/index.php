<?php
  require("php/GetData.php");
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Aeroponic Sensors Data</title>
    <link rel="stylesheet" href="css/index.css">
  </head>
  <body>
    <?php
        for($i = 1; $i <= $nb_of_plants; $i++){ ?>
          <p class="system-ID">system ID: <?php echo $i;?></p>
          <div class="TDS-container-<?php echo $i;?>">
            <div class="TDS-data-div">
              <p class="TDS-data">Actual TDS: <?php echo $TDS[count($TDS) - 1];?></p>
            </div>
            <div class="TDS-graph">
              <canvas id="TDS-chart" class="TDS-chart"></canvas>
              <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
              <script type="text/javascript">
                var ctx = document.getElementById('TDS-chart');
                var myChart = new Chart(ctx, {
                  type: 'line',
                  data: {
                      labels: [
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 24];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 23];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 22];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 21];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 20];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 19];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 18];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 17];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 16];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 15];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 14];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 13];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 12];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 11];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 10];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 9];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 8];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 7];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 6];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 5];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 4];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 3];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 2];?>',
                        '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 1];?>'
                      ],
                      datasets: [{
                          label: 'TDS',
                          data: [
                            <?php echo $TDS[count($TDS) - 24]; ?>,
                            <?php echo $TDS[count($TDS) - 23]; ?>,
                            <?php echo $TDS[count($TDS) - 22]; ?>,
                            <?php echo $TDS[count($TDS) - 21]; ?>,
                            <?php echo $TDS[count($TDS) - 20]; ?>,
                            <?php echo $TDS[count($TDS) - 19]; ?>,
                            <?php echo $TDS[count($TDS) - 18]; ?>,
                            <?php echo $TDS[count($TDS) - 17]; ?>,
                            <?php echo $TDS[count($TDS) - 16]; ?>,
                            <?php echo $TDS[count($TDS) - 15]; ?>,
                            <?php echo $TDS[count($TDS) - 14]; ?>,
                            <?php echo $TDS[count($TDS) - 13]; ?>,
                            <?php echo $TDS[count($TDS) - 12]; ?>,
                            <?php echo $TDS[count($TDS) - 11]; ?>,
                            <?php echo $TDS[count($TDS) - 10]; ?>,
                            <?php echo $TDS[count($TDS) - 9]; ?>,
                            <?php echo $TDS[count($TDS) - 8]; ?>,
                            <?php echo $TDS[count($TDS) - 7]; ?>,
                            <?php echo $TDS[count($TDS) - 6]; ?>,
                            <?php echo $TDS[count($TDS) - 5]; ?>,
                            <?php echo $TDS[count($TDS) - 4]; ?>,
                            <?php echo $TDS[count($TDS) - 3]; ?>,
                            <?php echo $TDS[count($TDS) - 2]; ?>,
                            <?php echo $TDS[count($TDS) - 1]; ?>
                          ]
                      }]
                  },
                  options: {
                      scales: {
                          y: {
                              beginAtZero: true,
                              grid: {
                                color: 'white'
                              }
                          },
                          x: {
                            grid: {
                              color: 'white'
                            }
                          }
                      },
                      borderColor: 'green',
                      color: 'white'
                  }
                });
              </script>
            </div>
          </div>
          <div class="PH-container-<?php echo $i;?>">
            <div class="PH-data-div">
              <p class="PH-data">Actual PH: <?php echo $PH[count($PH) - 1];?></p>
            </div>
            <div class="PH-graph">
              <canvas id="PH-chart"></canvas>
              <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
              <script type="text/javascript">
                var ctx = document.getElementById('PH-chart');
                var myChart = new Chart(ctx, {
                  type: 'line',
                  data: {
                    labels: [
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 24];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 23];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 22];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 21];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 20];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 19];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 18];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 17];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 16];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 15];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 14];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 13];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 12];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 11];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 10];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 9];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 8];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 7];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 6];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 5];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 4];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 3];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 2];?>',
                      '<?php echo $AeroponicSensor_time[count($AeroponicSensor_time) - 1];?>'
                    ],
                    datasets: [{
                      label: 'PH',
                      data: [
                        '<?php echo $PH[count($PH) - 24]; ?>',
                        '<?php echo $PH[count($PH) - 23]; ?>',
                        '<?php echo $PH[count($PH) - 22]; ?>',
                        '<?php echo $PH[count($PH) - 21]; ?>',
                        '<?php echo $PH[count($PH) - 20]; ?>',
                        '<?php echo $PH[count($PH) - 19]; ?>',
                        '<?php echo $PH[count($PH) - 18]; ?>',
                        '<?php echo $PH[count($PH) - 17]; ?>',
                        '<?php echo $PH[count($PH) - 16]; ?>',
                        '<?php echo $PH[count($PH) - 15]; ?>',
                        '<?php echo $PH[count($PH) - 14]; ?>',
                        '<?php echo $PH[count($PH) - 13]; ?>',
                        '<?php echo $PH[count($PH) - 12]; ?>',
                        '<?php echo $PH[count($PH) - 11]; ?>',
                        '<?php echo $PH[count($PH) - 10]; ?>',
                        '<?php echo $PH[count($PH) - 9]; ?>',
                        '<?php echo $PH[count($PH) - 8]; ?>',
                        '<?php echo $PH[count($PH) - 7]; ?>',
                        '<?php echo $PH[count($PH) - 6]; ?>',
                        '<?php echo $PH[count($PH) - 5]; ?>',
                        '<?php echo $PH[count($PH) - 4]; ?>',
                        '<?php echo $PH[count($PH) - 3]; ?>',
                        '<?php echo $PH[count($PH) - 2]; ?>',
                        '<?php echo $PH[count($PH) - 1]; ?>'
                      ]
                    }]
                  },
                  options:{
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                              color: 'white'
                            }
                        },
                        x: {
                          grid: {
                            color: 'white'
                          }
                        }
                    },
                    borderColor: 'green',
                    color: 'white'
                  }
                });
              </script>
            </div>
          </div>
          <div class="Tank-water-level-<?php echo $i;?>">
            <div class="Level-div">
              <p>Nutrient tank water level is <?php echo $tank_water_level[count($tank_water_level) - 1]; ?></p>
            </div>
          </div>
        <?php
      }
    ?>
  </body>
</html>
