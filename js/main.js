import { Util } from '/js/class/Util.js'
import { Pages } from '/js/class/Pages.js'
import { Api } from "/js/class/Api.js"

let url = Util.url;
let page = ""
const searchParams = new URLSearchParams(window.location.search);
const paramsArray = [...searchParams];
console.log(paramsArray);

switch (url) {

  // Home Threads Page
  case "/":
    // Get threads page template
    await Pages.threads().then(output => Util.main = output);
    // Populate thread list
    await Api.getThreadList().then((output) => {
      output.forEach((element) => {
        console.log(element)
        document.getElementById("threadsBody").innerHTML += '<tr><td><a href="/thread?id=' + element.id + '">' + element.title + '</a></td><td>' + element.author + '</td></tr>';
      })
    });
    break;

  // Individual Thread Page
  case "/thread":
    await Pages.thread().then(output => Util.main = output);
    await Api.getThread()
    break;

  // New Thread Page
  case "/new":
    // Gets the page
    await Pages.new().then(output => Util.main = output);
    // Submit Listener to create a new thread
    document.getElementById("newThreadForm").addEventListener("submit", (event) => { event.preventDefault(); Api.newThread() });
    break;
  default:
    Util.main = "<h1>Error 404. Page not found.</h1>"
    break;
}
