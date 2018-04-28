<!DOCTYPE html>
<?php
   // Open WeatherAPI
   $weatherKey = "dfc5381a15a6aea6bea3bcb0ef26a045";
   $weatherCity = "Mannheim";
   $weatherTimeDif = 1.0; // max time since weather was updated in hours

   // GitHub API
   $githubToken = "2bf667677f505be15bc7ade57fd3ec2ab45c73bb";
   $githubTimeDif = 24.0; // maximum number of hours since latest update of repositories
   $featNames = ["Vibrating-Line", "Sphere"]; // names of repos that are to be displayed under "featured repositories"
   $featReposImgs = []; // urls of imgs to be displayed next to repo in $featNames, needs to be in same order as $featnames
   $picURL = "https://avatars1.githubusercontent.com/u/21293336?v=4";
   
   // Skill Lists
   $webList = [
      "html5",
      "css3",
      "js",
      "p5",
      "php",
      "processing",
      "R",
      "stata"
   ];
   $dataList = [];
   $generalList = [];
   $theoList = [];
?>
