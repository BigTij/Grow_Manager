<?php

  
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Grow Manager</title>
    <link rel="stylesheet" href="/CSS/Homepage.css">
  </head>
  <body>
<!--------- Dropdown Menu ---------------------------------->
    <div class="dropdown">
      <button class="dropbtn">Dropdown</button>
      <div class="dropdown-content">
        <a href="Homepage.php">Homepage</a>
        <a href="../Irrigation/index.php">Irrigation</a>
        <a href="../Lighting/index.php">Lighting</a>
        <a href="../Air-Control/index.php">Air Control</a>
      </div>
    </div>
<!---------------------------------------------------------->

<!--------- Basic Irrigation info -------------------------->

    <div class="Irrigation-Box">
      <?php
        include 'Irrigation.php';
      ?>
    </div>
<!----------------------------------------------------------->

<!--------- Basic Lighting info ----------------------------->

    <div class="Lighting-Box">
      <?php
        include 'Lighting.php';
      ?>
    </div>

<!----------------------------------------------------------->

<!--------- Basic Air Control info -------------------------->

    <div class="Air-Control-Box">
      <?php 
        include 'Air-Control.php';
      ?>
    </div>

<!----------------------------------------------------------->
  </body>
</html>
