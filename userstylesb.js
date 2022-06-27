function changeCSS(cssFile, cssLinkIndex) {

  var oldlink = document.getElementsByTagName("link").item(cssLinkIndex);

  var newlink = document.createElement("link");
  newlink.setAttribute("rel", "stylesheet");
  newlink.setAttribute("type", "text/css");
  newlink.setAttribute("href", cssFile);

  document.getElementsByTagName("head").item(cssLinkIndex).replaceChild(newlink, oldlink);
  
}

if (localStorage.getItem("currentCSS") == null) {
localStorage.setItem("currentCSS","Dark")
}

var zone = document.getElementById("userstyleselecter");

zone.value = localStorage.getItem("currentCSS");

function userstyle() {

  if (zone.value == "Dark") {
    changeCSS('../styles/dark.css');
    localStorage.setItem("currentCSS","Dark");
  } else if (zone.value == "Light") {
    changeCSS('../styles/light.css')
    localStorage.setItem("currentCSS","Light");
  } else if (zone.value == "Yotsuba") {
    changeCSS('../styles/yotsuba.css')
    localStorage.setItem("currentCSS","Yotsuba");
  } else if (zone.value == "Yotsuba B") {
    changeCSS('../styles/yotsuba-b.css')
    localStorage.setItem("currentCSS","Yotsuba B");
  }
}

userstyle();