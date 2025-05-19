<?php
  require('config.php');

  $conn = mysqli_connect($address,$username,$password,$db_name);

  if(mysqli_connect_errno()){
    die('Connection failed: ' . mysqli_connect_errorno());
  }

  //deletes any rows older than 7 days
  $sql = 'DELETE FROM Tank_Water_Level WHERE datediff(now(), Tank_Water_Level.Date) > 7;';
  if(!($result = mysqli_query($conn, $sql))){
    echo "failed to send query: " . $sql;
  }
  mysqli_free_result($result);

  $sql = 'DELETE FROM AeroponicSensor WHERE datediff(now(), AeroponicSensor.Date) > 7;';
  mysqli_query($conn,$sql);
  if(!($result = mysqli_query($conn, $sql))){
    echo "failed to send query: " . $sql;
  }
  mysqli_free_result($result);
  // previous query: $sql = 'SELECT * FROM AeroponicSensor ORDER BY id DESC LIMIT 24;';
  $sql = 'SELECT * FROM AeroponicSensor;';
  if($result = mysqli_query($conn, $sql)){
    $NB_OF_ROW  = 0;
    $AeroponicData = 0;
    while($row = mysqli_fetch_row($result)){
      $PH[$NB_OF_ROW] = $row[0];
      $TDS[$NB_OF_ROW] = $row[1];
      $AeroponicSensor_time[$NB_OF_ROW] = $row[2];
      $NB_OF_ROW++;
    }
    mysqli_free_result($result);
  }

  $sql = 'SELECT * FROM Tank_Water_Level;';
  if($result = mysqli_query($conn, $sql)){
    $NB_OF_ROW = 0;
    while($row = mysqli_fetch_row($result)){

      $tank_water_level[$NB_OF_ROW] = $row[0];
      $tank_water_level_time[$NB_OF_ROW] = $row[1];
      $NB_OF_ROW++;
    }
    mysqli_free_result($result);
  }
  mysqli_close($conn);

 ?>
