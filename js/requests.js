// I want the following problem to be solved:
// The website takes too long to load if both there is new information requested from both APIs,
// Therefore, I want placeholders for the weather and the last used repository and replace it only after
// everything else is already loaded.
// To do this, I will make an XMLHttpRequest to the website's server, where PHP scripts get the
// information from the APIs and return it to the requesting browser.

window.onload = function () {
     try {
          let requestWeather, requestCurrRepo, requestFeatRepos;

          if (window.XMLHttpRequest) {
               requestWeather = new XMLHttpRequest(); // IE7+, Firefox, Chrome, Opera, Safari
               requestCurrRepo = new XMLHttpRequest();
               requestFeatRepos = new XMLHttpRequest();
          } else {  
               requestWeather = new ActiveXObject("Microsoft.XMLHTTP"); // IE6, IE5
               requestCurrRepo = new ActiveXObject("Microsoft.XMLHTTP");
               requestFeatRepos = new ActiveXObject("Microsoft.XMLHTTP");
          }

          // onreadystatechange: a function has to be defined that is excuted if the requestWeather's ready state changes
          // ready state changes from 0 to 4, 0 = not initialized, 4 = response is ready
          function onreadystatechange() {
               if (this.readyState == 4 && this.status == 200) {
                    // if requestWeather is ready and the HTTP status says the message is okay
                    console.log(JSON.parse(this.responseText));
               }
          }

          requestWeather.onreadystatechange = onreadystatechange;
          requestCurrRepo.onreadystatechange = onreadystatechange;
          requestFeatRepos.onreadystatechange = onreadystatechange;

          let script = "http://www.dominik-hillmann.com/requests.php?type=";
          requestWeather.open("GET", script + "weather", true); // specify properties of requestWeather
          requestCurrRepo.open("GET", script + "currentRepo", true);
          requestFeatRepos.open("GET", script + "featRepos", true);

          requestWeather.send()
          requestCurrRepo.send();
          requestFeatRepos.send();

     } catch (e) {
          console.log(e);

          // weiterhin im betroffenen Teil der Website ausdrücken, dass etwas schief gelaufen ist.
     }
};

