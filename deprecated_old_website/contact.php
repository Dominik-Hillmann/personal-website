<!DOCTYPE html>
<html lang="de">
<head>
<!-- Everything well done is worthwhile. -->
   <!-- Global site tag (gtag.js) - Google Analytics -->
   <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118780357-1"></script>
   <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() { dataLayer.push(arguments); }
      gtag("js", new Date());
      gtag("config", "UA-118780357-1");
   </script>

<!-- Basic Page Needs -->
    <meta charset="utf-8">
    <title>Dominik Hillmann</title>
    <meta name="description" content="Hi, my name is Dominik Hillmann and I am currently studying Economics and Computer Science. My free time is mostly spent on programming. You can view some featured projects further below or take a look at my GitHub profile to see all of them. Besides programming and studying I do sports and read as often as I can.">
    <meta name="author" content="Dominik Hillmann">

<!-- Mobile Specific Metas -->
    <meta>

<!-- CSS links -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/mobile.css">

<!-- Favicon -->
    <link rel="icon" type="image/png" href="">
    <script src='https://www.google.com/recaptcha/api.js'></script>
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
         // accesses weather and github API via curl
         $curl = curl_init($url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

         if (isset($token)) curl_setopt($curl, CURLOPT_HTTPHEADER, ["User-Agent: Dominik-Hillmann", $token]);

         $output = curl_exec($curl);
         curl_close($curl);
         return $output;
      }


      function putTime($path) {
         // writes current time into .txt files
         $lastTime = fopen($path, "w");
         fputs($lastTime, (string) time());
         fclose($lastTime);
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
            // 30 * 24 => every month
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


      function echoRepo($repo, $imgStr, $exampleURL) {
         /* This echoes HTML that has the following structure:
         <div class="repoContainer">
            <div>hide me</div>
            <div>
               <h1>Titel</h1>
               <p>Beschreibung</p>
            </div>
            <p>Anteile der Sprachen</p>
         </div> */
         echo '<div class="repoContainer"><div onclick="window.open(\''. $exampleURL .'\', \'_blank\');" style="background-position:center;background-size:cover;background-image:url(\''. $imgStr .'\');">hide me</div><div>';
         echo '<h1><a href="' . $repo->url . '">' . $repo->name . '</a></h1>';
         echo '<p>' . ($repo->description ? $repo->description : "Keine Beschreibung.") . '</p><p>';

         $total = 0.0;
         foreach ($repo->langs as $amount) $total += $amount;

         foreach ($repo->langs as $lang => $amount) {
            echo round(100 * $amount / $total) . '% ' . $lang . ' ';
         }
         echo '</p></div></div>';
      }


      function echoSkills($path, $sliderClass, $header, $text, $skillList) {
         // echoes the images for skills, includes placeholders
         echo '<div id="'. $sliderClass .'-wrapper" class="sliderWrapper '. $sliderClass .'-slider-wrapper">';
         echo '<div style="background-image: url(\'./images/backgrounds/background_'. $sliderClass .'.png\');" class="slider">';

         $len = count($skillList);
         $lenPlus = $len;
         while ($lenPlus % 3 != 0) $lenPlus++;
         for ($i = 0; $i < $lenPlus; $i++) {
            echo '<img src="' . $path . ($i >= $len ? "empty" : $skillList[$i]) . '.png" class="slide notShown ' . $sliderClass . '">';
         }

         echo '</div>';
         echo '<img src="./images/arrow_in_circle.png" onclick="animateSlide(\''. $sliderClass .'\')" class="'. $sliderClass .'NavSlider arrow navSlider">';
         echo '<div class="slidername"><h1>'. $header .'</h1>';
         echo '<p class="infoText" id="'. $sliderClass .'Info">'. $text .'</p></div></div>';
      }

      function getLocally($path) {
         return json_decode(file_get_contents($path, "r"));
      }

      function putLocally($path, $fileName, $obj) {
         file_put_contents($path . $fileName . ".json", json_encode($obj));
      }

      /***** OPENWEATHERMAP API *****/;
      // check the last time the weather.json file was updated
      $nowTime = time() / (60 * 60);
      $weatherUpd = getTime("./data/timeWeather.txt") / (60 * 60);
      $weather;
      $wAPI = false;

      // less than one hour ago --> use the JSON
      if (($nowTime - $weatherUpd) <= $weatherTimeDif) {
         $weather = getLocally("./data/weather.json");
      } else {
         $weatherJSON = contactAPI("http://api.openweathermap.org/data/2.5/weather?q=" . $weatherCity . "&appid=" . $weatherKey, NULL);
         $wAPI = true;

         if (isset($weatherJSON)) {
            $weather = json_decode($weatherJSON);
            file_put_contents("./data/weather.json", json_encode($weather));
            putTime("./data/timeWeather.txt");
         } else {
            $weather = getLocally("./data/weather.json");
         }
      }


      /***** GITHUB API *****/
      $reposUrl = "https://api.github.com/users/Dominik-Hillmann/repos";
      $curlToken = "Authorization: token " . $githubToken;

      $githubUpd = getTime("./data/timeGithub.txt") / (60 * 60);
      // contact the API if the GitHub-json files are older than one day
      $repos = [];
      $gAPI = false;

      if ($nowTime - $githubUpd >= $githubTimeDif) {
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
            array_push($repos, getLocally("./data/repos/" . $file));
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


      /***** CONSTRUCTIONG STRINGS FOR WEATHER SYMBOLS *****/
      $weatherPicStr = "./images/weather/" . ($useBrightSymbols ? "bright" : "dark") . "_";
      $hour = (int) date("G", time());
      $weatherPicStr .= ($hour >= $darkHours[0] || $hour <= $darkHours[1]) ? "night_" : "day_";

      $id = $weather->weather[0]->id;
      if (($id >= 200 && $id < 300) || $id >= 900) {
         $weatherPicStr .= "storm.png";
      } else if ($id >= 300 && $id < 600) {
         $weatherPicStr .= "rain.png";
      } else if ($id >= 600 && $id < 701) {
         $weatherPicStr .= "snow.png";
      } else if ($id >= 701 && $id < 800) {
         $weatherPicStr .= "mist.png";
      } else if ($id == 800) {
         $weatherPicStr .= "clear.png";
      } else if ($id >= 801 && $id < 900) {
         $weatherPicStr .= "cloud.png";
      } else {
         $weatherPicStr .= "cloud.png";
      }
   ?>

<!-- Page Layout -->
   <div id="lang">
      <a href="./de.php"><img src="images\de.png"></a>
   </div>
   <header>
      <div id="menuwrapper">
         <!-- Teile: "Ganz oben mit p5-Sketch", Kontakt, Skills, Resumé, brief history [reading, ], notebook (quasi blog)-->
         <div><img src="images/hollow_circle.png"><p class="unshown">Welcome</p></div>
         <div><img src="images/hollow_circle.png"><p class="unshown">Skills</p></div>
         <div><img src="images/hollow_circle.png"><p class="unshown">Contact</p></div>
         <!--später blank.png, mit css hollow und full änder per CSS-->
      </div>
   </header>


   <div id="sketch"></div>


   <div id="main">
      <div id="weather">
         <img src= <?php echo '"' . $weatherPicStr . '"'; ?> >
         <p><?php echo $weatherCity . " " . round($weather->main->temp - 273.15) . "°C"; ?></p>
      </div>
      <div id="start">
         <div id="hello">
            <?php getPic($picURL, "./images/me.png", "./data/timePic.txt"); ?>
            <img src="./images/me.png">
            <div>
               <h1>Welcome</h1>
               <p>
                  Hi, my name is Dominik Hillmann and I am currently studying Economics and Computer Science.
                  My free time is mostly spent on programming. You can view some featured projects further below or take a look at my GitHub profile to see all of them.
                  Besides programming and studying I do sports and read as often as I can.
               </p>
            </div>
         </div>
      </div>

      <div id="skills">

         <div id="skillSlider">
            <h1 class="mainHeader">Skills</h1>

            <?php
               echoSkills("/images/logo/", "web", "Web Development", $webText, [
                  "js",
                  "php",
                  "css3",
                  "html5",
                  "p5"
               ]);

               echoSkills("/images/logo/", "data", "Statistics", $dataText, [
                  "R",
                  "python",
                  "sql",
                  "stata"
               ]);

               echoSkills("/images/logo/", "general", "General Programming", $generalText, [
                  "c",
                  "java",
                  "processing"
               ]);
            ?>

            <!-- <div id="theo-wrapper" class="sliderWrapper theo-slider-wrapper">
               <div class="slider"> -->
               <!-- </div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('theo')" class="dataNavSlider arrow navSlider"> -->
               <!--<img src="./images/question.png" onclick="showTypeInfo('data');" class="dataNavSlider navSlider question">-->
               <!-- <div class="slidername">
                  <h1>Theory</h1>
                  <p class="infoText" id="theoInfo">Das ist ein Text, der beschreiben soll, was das Thema überhaupt ist</p>
               </div>
            </div> -->
         </div>

         <div id="repos">
            <h1 class="repoHeading">Featured repositories</h1>
               <div>
                  <?php
                     for ($i = 0; $i < count($featRepos); $i++) {
                        echoRepo($featRepos[$i], $featReposImgs[$i], $featExps[$i]);
                     }
                  ?>
               </div>
               <h1 class="repoHeading">Repository I last worked on</h1>
               <div>
                  <?php
                     // these have no predefined examples like the featured repos --> use their own GitHub page as subsitute
                     echoRepo($currRepo, "images/backgrounds/background_web.png", $currRepo->url);
                  ?>
               </div>
         </div>

      </div>

      <div id="contact">
         <div id="socialHeaders">
            <h1>Kontakt</h1>
            <h1>Social Media</h1>
         </div>
         <div id="contactForm">
            <h1>Kontaktanfrage</h1>
            <?php
               $data = [
                  'secret' => $privCaptcha,
                  'response' => $_POST["g-recaptcha-response"]
               ];

               $verify = curl_init();
               curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
               curl_setopt($verify, CURLOPT_POST, true);
               curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
               curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
               curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
               $response = json_decode(curl_exec($verify));

               $formFilled = true;
               foreach ($_POST as $ele) {
                  if (!isset($ele)) {
                     $formFilled = false;
                     break;
                  }
               }

               echo '<p id="response">';;
               if ($response->success) {
                  if ($formFilled) {
                     $receiver = "dominik.hillmann.website@gmail.com";
                     $subject = $_POST["subject"];

                     $message = $_POST["message"];
                     $message .= "\nvon " . $_POST["firstname"] . " " . $_POST["lastname"] . " <" .  $_POST["address"] . ">\r\n";
                     $message .= "Telefonnummer: " . $_POST["telnum"];

                     $headers = "Reply-To: " . $_POST["firstname"] . " " . $_POST["lastname"] . " <" .  $_POST["address"] . ">\r\n";
                     $headers .= "Return-Path: " . $_POST["firstname"] . " " . $_POST["lastname"] . " <" .  $_POST["address"] . ">\r\n";
                     $headers .= "MIME-Version: 1.0\r\n";
                     $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
                     $headers .= "X-Priority: 3\r\n";
                     $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";

                     $success = mail($receiver, $subject, $message, $headers);
                     echo ($success ? "Mail successfully sent." : "Error: Mail was not sent.");
                     mail("dominik.hillmann@gmx.de", $subject, $message, $headers);
                  } else {
                     echo "Not every field was filled in. The mail was not sent.";
                  }
               } else {
                  echo "reCaptcha concluded that you are a bot. If this is a misunderstanding, please try again.";
               }
               echo "</p>";
            ?>
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
<script>
   document.querySelector("#contact").scrollIntoView({
      behavior: "smooth"
   });
</script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/slides.js"></script>

</html>
