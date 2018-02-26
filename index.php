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
      // configurations for contacting the OpenWeatherMap API
      $appid = "9fe71167771b980192490c2eb67c98bf";
      $city = "Mannheim";

      $nowTime = time() / (60 * 60); // get hours by dividing by 60 * 60
      $lastTimeTxt = fopen("./data/lastTime.txt", "r"); // check the last time the weather was updated
      $lastTime = ((int) fgets($lastTimeTxt)) / (60 * 60);
      fclose($lastTimeTxt);
      echo ($nowTime - $lastTime) . "<br>";

      // get weather data from API or JSON dependend on how long ago the last update happened (1 hour)
      $weather;
      if (($nowTime - $lastTime) <= 1.0) // less than one hour ago --> use the JSON
      {
         echo "Nehme alte Datei.<br>";
         $weather = json_decode(file_get_contents("./data/weather.json", "r"));
      }
      else // more than one hour ago --> contact weather API
      {
         echo "Kontaktiere API<br>";
         $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" . $appid;
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         $jsonW = curl_exec($curl);
         curl_close($curl);

         if (isset($jsonW)) // if successfully contacted the API --> give data to weather and write into weather.json
         {
            echo "Contacted Weather API. " . "JSON: " . $jsonW . "<br>";
            $weather = json_decode($jsonW);
            file_put_contents("./data/weather.json", json_encode($weather));

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

      echo "<br>current conditions in " . $city . ": " . $weather->weather[0]->description . "<br>";


      /***** GitHub API *****/
      function github_request($url)
      {
          $ch = curl_init();

          // Basic Authentication with token
          // https://developer.github.com/v3/auth/
          // https://github.com/blog/1509-personal-api-tokens
          // https://github.com/settings/tokens
          $access = "d55c9cbf5f475177c79c39e31cf1fae3b63b7f9a";

          curl_setopt($ch, CURLOPT_URL, $url);
          //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
          curl_setopt($ch, CURLOPT_USERAGENT, "Dominik-Hillmann");
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_USERPWD, $access);
          curl_setopt($ch, CURLOPT_TIMEOUT, 30);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          $output = curl_exec($ch);
          curl_close($ch);
          $result = json_decode(trim($output), true);
          return $result;
      }

      $urlRepos = "http://api.github.com/users/Dominik-Hillmann";
      $gitcurl = curl_init();
      curl_setopt($gitcurl, CURLOPT_URL, $urlRepos);
      curl_setopt($gitcurl, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt($gitcurl, CURLOPT_USERAGENT, "Dominik-Hillmann https://api.github.com/Dominik-Hillmann");
      $jsonR = curl_exec($gitcurl);
      var_dump($jsonR);
      curl_close($gitcurl);
      // "d55c9cbf5f475177c79c39e31cf1fae3b63b7f9a"
      echo $jsonR . "HALLO<br>";

      var_dump(github_request($urlRepos));


      //echo (string) github_request("http://api.github.com/users/Dominik-Hillmann/repos");
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
