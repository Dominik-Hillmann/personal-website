<!DOCTYPE html>
<html lang="de">
<head>

<!-- Basic Page Needs -->
    <meta charset="utf-8">
    <title>Dominik Hillmann</title>
    <meta name="description" content=""> <!-- DO NOT FORGET DESCRIPTION-->
    <meta name="author" content="Dominik Hillmann">

<!-- Mobile Specific Metas -->
    <meta>

<!-- CSS links -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/mobile.css">

<!-- Favicon -->
    <link rel="icon" type="image/png" href="">

</head>
<body>
   <?php
      // get weather data if it is older than two hours
      // get weather JSON --> save it as JSON, time with it
      // someone calls index.php: check last update of weather.json
      // if older than 2h --> call API and update weather.json then use it, else use current weather.json

      // configurations for contacting the OpenWeatherMap API
      $appid = "9fe71167771b980192490c2eb67c98bf"; // key
      $city = "Mannheim";

      //$now = time();
      //echo date("d.m.Y H:i:s", $now) . "<br>";
      //echo var_dump($now) . "<br>";
      //echo var_dump(date("d.m.Y H:i:s", $now)) . "<br>";
      //time / 60 / 60 - now /60 / 60 > 20 --> Abstand in Stunden > 2 --> neuer file, anosnten daten nutzen
      //echo "<br><br>";

      $nowTime = time() / (60 * 60); // get hours by dividing by 60 * 60
      // check the last time the weather was updated
      $lastTimeTxt = fopen("./data/lastTime.txt", "r");
      $lastTime = ((int) fgets($lastTimeTxt)) / (60 * 60);
      fclose($lastTimeTxt);
      echo ($nowTime - $lastTime) . "<br>";

      //if(!$lastTime)
         //exit("Couldn't open lastTime.txt.");

      // get weather data from API or JSON dependend on how long ago the last update happened (1 hour)
      $weather;
      // less than one hour ago --> use the JSON
      if(($nowTime - $lastTime) <= 1.0)
      {
         echo "Nehme alte Datei.<br>";
         $weather = json_decode(file_get_contents("./data/weather.json", "r"));
      }
      else
      {
         echo "Kontaktiere API<br>";
         // more than one hour ago --> contact weather API
         $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" . $appid;
         // echo "QUERY: " . $url . "<br>";
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         $wJson = curl_exec($curl);

         // if successfully contacted the API --> give data to weather and write into weather.json
         if(isset($wJson))
         {
            echo "Contacted Weather API. " . "JSON: " . $wJson . "<br>";
            $weather = json_decode($wJson);
            file_put_contents("./data/weather.json", json_encode($weather));
            echo $writeSuccess . "<br>";

            // update lastTime.txt because weather was just updated
            $lastTime = fopen("./data/lastTime.txt", "w");
            $writeSuccess = fputs($lastTime, (string) time());
            fclose($lastTime);
            $writeSuccess ? "TIME successfully saved<br>" : "ERRORs<br>";
         }
         else
         {
            echo "Could NOT contact Weather API.<br>";
            $weather = json_decode(file_get_contents("./data/weather.json", "r"));
         }
      }
      //var_dump($weather);

      echo "<br>current conditions in " . $city . ": " . $weather->weather[0]->description;
   ?>

<!-- Page Layout -->

   <header>
      <div id="menuwrapper">

         <!-- Teile: "Ganz oben mit p5-Sketch", Kontakt, Skills, Resumé, brief history [reading, ], notebook (quasi blog)-->
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">START</a></div>
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">SKILLS</a></div>
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">KONTAKT</a></div>
         <!--später blank.png, mit css hollow und full änder per CSS-->

      </div>
   </header>

   <div id="sketch-holder"></div>



   <div id="test"></div>

   <div id="skills">
      <div class="heading">
         <h2>SKILLS</h2>
      </div>

      <div class="content">

      </div>
   </div>

   <div id="contact">
      <div class="heading">
         <h2>CONTACT</h2>
      </div>
      <div class="content">
         <form action="contact.php" method="post">
            <h2>Say hello!</h2>
            <p>Vorname<input name="firstname" value="Test"></p>
            <p>Nachname<input name="lastname"></p>
            <p>Ihre E-Mailadresse<input name="address"></p>
            <p>Betreff<input name="subject"></p>
            <p>Nachricht<textarea name="message"></textarea></p>
            <p><input type="submit"><input type="reset"></p>
         </form>
      </div>
   </div>

   <div id="currently">


      <div class="heading"><h2>Currently...</h2></div>
      <div class="content">

         <div id="reading">
            <div class="heading"><h2>... reading</h2></div>
            <div class="content">

            </div>
         </div>


         <div id="working">
            <div class="heading"><h2>... working on</h2></div>
            <div class="content">

            </div>
         </div>

      </div>

   </div>


   <div id="resume">
      <div class="heading"><h2>Resumé</h2></div>
      <div class="content"></div>
   </div>


   <div id="notebook">
      <div class="heading"></div>
      <div class="content"></div>
   </div>



<!-- End of Page Layout -->
</body>

<script type="text/javascript" src="js/menu.js"></script>

<!-- spaeter herunterladen und gleich auf dem Server zur Verfügung stellen -->
<script type="text/javascript" src="js/libraries/p5.js"></script>
<script type="text/javascript" src="js/sphere.classes.js"></script>
<script type="text/javascript" src="js/sphere.sketch.js"></script>

</html>

<!-- Links to Scripts -->
