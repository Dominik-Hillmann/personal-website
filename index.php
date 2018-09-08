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
      require "config/config.inc.php";
      require "libraries/functions.inc.php";
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
            <!--<img src= <?php // echo '"' . $weatherPicStr . '"'; ?> >-->
            <!--<img src="/images/weather/loading.png">
            <p><?php // echo $weatherCity . " " . round($weather->main->temp - 273.15) . "°C"; ?></p>-->
            <!--<p id="weatherLoading">...</p>-->
            <img style="height:19px;border-right:none;margin-bottom:-10px;" src="/images/loading/circle.gif" id="loadingWeather">
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
                echoSkills("/images/logo/", "web", "Web Development", $webText, $introToListENG, [
                    "JavaScript",
                    "PHP",
                    "CSS3",
                    "HTML5",
                    "P5.js"
                ]);

                echoSkills("/images/logo/", "data", "Statistics, Econometrics, Machine Learning", $dataText, $introToListENG, [
                    "R",
                    "Python",
                    "SQL",
                    "Stata"
                ]);

                echoSkills("/images/logo/", "general", "General Programming", $generalText, $introToListENG, [
                    "C",
                    "Java",
                    "Processing"
                ]);
            ?>

            <!-- <div id="theo-wrapper" class="sliderWrapper theo-slider-wrapper">
               <div class="slider"></div>
               <img src="./images/arrow_in_circle.png" onclick="animateSlide('theo')" class="dataNavSlider arrow navSlider"> 
               <img src="./images/question.png" onclick="showTypeInfo('data');" class="dataNavSlider navSlider question">
                <div class="slidername">
                  <h1>Theory</h1>
                  <p class="infoText" id="theoInfo">Das ist ein Text, der beschreiben soll, was das Thema überhaupt ist</p>
               </div>
            </div> -->
        </div>

        <div id="repos">
            <h1 class="repoHeading">Featured repositories</h1>
                <div>
                    <?php
                        // for ($i = 0; $i < count($featRepos); $i++) {
                        //     echoRepo($featRepos[$i], $featReposImgs[$i], $featExps[$i]);
                        // }
                    ?>
                    <img class="loadingRepo" src="/images/loading/circle.gif">
                </div>
                <h1 class="repoHeading">Repository I last worked on</h1>
                <div>
                    <?php
                        // these have no predefined examples like the featured repos --> use their own GitHub page as subsitute
                        // echoRepo($currRepo, "images/backgrounds/background_web.png", $currRepo->url);
                    ?>
                    <img class="loadingRepo" src="/images/loading/circle.gif">
                </div>
        </div>

    </div>


    <div id="contact">
        <div id="socialHeaders">
            <h1>Contact</h1>
            <h1>Social Media</h1>
        </div>
        <div id="contactForm">
            <form action = "contact.php" method="post">
                <div>
                    <div class="twoinrow">
                        <h3 class="formHeading">First name</h3>
                        <input type="text" name="firstname">
                    </div><div class="twoinrow"><!-- to avoid whitespace -->
                        <h3 class="formHeading">Last name</h3>
                        <input type="text" name="lastname">
                    </div>
                </div>
                <p>
                    <h3 class="formHeading">E-Mail address</h3>
                    <input type="text" name="address">
                </p><!--
             --><p>
                    <h3 class="formHeading">Telephone</h3>
                    <input type="text" name="telnum">
                </p><!--
             --><p>
                    <h3 class="formHeading">Subject</h3>
                    <input type="text" name="subject">
                </p><!--
             --><p>
                    <h3 class="formHeading">Message</h3>
                    <textarea id="message" name="message"></textarea>
                </p>
                <p>
                    <div class="g-recaptcha" data-sitekey="6LfN2VcUAAAAAJkZ542qnEj2SmZ0adjE-w18YWtU"></div>
                    <input id="submit" type="submit">
                    <!--<a href="#">Resumé</a>-->
                </p>
            </form>
            <a id="impressum" href="http://www.dominik-hillmann.com/impressum.html"><p>Impressum</p></a>
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
        <?php
            // echo "difference in time concerning weather: " . ($nowTime - (getTime("./data/timeWeather.txt") / (60 * 60))) . "<br>";
            // echo "difference in time concerning github repositories: " . ($nowTime - (getTime("./data/timeGithub.txt") / (60 * 60))) . "<br>";
            // echo "contacted OpenWeatherMap API: " . ($wAPI ? "YES" : "NO") . "<br>";
            // echo "contacted GitHub API: " . ($gAPI ? "YES" : "NO") . "<br>";
            // echo "current conditions in " . $weatherCity . ": " . $weather->weather[0]->description . " with " . round($weather->main->temp - 273.15) . " °C.";
            // echo "<br>";
            // var_dump($weather);
            // echo "<br><br>";
            // var_dump($currRepo);
            // echo "<br><br>";
            // var_dump($featRepos);
            // echo "<br>";
            //
            // foreach ($repos as $repo) {
            //    echo "<br>";
            //    var_dump($repo->langs);
            // }
        ?>
    </div>

</div>
<!-- End of Page Layout -->
</body>

<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/slides.js"></script>
<script type="text/javascript" src="js/requests.js"></script>
</html>
