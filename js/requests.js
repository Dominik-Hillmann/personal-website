// I want the following problem to be solved:
// The website takes too long to load if both there is new information requested from both APIs,
// Therefore, I want placeholders for the weather and the last used repository and replace it only after
// everything else is already loaded.
// To do this, I will make an XMLHttpRequest to the website's server, where PHP scripts get the
// information from the APIs and return it to the requesting browser.

window.onload = function () {
    try  {

        let requestWeather, requestCurrRepo, requestFeatRepos;
        if (window.XMLHttpRequest) {
            // IE7+, Firefox, Chrome, Opera, Safari
            requestWeather = new XMLHttpRequest(); 
            requestCurrRepo = new XMLHttpRequest();
            requestFeatRepos = new XMLHttpRequest();
        } else { 
            // IE6, IE5
            requestWeather = new ActiveXObject("Microsoft.XMLHTTP");
            requestCurrRepo = new ActiveXObject("Microsoft.XMLHTTP");
            requestFeatRepos = new ActiveXObject("Microsoft.XMLHTTP");
        }

        // onreadystatechange: a function has to be defined that is excuted if the requestWeather's ready state changes
        // ready state changes from 0 to 4, 0 = not initialized, 4 = response is ready
        function getRepoTemplate() {           
            return;
        }

        function onWeatherReady() {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    let weatherInfo = JSON.parse(this.responseText);
                    console.log(weatherInfo);

                    // remove gif signalling that information is being loaded
                    let weatherNode = document.getElementById("weather");
                    weatherNode.getElementsByTagName("img")[0].remove();

                    // weather symbol
                    let weatherSymbol = document.createElement("img");
                    weatherSymbol.src = weatherInfo.weatherPicStr;
                    weatherNode.appendChild(weatherSymbol);

                    // want to create node in #weather looking like <p>City XX.X°C</p>
                    let cityAndTemp = document.createTextNode(
                        weatherInfo.weather.name +
                        " " +
                        Math.round(weatherInfo.weather.main.temp - 273.15) +
                        "°C"
                    );
                    let cityNode = document.createElement("p");
                    cityNode.appendChild(cityAndTemp);
                    weatherNode.appendChild(cityNode);
                } catch (e) {
                    console.error(e);
                    // später rote Anzeige
                }
            }
        }

        function onCurrRepoReady() {
            if (this.readyState == 4 && this.status == 200) {
                /*
                <div class="repoContainer">
                    <div onclick="window.open('http://www.dominik-hillmann.com/sketches/sphere/sphere.html', '_blank');" style="background-position:center;background-size:cover;background-image:url('./images/sphere.JPG');">
                        hide me
                    </div>

                    <div>
                        <h1>
                            <a href="https://github.com/Dominik-Hillmann/Sphere">Sphere</a>
                        </h1>
                        <p>Keine Beschreibung.</p>
                        <p>92% JavaScript 8% HTML</p>
                    </div>
                </div>
                */

                console.log("currrepo");
                console.log(JSON.parse(this.responseText));
                let repo = JSON.parse(this.responseText);

                // whole repo
                let repoNode = document.createElement("div");
                repoNode.classList.add("repoContainer");

                // picture on left-hand side
                let picNode = document.createElement("div");
                let picStyle = "background-position:center;background-size:cover;background-image:url('./images/backgrounds/background_web.png')"; // always same pic here
                picNode.setAttribute("style", picStyle);
                picNode.onclick = function () {
                    window.open(repo.url, '_blank');
                }
                repoNode.appendChild(picNode);

                // text on the right-hand side
                let textNode = document.createElement("div");
                let headerNode = document.createElement("h1");
                let repoLink = document.createElement("a").appendChild(document.createTextNode(repo.name));
                
                textNode.appendChild(headerNode.appendChild(repoLink));


            }
        }

        let onFeatReposReady = function () {
            if (this.readyState == 4 && this.status == 200) {
                // if requestWeather is ready and the HTTP status says the message is okay
                console.log(JSON.parse(this.responseText));
            }
        }

        requestWeather.onreadystatechange = onWeatherReady;
        requestCurrRepo.onreadystatechange = onCurrRepoReady;
        requestFeatRepos.onreadystatechange = onFeatReposReady;

        let script = "http://www.dominik-hillmann.com/requests.php?type=";
        requestWeather.open("GET", script + "weather", true); // specify properties of requestWeather
        requestCurrRepo.open("GET", script + "currentRepo", true);
        requestFeatRepos.open("GET", script + "featRepos", true);

        requestWeather.send()
        requestCurrRepo.send();
        requestFeatRepos.send();

    } catch (e) {
        console.error(e);
        // weiterhin im betroffenen Teil der Website ausdrücken, dass etwas schief gelaufen ist.
    }
};

