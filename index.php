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
         if (isset($token))
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["User-Agent: Dominik-Hillmann", $token]);

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
      $appid = "9fe71167771b980192490c2eb67c98bf";
      $city = "Mannheim";
      // check the last time the weather.json file was updated
      $nowTime = time() / (60 * 60);
      /*$lastTimeTxt = fopen("./data/timeWeather.txt", "r");
      $lastTime = ((int) fgets($lastTimeTxt)) / (60 * 60);
      fclose($lastTimeTxt);*/
      $lastTime = getTime("./data/timeWeather.txt") / (60 * 60);
      echo ($nowTime - $lastTime) . "<br>";

      // get weather data from API or JSON dependend on how long ago the last update happened (1 hour)
      $weather;
      // less than one hour ago --> use the JSON
      if (($nowTime - $lastTime) <= 1.0) {
         echo "Nehme alte Datei.<br>";
         $weather = json_decode(file_get_contents("./data/weather.json", "r"));
      } else {
         // more than one hour ago --> contact weather API
         echo "Kontaktiere API<br>";
         $jsonW = contactAPI("http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&appid=" . $appid, NULL);

         if (isset($jsonW)) { // if successfully contacted the API --> give data to weather and write into weather.json
            echo "Contacted Weather API. " . "JSON: " . $jsonW . "<br>";
            $weather = json_decode($jsonW);
            file_put_contents("./data/weather.json", json_encode($weather));
            putTime("./data/timeWeather.txt");
         } else {
            echo "Could NOT contact Weather API.<br>";
            $weather = json_decode(file_get_contents("./data/weather.json", "r"));
         }
      }
      echo "<br>current conditions in " . $city . ": " . $weather->weather[0]->description . "<br>";


      /***** GitHub API *****/
      $githubToken = "";
      $ReposUrl = "https://api.github.com/users/Dominik-Hillmann/repos";
      $curlToken = "Authorization: token " . $githubToken;

      echo "HOURS SINCE LAST UPDATE: " . ($nowTime - getTime("./data/timeGithub.txt") / (60 * 60)) . "<br>";

      $lastUpdate = getTime("./data/timeGithub.txt") / (60 * 60);

      $repos = [];
      if (($nowTime - $lastUpdate) >= 24.0) {
         // contactAPI GitHub API because the data on the repositories is older than 24 hours
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
         echo "UPDATED REPOS<br>";
      } else {
         // data is younger than 24 hours --> loop through every json and add to repos[]
         $files = scandir("./data/repos/");
         unset($files[0]);
         unset($files[1]);

         foreach ($files as $file) {
            array_push(
               $repos,
               json_decode(file_get_contents("./data/repos/" . $file))
            );
         }

         echo "GOT OLD REPOS<br>";
      }

      // select project with most recent update
      $currProj = $repos[0];
      for ($i = 1; $i < count($repos); $i++) {
         if ($repos[$i]->lastUpdate > $currProj->lastUpdate)
            $currProj = $repos[$i];
      }

      echo "<br>";
      var_dump($currProj);

      $test = strtotime("2018-02-27T21:05:53Z");
      echo date("Y-m-d H:i:s", $test) . "<br>";
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
