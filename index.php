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
      // api.openweathermap.org/data/2.5/weather?q=London,uk&APPID=9fe71167771b980192490c2eb67c98bf

      $currentCity = "London,uk";
      $APPID = "9fe71167771b980192490c2eb67c98bf";
      // $url = "api.openweathermap.org/data/2.5/weather?q=" . $currentCity . "&APPID=" . $APPID;
      $url = "http://www.api.openweathermap.org/data/2.5/weather?q=London,uk&APPID=9fe71167771b980192490c2eb67c98bf";
      echo $url . "<br>";

      $response = file_get_contents($url);

      echo "<p>RESPONSE: " . $response . "</p>";


      /***** GROUPON EXAMPLE *****//*
      $apikey = "client_id=abcd1234567890";
      $division = "division_id=chicago";
      $url = "http://api.groupon.com/v2/deals?" . implode("&", array($apikey, $division));
      $response = file_get_contents($url);
      $deals = json_decode($response, true);

      foreach($deals['deals'] as $deal){
          $format = 'Deal: <a href="%s">%s</a><br/>';
          echo sprintf( $format, $deal['dealURL'], $deal['announcementTitle']);
      }*/

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
