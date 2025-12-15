import { Util } from '/js/class/Util.js'
import { Pages } from '/js/class/Pages.js'
import { Api } from "/js/class/Api.js"

let url = Util.url;
let page = ""
switch (url) {
  case "/":
    await Pages.threads().then(output => Util.main = output);
    await Api.getThreadList().then((output) => {
      output.forEach((element) => {
        document.getElementById("threadsBody").innerHTML += '<tr><td>' + element.title + '</td><td>' + element.author + '</td></tr>';
      })
    });
    break;
  case "/new":
    // Gets the page, sets the main dom to contian it
    await Pages.new().then(output => Util.main = output);
    document.getElementById("newThreadForm").addEventListener("submit", (event) => { event.preventDefault(); Api.newThread() });
    break;
  default:
    Util.main = "<h1>Error 404. Page not found.</h1>"
    break;
}
