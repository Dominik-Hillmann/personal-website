// I want the following problem to be solved:
// The website takes too long to load if both there is new information requested from both APIs,
// Therefore, I want placeholders for the weather and the last used repository and replace it only after
// everything else is already loaded.
// To do this, I will make an XMLHttpRequest to the website's server, where PHP scripts get the
// information from the APIs and return it to the requesting browser.

let getRepo = function (repoData, picPath, picLinkURL) {
    // return node containing a whole repo

    let repoNode = document.createElement("div");
    repoNode.classList.add("repoContainer");

    // left-hand side pic
    let picNode = document.createElement("div");
    picNode.appendChild(document.createTextNode("hide me"));
    picNode.style = "background-position:center;background-size:cover;background-image:url('" + picPath + "');"; 
    picNode.onclick = function () { window.open(picLinkURL, '_blank'); };
    repoNode.appendChild(picNode);

    // right-hand side with text
    let textNode = document.createElement("div");
    // heading
    let headerNode = document.createElement("h1");
    let name = document.createTextNode(repoData.data.name);
    let linkNode = document.createElement("a");
    linkNode.appendChild(name);
    linkNode.href = repoData.data.url;
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
    return repoNode;
}


let getErrorRepo = function (error) {
    // error message will be inserted into a displayed repo

    let repoNode = document.createElement("div");
    repoNode.classList.add("repoContainer");

    // left-hand side pic
    let picNode = document.createElement("div");
    picNode.appendChild(document.createTextNode("hide me"));
    picNode.style = "background-position:center;background-size:cover;background-image:url('./images/backgrounds/background_web.png');"; 
    // picNode.onclick = function () { window.open(picLinkURL, '_blank'); };
    repoNode.appendChild(picNode);

    // right-hand side with text
    let textNode = document.createElement("div");
    // heading
    let headerNode = document.createElement("h1");
    let name = document.createTextNode("Error");
    let linkNode = document.createElement("a");
    linkNode.appendChild(name);
    headerNode.appendChild(linkNode);
    textNode.appendChild(headerNode);
    // text below
    let descriptionNode = document.createElement("p");
    let langsNode = document.createElement("p");
    let description = document.createTextNode("Something went wrong:");
    let langs = document.createTextNode(error.message);
    descriptionNode.appendChild(description);
    langsNode.appendChild(langs);

    textNode.appendChild(descriptionNode);
    textNode.appendChild(langsNode);
    textNode.style = "color:red;"
    
    repoNode.appendChild(textNode);
    return repoNode;
}


window.onload = function () {
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
    let onWeatherReady = function () {
        if (this.readyState == 4 && this.status == 200) {
            // remove gif signalling that information is being loaded
            let weatherNode = document.getElementById("weather");
            weatherNode.getElementsByTagName("img")[0].remove();

            try {
                let weatherInfo = JSON.parse(this.responseText);
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
                let errorNode = document.createElement("p");
                errorNode.style = "color:red;";
                let errorInfo = document.createTextNode(e.message);
                errorNode.appendChild(errorInfo);
                weatherNode.appendChild(errorNode);
            }
        }
    }


    let onCurrRepoReady = function () {
        if (this.readyState == 4 && this.status == 200) {
            // remove loading symbol first
            let currRepoNode = document.getElementsByClassName("repoHelper")[1];
            currRepoNode.getElementsByTagName("img")[0].remove();
            try {                    
                let repo = JSON.parse(this.responseText);
                
                currRepoNode.appendChild(getRepo(repo, "./images/backgrounds/background_web.png", repo.data.url));
            } catch (e) {
                console.error(e);
                let errorRepo = getErrorRepo(e);
                currRepoNode.appendChild(errorRepo);
            }
        }
    }


    let onFeatReposReady = function () {
        if (this.readyState == 4 && this.status == 200) {
            // remove loading symbol first
            let featReposNode = document.getElementsByClassName("repoHelper")[0];
            featReposNode.getElementsByTagName("img")[0].remove();

            try {
                let featRepos = JSON.parse(this.response);

                for (featRepo of featRepos) 
                    featReposNode.appendChild(getRepo(featRepo, featRepo.picPath, featRepo.exampleURL));

            } catch (e) {
                console.error(e);
                let errorRepo = getErrorRepo(e);
                featReposNode.appendChild(errorRepo);
            }
        }
    }


    requestWeather.onreadystatechange = onWeatherReady;
    requestCurrRepo.onreadystatechange = onCurrRepoReady;
    requestFeatRepos.onreadystatechange = onFeatReposReady;

    let scriptLink = "http://www.dominik-hillmann.com/requests.php?type=";
    requestWeather.open("GET", scriptLink + "weather", true); // specify properties of requestWeather
    requestCurrRepo.open("GET", scriptLink + "currentRepo", true);
    requestFeatRepos.open("GET", scriptLink + "featRepos", true);

    requestWeather.send();
    requestCurrRepo.send();
    requestFeatRepos.send();
};
