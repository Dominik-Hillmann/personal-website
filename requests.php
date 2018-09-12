<?php
    header('Content-type: application/json');
    require "config/config.inc.php";
    require "libraries/functions.inc.php";

    $type = $_GET['type'];

    if ($type != "weather") {

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
            // get repo data locally, the last update did not happen too long ago
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

        if ($type == "currentRepo")
            echo json_encode($currRepo);
        else
            echo json_encode($featRepos);

    } else /* if (type === "weather") */ {

        /***** OPENWEATHERMAP API *****/
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

        // this allows me to define new properties which I need to send the weatherPicStr with the JSON
        $re = new stdClass;
        $re->weather = $weather;
        $re->weatherPicStr = $weatherPicStr;
          
        echo json_encode($re);
    }
?>