// I want the following problem to be solved:
// The website takes too long to load if both there is new information requested from both APIs,
// Therefore, I want placeholders for the weather and the last used repository and replace it only after
// everything else is already loaded.
// To do this, I will make an XMLHttpRequest to the website's server, where PHP scripts get the
// information from the APIs and return it to the requesting browser.

let getRepo = function (repoData, picPath, picLinkURL) {
    let repoNode = document.createElement("div");
    repoNode.classList.add("repoContainer");

    // left-hand side pic
    let picNode = document.createElement("div");
    picNode.appendChild(document.createTextNode("hide me"));
    picNode.style = "background-position:center;background-size:cover;background-image:url('" + picPath + "');"; // always the same
    picNode.onclick = function () { window.open(picLinkURL, '_blank'); };
    repoNode.appendChild(picNode);

    // right-hand side with text
    let textNode = document.createElement("div");
    // heading
    let headerNode = document.createElement("h1");
    let name = document.createTextNode(repoData.data.name);
    let linkNode = document.createElement("a");
    linkNode.appendChild(name);
    linkNode.href = repoData.data.url; // ***
    headerNode.appendChild(linkNode);
    textNode.appendChild(headerNode);
    // text below
    let descriptionNode = document.createElement("p");
    let langsNode = document.createElement("p");
    let description = document.createTextNode(
        repoData.data.description == null ? "No description" : repoData.data.description
    );
    let langs = document.createTextNode(repoData.langStr);
    descriptionNode.appendChild(description);
    langsNode.appendChild(langs);

    textNode.appendChild(descriptionNode);
    textNode.appendChild(langsNode);
    
    repoNode.appendChild(textNode);
    // console.log(repoNode);
    // wieder reinhängen, davor Ladezeichen weg
    // let currRepoNode = document.getElementsByClassName("repoHelper")[1];
    // currRepoNode.getElementsByTagName("img")[0].remove();
    // currRepoNode.appendChild(repoNode);

    return repoNode;
}

window.onload = function () {
    try {

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

                /*<div class="repoContainer">
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
                </div>*/

                try {
                    // SPÄTER ZU FUNKTION
                    console.log("currrepo");
                    console.log(JSON.parse(this.responseText));
                    let repo = JSON.parse(this.responseText);
                    
                    // remove loading symbol first
                    let currRepoNode = document.getElementsByClassName("repoHelper")[1];
                    currRepoNode.getElementsByTagName("img")[0].remove();
                    currRepoNode.appendChild(getRepo(repo, "./images/backgrounds/background_web.png", repo.data.url));

                } catch (e) {
                    console.error(e);
                }
            }
        }

        let onFeatReposReady = function () {
            if (this.readyState == 4 && this.status == 200) {
                try {

                    console.log(JSON.parse(this.responseText));
                    let featRepos = JSON.parse(this.response);
                    // remove loading symbol first
                    let featReposNode = document.getElementsByClassName("repoHelper")[0];
                    featReposNode.getElementsByTagName("img")[0].remove();

                    for (featRepo of featRepos) 
                        featReposNode.appendChild(getRepo(featRepo, featRepo.picPath, featRepo.exampleURL));

                } catch (e) {
                    // print red error
                } finally { }
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

