<!DOCTYPE html>
<html lang="de">
<head>
<!-- NOTHING WELL DONE IS EVER INSIGNIFICANT. -->
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
      // stores all needed data for the GitHub projects I want to display
      class Repo {
         public $name;
         public $lastUpdate;
         public $description;

         public function __construct($name, $lastUpdate, $description) {
            $this->name = $name;
            $this->description = $description;
            $this->lastUpdate = strtotime($lastUpdate); // UNIX timestamp
         }
      }

      // accesses wather and github API via curl
      function contactAPI($url, $token) {
         $curl = curl_init($url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         if (isset($token)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["User-Agent: Dominik-Hillmann", $token]);
         }
         $outputs = curl_exec($curl);
         curl_close($curl);
         return $outputs;
      }

      // writes current time into .txt files
      function putTime($path) {
         $lastTime = fopen($path, "w");
         fputs($lastTime, (string) time());
         fclose($lastTime);
         return;
      }

      // time from .txt file as UNIX timestamp
      function getTime($path) {
         $timef = fopen($path, "r");
         $time = (int) fgets($timef);
         fclose($timef);
         return $time;
      }


      /***** OpenWeatherMap API *****/
      // configurations for contacting the OpenWeatherMap API
      $appid = "dfc5381a15a6aea6bea3bcb0ef26a045";
      $city = "Mannheim";
      // check the last time the weather.json file was updated
      $nowTime = time() / (60 * 60);
      $weatherUpd = getTime("./data/timeWeather.txt") / (60 * 60);
      $weather;
      $wAPI = false;

      // less than one hour ago --> use the JSON
      if (($nowTime - $weatherUpd) <= 1.0) {
         $weather = json_decode(file_get_contents("./data/weather.json", "r"));
      } else {
         $jsonW = contactAPI("http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" . $appid, NULL);
         $wAPI = true;

         if (isset($jsonW)) {
            $weather = json_decode($jsonW);
            file_put_contents("./data/weather.json", json_encode($weather));
            putTime("./data/timeWeather.txt");
         } else {
            $weather = json_decode(file_get_contents("./data/weather.json", "r"));
         }
      }


      /***** GitHub API *****/
      $githubToken = "";
      $ReposUrl = "https://api.github.com/users/Dominik-Hillmann/repos";
      $curlToken = "Authorization: token " . $githubToken;

      $githubUpd = getTime("./data/timeGithub.txt") / (60 * 60);
      // contact the API if the GitHub-json files are older than one day
      $repos = [];
      $gAPI = false;

      if (($nowTime - $githubUpd) >= 24.0) {
         $repoData = json_decode(contactAPI($ReposUrl, $curlToken));

         // put all relevant data into objects
         foreach ($repoData as $repo) {
            array_push($repos, new Repo(
               $repo->name,
               $repo->pushed_at,
               $repo->description
            ));
         }
         // (over)write files
         foreach ($repos as $repo) {
            file_put_contents(
               "./data/repos/" . $repo->name . ".json",
               json_encode($repo)
            );
         }

         // update time because the data got updated
         putTime("./data/timeGithub.txt");
         $gAPI = true;
      } else {
         // get repo data locally
         $files = scandir("./data/repos/");
         // delete ".", ".."
         unset($files[0]);
         unset($files[1]);

         foreach ($files as $file) {
            array_push($repos, json_decode(file_get_contents("./data/repos/" . $file)));
         }
      }

      // select project with most recent update
      $currProj = $repos[0];
      for ($i = 1; $i < count($repos); $i++) {
         if ($repos[$i]->lastUpdate > $currProj->lastUpdate) {
            $currProj = $repos[$i];
         }
      }


      /***** CONSTRUCTIONG WEATHER SYMBOLS *****/
      $weatherPicStr = "./images/weather/bright_";
      $hour = (int) date("G", time());

      if (($hour > 19) || ($hour < 7)) {
         $weatherPicStr .= "night_";
      } else {
         $weatherPicStr .= "day_";
      }


      $id = $weather->weather[0]->id;
      if ((($id >= 200) && ($id < 300)) || ($id >= 900)) {
         $weatherPicStr .= "storm.png";
      } else if (($id >= 300) && ($id < 600)) {
         $weatherPicStr .= "rain.png";
      } else if (($id >= 600) && ($id < 701)) {
         $weatherPicStr .= "snow.png";
      } else if (($id >= 701) && ($id < 800)) {
         $weatherPicStr .= "mist.png";
      } else if ($id == 800) {
         $weatherPicStr .= "clear.png";
      } else if (($id >= 801) && ($id < 900)) {
         $weatherPicStr .= "cloud.png";
      } else {
         $weatherPicStr .= "cloud.png";
      }

   ?>

