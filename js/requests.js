// I want the following problem to be solved:
// The website takes too long to load if both there is new information requested from both APIs,
// Therefore, I want placeholders for the weather and the last used repository and replace it only after
// everything else is already loaded.
// To do this, I will make an XMLHttpRequest to the website's server, where PHP scripts get the
// information from the APIs and return it to the requesting browser.

/**
 * Plan:
 * Seite fertig geladen --> requestWeather für Wetter und GitHub an PHP-Script --> kontaktiert beide Seiten
 * mit den bereits genutzten Methoden --> liefert Info zurück an Browser -->
 */
try  {
     window.onload = function () {
          try {
               let requestWeather, requestGitHub;
               if (window.XMLHttpRequest) {
                    requestWeather = new XMLHttpRequest(); // IE7+, Firefox, Chrome, Opera, Safari
                    requestGitHub = new XMLHttpRequest();
               } else {  
                    requestWeather = new ActiveXObject("Microsoft.XMLHTTP"); // IE6, IE5
                    requestGitHub = new ActiveXObject("Microsoft.XMLHTTP");
               }

               // onreadystatechange: a function has to be defined that is excuted if the requestWeather's ready state changes
               // ready state changes from 0 to 4, 0 = not initialized, 4 = response is ready
               requestWeather.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                         // if requestWeather is ready and the HTTP status says the message is okay
                         console.log(this.responseText);
                    }
               }

               let script = "http://www.dominik-hillmann.com/requests.php?type=";
               requestWeather.open("GET", script + "weather", true); // specify properties of requestWeather
               requestGitHub.open("GET", script + "github", true);
               requestWeather.send()
               // requestGitHub.send();

               // JSON.parse() um JSON-String in Objekt umzuparsen
          } catch (e) {
               console.log(e);
          }
     };
} catch (e) {
     console.log(e);
}
