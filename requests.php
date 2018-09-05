<!DOCTYPE html>
<?php
     require "config/config.inc.php";
     require "libraries/functions.inc.php";

     $type = $_GET['type'];

     //if ($type === "github") {

     //} else /* if (type === "weather") */ {
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
          echo "TESTTESTTESTTESTTESTTESTTEST";
     //}
?>