<!-- Page Layout -->

   <header>
      <div id="weather">
         <img src=<?php echo $weatherPicStr; ?>>
         <p><?php echo round($weather->main->temp - 273.15) . "°C " . $city; ?></p>
      </div>
      <div id="menuwrapper">
         <!-- Teile: "Ganz oben mit p5-Sketch", Kontakt, Skills, Resumé, brief history [reading, ], notebook (quasi blog)-->
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">START</a></div>
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">SKILLS</a></div>
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">KONTAKT</a></div>
         <!--später blank.png, mit css hollow und full änder per CSS-->
      </div>
   </header>

   <!--<div id="sketch-holder"></div>-->



   <!--<div id="test"></div>

   <div id="skills" class="segment">
      <div class="heading">
         <h2>SKILLS</h2>
      </div>

      <div class="content">

      </div>
   </div>-->

   <div id = "contact">

      <div id = "contactOther">

         <div>
            <div>
               <img src="/images/github_black.png">
               <img src="/images/xing_black.png">
               <img src="/images/linkedin_black.png">
            </div>

         </div>

         <div><!-- social media links -->
            <img src = "./images/arrow_down.png">
         </div>

      </div>

      <!-- Button linksbündig gross -->
      <!-- Rechts daneben Download Lebenslauf -->
      <div id = "contactForm">
         <form action = "contact.php" method = "post">
            <h2 class="hover-underline-animation">Say hello!</h2>
            <div>
               <div class="twoinrow">
                  <h3 class="formHeading">Vorname</h3>
                  <input type="text" name="firstname">
               </div><div class="twoinrow"><!-- to avoid whitespace -->
                  <h3 class="formHeading">Nachname</h3>
                  <input type="text" name="lastname">
               </div>
            </div>
            <p>
               <h3 class="formHeading">E-Mailadresse</h3>
               <input type="text" name="address">
            </p>
            <p>
               <h3 class="formHeading">Telefonnummer</h3>
               <input type="text" name="telnum">
            </p>
            <p>
               <h3 class="formHeading">Betreff</h3>
               <input type="text" name="subject">
            </p>
            <p>
               <h3 class="formHeading">Nachricht</h3>
               <textarea id="message" name="message"></textarea>
            </p>
            <p>
               <input id="submit" class="nonWriteInput" type="submit">
               <!--<a href="#">Resumé</a>-->
            </p>
         </form>
      </div>



      <div id="skills">

         <div>
            <div id="web-wrapper" class="sliderWrapper">
               <div class="progress"><div>&nbsp;</div></div>
               <div class="slider"><!--
               --><img src="/images/github_black.png" class="slide notShown web"><!-- getting rid of goddamn whitespace
               --><img src="/images/xing_black.png" class="slide notShown web"><!--
               --><img src="/images/linkedin_black.png" class="slide notShown web"><!--
               --><img src="/images/github_black.png" class="slide notShown web"><!--
               --><img src="/images/xing_black.png" class="slide notShown web"><!--
               --><img src="/images/linkedin_black.png" class="slide notShown web"><!--
               --><img src="/images/github_black.png" class="slide notShown web"><!--
               --><img src="/images/xing_black.png" class="slide notShown web"><!--
               --><img src="/images/linkedin_black.png" class="slide notShown web"><!--
               --><img src="/images/linkedin_black.png" class="slide notShown web">
               </div>
                  <img src="./images/arrow_in_circle.png" onclick="animateSlide('web')" class="arrow">
            </div>


            <div id="data-wrapper" class="sliderWrapper">
               <div class="slider"><!--
               --><img src="/images/github_black.png" class="slide notShown data"><!-- getting rid of goddamn whitespace
               --><img src="/images/xing_black.png" class="slide notShown data"><!--
               --><img src="/images/linkedin_black.png" class="slide notShown data">
               </div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('data')" class="arrow">
            </div>


            <div id="general-wrapper" class="sliderWrapper">
               <div class="slider"><!--
               --><img src="/images/github_black.png" class="slide notShown general"><!-- getting rid of goddamn whitespace
               --><img src="/images/xing_black.png" class="slide notShown general"><!--
               --><img src="/images/linkedin_black.png" class="slide notShown general"><!--
               --><img src="/images/github_black.png" class="slide notShown general"><!--
               --><img src="/images/xing_black.png" class="slide notShown general">
               </div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('general')" class="arrow">
            </div>


            <div id="theo-wrapper" class="sliderWrapper">
               <div class="slider"><!--
               --><img src="/images/github_black.png" class="slide notShown theo"><!-- getting rid of goddamn whitespace
               --><img src="/images/xing_black.png" class="slide notShown theo"><!--
               --><img src="/images/linkedin_black.png" class="slide notShown theo"><!--
               --><img src="/images/linkedin_black.png" class="slide notShown theo">
               </div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('theo')" class="arrow">
            </div>

            <?php/*
            <div class="sliderWrapper">
               <!--<div class="sliderConstrainer">-->
                  <img src="./images/left_arrow.png">
                  <div class="slider">
                     <img src="/images/github_black.png">
                     <img src="/images/xing_black.png">
                     <img src="/images/linkedin_black.png">
                     <img src="/images/github_black.png">
                     <img src="/images/xing_black.png">
                     <img src="/images/linkedin_black.png">
                  </div>
                  <img src="./images/right_arrow.png">
               <!--</div>-->
            </div>
            <div class="sliderWrapper">
               <!--<div class="sliderConstrainer">-->
                  <img src="./images/left_arrow.png">
                  <div class="slider">
                     <img src="/images/github_black.png">
                     <img src="/images/xing_black.png">
                     <img src="/images/linkedin_black.png">
                     <img src="/images/github_black.png">
                     <img src="/images/xing_black.png">
                     <img src="/images/linkedin_black.png">
                  </div>
                  <img src="./images/right_arrow.png">
               <!--</div>-->
            </div>*/?>
         </div>

      </div>

   </div>

   <!--<div id="currently" class="segment">


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

   </div>-


   <div id="resume" class="segment">
      <div class="heading"><h2>Resumé</h2></div>
      <div class="content"></div>
   </div>


   <div id="notebook" class="segment">
      <div class="heading"></div>
      <div class="content"></div>
   </div>-->



   <?php
      echo "difference in time concerning weather: " . ($nowTime - (getTime("./data/timeWeather.txt") / (60 * 60))) . "<br>";
      echo "difference in time concerning github repositories: " . ($nowTime - (getTime("./data/timeGithub.txt") / (60 * 60))) . "<br>";
      echo "contacted OpenWeatherMap API: " . ($wAPI ? "YES" : "NO") . "<br>";
      echo "contacted GitHub API: " . ($gAPI ? "YES" : "NO") . "<br>";
      echo "current conditions in " . $city . ": " . $weather->weather[0]->description . " with " . round($weather->main->temp - 273.15) . " °C.";
      echo "<br>";
      var_dump($weather);
      echo "<br><br>";
      var_dump($currProj);

      echo "<br><br>";
      for ($j = 10; $j <= 100; $j += 10) {
         for ($i = 0; $i <= 100; $i += 10) {
            if (($i != $j)) {
               echo "@keyframes anim".$i."to".$j."{from{width:".$i."%}to{width:".$j."%}}";
               echo ".progress".$i."to".$j."{animation-name:anim".$i."to".$j.";animation-duration:0.5s;}";
            }
         }
      }
      echo "<br>";

      for ($i = 5; $i <= 100; $i += 5) {
         echo ".progress".$i."{animation-name:anim".$i.";animation-duration:0.5s;}";
      }
   ?>
<!-- End of Page Layout -->
</body>

<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/slideTypes.js"></script>

<!-- spaeter herunterladen und gleich auf dem Server zur Verfügung stellen -->
<script type="text/javascript" src="js/libraries/p5.js"></script>
<script type="text/javascript" src="js/sphere.classes.js"></script>
<script type="text/javascript" src="js/sphere.sketch.js"></script>

</html>
