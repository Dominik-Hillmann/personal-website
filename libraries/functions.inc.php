<?php
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

        if (isset($token)) {
           curl_setopt($curl, CURLOPT_HTTPHEADER, ["User-Agent: Dominik-Hillmann", $token]);
        }

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

    function toStrList($stringArr) {
        $reString = "";
        $len = count($stringArr);

        for ($i = 0; $i < $len; $i++) {
            if ($i == 0) {
                $reString.=$stringArr[$i];
            } else if ($i == $len - 1) {
                $reString .= " and " . $stringArr[$i];
            } else {
                $reString .= ", " . $stringArr[$i];
            }
        }

        return $reString;
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

        //$percentages = '';
        // var_dump($repo->langs);
        foreach ($repo->langs as $lang => $amount) /* for ($i = 0; $i < count($repo->langs); $i++) */ {
            // echo round(100 * $amount / $total) . '% ' . $lang . ' ';
            // echo '$lang' . ' ' . $repo->langs[count($repo->langs) - 1];
            echo round(100 * $amount / $total) . '% ' . $lang . ' ';
        }
        echo '</p></div></div>';
    }


    function echoSkills($path, $sliderClass, $header, $text, $introToList, $skillList) {
        // echoes the images for skills, includes placeholders
        echo '<div id="'. $sliderClass .'-wrapper" class="sliderWrapper '. $sliderClass .'-slider-wrapper">';
        echo '<div style="background-image: url(\'./images/backgrounds/background_'. $sliderClass .'.png\');" class="slider">';

        $len = count($skillList);
        $lenPlus = $len;
        while ($lenPlus % 3 != 0) {
           $lenPlus++;
        }

        for ($i = 0; $i < $lenPlus; $i++) {
           // echo '<div class="slide notShown '. $sliderClass .'Wrapper">'; // wichtig: sliderClass und Wrapper ohne Whitespace
           echo '<img src="' . $path . ($i >= $len ? "empty" : $skillList[$i]) . '.png" class="slide notShown ' . $sliderClass . '">';
           // echo '</div>';
           // echo $i >= $len ? NULL : '<p>' . $skillList[$i] . '</p></div>';
           // richtige Namen Bilder fuer Technologien
        }

        echo '</div>';
        echo '<img src="./images/arrow_in_circle.png" onclick="animateSlide(\''. $sliderClass .'\')" class="'. $sliderClass .'NavSlider arrow navSlider">';
        echo '<div class="slidername"><h1>'. $header .'</h1>';
        echo '<p class="infoText" id="'. $sliderClass .'Info">';
        echo $text . " " . $introToList . toStrList($skillList) . '.</p></div></div>';
    }

    function getLocally($path) {
        return json_decode(file_get_contents($path, "r"));
    }

    function putLocally($path, $fileName, $obj) {
        file_put_contents($path . $fileName . ".json", json_encode($obj));
    }
?>