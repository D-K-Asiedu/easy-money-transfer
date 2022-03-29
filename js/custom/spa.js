function hasHash() {
    return location.hash.length > 0 ? true : false;
}

function loadSPAContent(url) {
    if (!hasHash()) {
        alert("Default page not set");
        return;
    }
    let app = $("#app");
    app.load(url, (response, status, xhr) => {
        if (status == "error") {
            console.log(xhr.status + " " + xhr.statusText);
        } else {
            $.event.trigger({ type: 'fragmentLoaded' });
        }
    });
}

function getUrl(base) {
    let fragmentId = location.hash.substr(1);
    let url = base + fragmentId + ".php";
    return url;
}

sessionStorage.setItem('spaRoutes', JSON.stringify([]));

function logRoute(routeName) {
    let routes = sessionStorage.getItem('spaRoutes');
    let routesArr = JSON.parse(routes);
    if (!routesArr.includes(routeName)) {
        routesArr.push(routeName);
    }
    sessionStorage.setItem('spaRoutes', JSON.stringify(routesArr));
}

function checkAndChangeRoute(base) {
    let allRoutes = JSON.parse(sessionStorage.getItem('spaRoutes'));
    let route = location.hash.substr(1);
    if (allRoutes.includes(route))
        loadSPAContent(getUrl(base));
}