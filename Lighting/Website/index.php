<?php
  require("php/GetData.php");

  // Settings
  $address = '10.0.0.19';
  $port = 5045;

  //$socket_connect_err = 0;

  /*for ($i = 1; $i <= $nb_of_lights; $i++){



  }
*/
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Lighting</title>
     <link rel="stylesheet" href="css/index.css">
   </head>
   <body>
     <script type="text/javascript" src="js/index.js"></script>
     <div class="main-box">


     <?php for($i = 1; $i <= $nb_of_lights; $i++)
     {
       ?>
       <div class="container">
         <div class="light-area" id="Light-<?php echo $i; ?>-div">
           <div class="state" id="state-<?php echo $i; ?>-div">
             <p class="state-<?php echo $i; ?>-p" id="state-<?php echo $i; ?>-p"><?php
             if($State[($i)]){
               echo "on";

             }
             else{
              echo "off";

             }
             ?></p>
           </div>
           <script type="text/javascript">
           var nb_of_lights = <?php echo $nb_of_lights; ?>;
           var States = <?php echo json_encode($State);?>;
           for(var i = 1; i <= nb_of_lights; i++){

             if(States[i - 1]){
               document.getElementById("state-" + i.toString() + "-div").style.background = "rgba(54,191,90,0.65)";
             }
             else{
               document.getElementById("state-" + i.toString() + "-div").style.background = "rgba(191,54,54,0.65)";
             }

           }

           </script>

         </div>
         <div class="program-box" id="program-box-<?php echo $i;?>">
           <div class="set_time">
             <form class="" action="index.php" method="post">
               <p class="time on">Time on
                 <input type="text" name="hr_on_<?php echo $i;?>" size="1">
                 <label for="">:</label>
                 <input type="text" name="min_on_<?php echo $i;?>" size="1">
               </p>
               <p class="time off">Time off
                 <input type="text" name="hr_off" size="1">
                 <label for="">:</label>
                 <input type="text" name="min_off" size="1">
               </p>
               <input type="submit" name="send_light<?php echo $i;?>_time" value="Set">
             </form>
           </div>
         </div>
         <div class="connection_popup" id="popup<?php echo $i; ?>">
           <p class="popup_text" id="popup-text<?php echo $i; ?>">Unable to connect to <?php echo $address; ?></p>
           <!-- <div class="exit_popup" onclick="exit_popup(<?php // echo $i; ?>)"></div> -->
           <!-- Load React. -->
           <!-- Note: when deploying, replace "development.js" with "production.min.js". -->
           <script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>  <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
           <!-- Load our React component. -->
           <script src="js/popup.js"></script>
         </div>
       </div>

     <?php
       $str = "send_light".$i."_time";
       if(isset($_POST[$str])){

         // convert the $_POST['a'] to integer if it's valid, or default to 0
         $hr_on = (is_numeric($_POST['hr_on']) ? (int)$_POST['hr_on'] : 0);
         $min_on = (is_numeric($_POST['min_on']) ? (int)$_POST['min_on'] : 0);
         $hr_off = (is_numeric($_POST['hr_off']) ? (int)$_POST['hr_off'] : 0);
         $min_off = (is_numeric($_POST['min_off']) ? (int)$_POST['min_off'] : 0);

         $time_on = ($hr_on * 3600) + ($min_on * 60);
         $time_off = ($hr_off * 3600) + ($min_off * 60);

         if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
           echo "Couldn't create socket".socket_strerror(socket_last_error())."\n";
         }
         if (socket_connect($socket, $address, $port) === false) {
           ?>
            <script type="text/javascript" src="js/index.js">
              open_popup();
            </script>
           <?php
           //echo "Bind Error ".socket_strerror(socket_last_error($sock)) ."\n";

         }
         $str = "r".$i." ".$time_on." ".$time_off;
         socket_write($socket, $str, strlen($str));
         socket_close($socket);


       }
     }
       ?>
   </div>
   </body>
 </html>
