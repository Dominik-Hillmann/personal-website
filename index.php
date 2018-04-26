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
      require "libraries/config.inc.php";

      class Repo {
         // stores all needed data for the GitHub projects I want to display
         public $name;
         public $lastUpdate;
         public $description;
         public $langs;
         public $url;

         public function __construct($name, $lastUpdate, $description, $langs, $url) {
            $this->name = $name;
            $this->description = $description;
            $this->lastUpdate = strtotime($lastUpdate); // UNIX timestamp
            $this->langs = $langs;
            $this->url = $url;
         }
      }


      function contactAPI($url, $token) {
         // accesses wather and github API via curl
         $curl = curl_init($url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         if (isset($token)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["User-Agent: Dominik-Hillmann", $token]);
         }
         $outputs = curl_exec($curl);
         curl_close($curl);
         return $outputs;
      }

      function putTime($path) {
         // writes current time into .txt files
         $lastTime = fopen($path, "w");
         fputs($lastTime, (string) time());
         fclose($lastTime);
         return;
      }

      function getTime($path) {
         // time from .txt file as UNIX timestamp
         $timef = fopen($path, "r");
         $time = (int) fgets($timef);
         fclose($timef);
         return $time;
      }


      function getPic($adress, $savePath, $timePath) {
         // updates the current picture of me
         $lastTime = getTime($timePath) / (60 * 60);
         $nowTime = time() / (60 * 60);

         if ($nowTime - $lastTime > 30 * 24) {
            $curl = curl_init($adress);
            $file = fopen($savePath, "wb");

            curl_setopt($curl, CURLOPT_FILE, $file);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_exec($curl);

            curl_close($curl);
            fclose($file);

            putTime($timePath);
         }
      }


      function echoRepo($repo) {
         /*<div class="repoContainer">
            <div>hide me</div>
            <div>
               <h1>Titel</h1>
               <p>Beschreibung</p>
            </div>
            <p>Anteile der Sprachen</p>
         </div> */
         echo '<div class="repoContainer"><div>hide me</div><div>';
         echo '<h1><a href="' . $repo->url . '">' . $repo->name . '</a></h1>';
         echo '<p>' . ($repo->description ? $repo->description : "Keine Beschreibung") . '</p><p>';

         $total = 0.0;
         foreach ($repo->langs as $amount) {
            $total += $amount;
         }

         foreach ($repo->langs as $lang => $amount) {
            echo round(100 * $amount / $total) . "% " . $lang . " ";
         }
         echo '</p></div></div>';
      }


      function echoSkills($path, $sliderClass, $skillList) {
         // echoes the images for skills, includes placeholders
         $len = count($skillList);
         $lenPlus = $len;
         while ($lenPlus % 3 != 0) {
            $lenPlus++;
         }
         for ($i = 0; $i < $lenPlus; $i++) {
            echo '<img src="' . $path . ($i >= $len ? "empty" : $skillList[$i]) . '.png" class="slide notShown ' . $sliderClass . '">';
         }
      }

      /***** OPENWEATHERMAP API *****/
      $appid = $weatherKey;
      // check the last time the weather.json file was updated
      $nowTime = time() / (60 * 60);
      $weatherUpd = getTime("./data/timeWeather.txt") / (60 * 60);
      $weather;
      $wAPI = false;

      // less than one hour ago --> use the JSON
      if (($nowTime - $weatherUpd) <= $weatherTimeDif) {
         $weather = json_decode(file_get_contents("./data/weather.json", "r"));
      } else {
         $jsonW = contactAPI(
            "http://api.openweathermap.org/data/2.5/weather?q=" . $weatherCity . "&appid=" . $appid,
            NULL
         );
         $wAPI = true;

         if (isset($jsonW)) {
            $weather = json_decode($jsonW);
            file_put_contents("./data/weather.json", json_encode($weather));
            putTime("./data/timeWeather.txt");
         } else {
            $weather = json_decode(file_get_contents("./data/weather.json", "r"));
         }
      }


      /***** GITHUB API *****/
      $reposUrl = "https://api.github.com/users/Dominik-Hillmann/repos";
      $curlToken = "Authorization: token " . $githubToken;

      $githubUpd = getTime("./data/timeGithub.txt") / (60 * 60);
      // contact the API if the GitHub-json files are older than one day
      $repos = [];
      $gAPI = false;

      if (($nowTime - $githubUpd) >= $githubTimeDif) {
         $repoData = json_decode(contactAPI($reposUrl, $curlToken));
         foreach ($repoData as $repo) {
            // put all relevant data into objects
            array_push($repos, new Repo(
               $repo->name,
               $repo->pushed_at,
               $repo->description,
               json_decode(contactAPI($repo->languages_url, $curlToken)),
               $repo->html_url
            ));
         }
         // (over)write files
         foreach ($repos as $repo) {
            file_put_contents(
               "./data/repos/" . $repo->name . ".json",
               json_encode($repo)
            );
         }

         putTime("./data/timeGithub.txt"); // update time because the data got updated
         $gAPI = true;

      } else {

         // get repo data locally
         $files = scandir("./data/repos/");
         unset($files[0]); // delete ".", ".."
         unset($files[1]);

         foreach ($files as $file) {
            array_push($repos, json_decode(file_get_contents("./data/repos/" . $file)));
         }
      }

      // select project with most recent update
      $currRepo = $repos[0];
      for ($i = 1; $i < count($repos); $i++) {
         if ($repos[$i]->lastUpdate > $currRepo->lastUpdate) {
            $currRepo = $repos[$i];
         }
      }

      // select featured repos
      $featRepos = [];
      foreach ($repos as $repo) {
         foreach ($featNames as $name) {
            if ($repo->name == $name) {
               array_push($featRepos, $repo);
            }
         }
      }


      /***** CONSTRUCTIONG WEATHER SYMBOLS *****/
      $weatherPicStr = "./images/weather/bright_";
      $hour = (int) date("G", time());

      if ($hour >= 20 || $hour <= 6) {
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
         <img src= <?php echo '"' . $weatherPicStr . '"'; ?> >
         <p><?php echo $weatherCity . " " . round($weather->main->temp - 273.15) . "°C"; ?></p>
      </div>
      <div id="menuwrapper">
         <!-- Teile: "Ganz oben mit p5-Sketch", Kontakt, Skills, Resumé, brief history [reading, ], notebook (quasi blog)-->
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">START</a></div>
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">SKILLS</a></div>
         <div><img src="images/hollow_circle.png"><a class="unshown" href="#">KONTAKT</a></div>
         <!--später blank.png, mit css hollow und full änder per CSS-->
      </div>
   </header>


   <div id="sketch">
   </div>


   <div id="main">
      <div id="start">
         <?php getPic($picURL, "./images/me.png", "./data/timePic.txt"); ?>
         <img src="./images/me.png">
         <!-- HIER FOTO VIA GITHUB API NEBEN TEXT -->
         <!-- SPAETER TIMELINE -->
         <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
         <?php
            echo "difference in time concerning weather: " . ($nowTime - (getTime("./data/timeWeather.txt") / (60 * 60))) . "<br>";
            echo "difference in time concerning github repositories: " . ($nowTime - (getTime("./data/timeGithub.txt") / (60 * 60))) . "<br>";
            echo "contacted OpenWeatherMap API: " . ($wAPI ? "YES" : "NO") . "<br>";
            echo "contacted GitHub API: " . ($gAPI ? "YES" : "NO") . "<br>";
            echo "current conditions in " . $weatherCity . ": " . $weather->weather[0]->description . " with " . round($weather->main->temp - 273.15) . " °C.";
            echo "<br>";
            var_dump($weather);
            echo "<br><br>";
            var_dump($currRepo);
            echo "<br><br>";
            var_dump($featRepos);
            echo "<br>";
            foreach ($repos as $repo) {
               echo "<br>";
               var_dump($repo->langs);
            }
         ?>
      </div>

      <div id="skills">

         <div id="skillSlider">
            <h1>Skills</h1>
            <div id="web-wrapper" class="sliderWrapper web-slider-wrapper">
               <div class="slider">
                  <?php
                     echoSkills("/images/logo/", "web", [
                        "html5",
                        "css3",
                        "js",
                        "p5",
                        "php",
                        "processing",
                        "R",
                        "stata"
                     ]);
                  ?>
               </div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('web')" class="webNavSlider arrow navSlider">
               <!--<img src="./images/question.png" onclick="showTypeInfo('web');" class="webNavSlider navSlider question">-->
               <div class="slidername">
                  <h1>WEB-PROGRAMMIERUNG</h1>
                  <p class="infoText" id="webInfo">Das ist ein Text, der beschreiben soll, was das Thema überhaupt ist</p>
               </div>
            </div>


            <div id="data-wrapper" class="sliderWrapper data-slider-wrapper">
               <div class="slider">
                  <?php
                     echoSkills("/images/", "data", [
                        "github_black",
                        "github_black",
                        "linkedin_black",
                        "linkedin_black"
                     ]);
                  ?>
               </div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('data')" class="dataNavSlider arrow navSlider">
               <!--<img src="./images/question.png" onclick="showTypeInfo('data');" class="dataNavSlider navSlider question">-->
               <div class="slidername">
                  <h1>DATA_ENGINEERING</h1>
                  <p class="infoText" id="dataInfo">Das ist ein Text, der beschreiben soll, was das Thema überhaupt ist</p>
               </div>
            </div>


            <div id="general-wrapper" class="sliderWrapper general-slider-wrapper">
               <div class="slider">
                  <?php
                     echoSkills("/images/", "general", [
                        "xing_black",
                        "github_black",
                        "linkedin_black"
                     ]);
                  ?>
               </div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('general')" class="dataNavSlider arrow navSlider">
               <!--<img src="./images/question.png" onclick="showTypeInfo('data');" class="dataNavSlider navSlider question">-->
               <div class="slidername">
                  <h1>GENERAL PROGRAMMING</h1>
                  <p class="infoText" id="generalInfo">Das ist ein Text, der beschreiben soll, was das Thema überhaupt ist</p>
               </div>
            </div>


            <div id="theo-wrapper" class="sliderWrapper theo-slider-wrapper">
               <div class="slider">
                  <?php
                     echoSkills("/images/", "theo", [
                        "github_black",
                        "xing_black"
                     ]);
                  ?>
               </div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('theo')" class="dataNavSlider arrow navSlider">
               <!--<img src="./images/question.png" onclick="showTypeInfo('data');" class="dataNavSlider navSlider question">-->
               <div class="slidername">
                  <h1>THEO</h1>
                  <p class="infoText" id="theoInfo">Das ist ein Text, der beschreiben soll, was das Thema überhaupt ist</p>
               </div>
            </div>

         </div>

         <div id="repos">
            <h1 class="repoHeading">Featured repositories</h1>
               <div>
                  <?php
                     foreach ($featRepos as $featRepo) {
                        echoRepo($featRepo);
                     }
                  ?>
               </div>
               <h1 class="repoHeading">Repository I last worked on</h1>
               <div>
                  <?php echoRepo($currRepo); ?>
               </div>
         </div>

      </div>


      <div id = "contact">
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
               </p><!--
            --><p>
                  <h3 class="formHeading">Telefonnummer</h3>
                  <input type="text" name="telnum">
               </p><!--
            --><p>
                  <h3 class="formHeading">Betreff</h3>
                  <input type="text" name="subject">
               </p><!--
            --><p>
                  <h3 class="formHeading">Nachricht</h3>
                  <textarea id="message" name="message"></textarea>
               </p><!--
            --><p>
                  <input id="submit" class="nonWriteInput" type="submit">
                  <!--<a href="#">Resumé</a>-->
               </p>
            </form>
         </div>

         <div id="socialMedia">
            <div>
               <a href="https://github.com/Dominik-Hillmann"><img src="/images/github_black.png"></a>
               <h1>GITHUB</h1>
            </div>
            <div>
               <a href="https://www.linkedin.com/in/dominik-hillmann-a0a21015a/"><img src="/images/linkedin_black.png"></a>
               <h1>LINKEDIN</h1>
            </div>
            <div>
               <a href="https://www.xing.com/profile/Dominik_Hillmann/"><img src="images/xing_black.png"></a>
               <h1>XING</h1>
            </div>
         </div>

      </div>

   </div>
<!-- End of Page Layout -->
</body>

<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/slides.js"></script>

<script type="text/javascript" src="js/libraries/p5.js"></script>
<script type="text/javascript" src="js/sketches.js"></script>

</html>
