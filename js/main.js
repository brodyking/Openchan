import { Util } from '/js/class/Util.js'
import { Pages } from '/js/class/Pages.js'
import { Api } from "/js/class/Api.js"
import { Components } from "/js/class/Components.js"

let url = Util.url;
const urlParams = new URLSearchParams(window.location.search);

// Generate Navigation
await Components.nav().then(output => Util.nav = output);
// Generate Footer
await Components.footer().then(output => Util.footer = output);

switch (url) {

  // Home Threads Page
  case "/":
    // Get threads page template
    await Pages.index().then(output => Util.main = output);
    // Populate thread list
    await Api.getThreadList().then((output) => {
      output.forEach((element) => {
        document.getElementById("threadsBody").innerHTML += '<tr><td><a href="/thread?id=' + element.id + '">' + element.title + '</a></td><td>' + element.author + '</td></tr>';
      })
    });
    break;

  // Individual Thread Page
  case "/thread":
    if (urlParams.get('id') == undefined) {
      location.href = "/";
      break;
    }

    // Get thread page template
    await Pages.thread().then(output => Util.main = output);

    // Get thread data from api.
    await Api.getThread(urlParams.get('id')).then((data) => {

      if (data.error !== undefined) {

        location.href = "/";

      } else {

        // Set thread metadata
        document.getElementById("threadAuthor").innerText = data["author"]
        document.getElementById("threadTitle").innerText = data["title"]

        // Add reply button
        navInner.innerHTML += '<li style="float:right;"><a href="#" id="threadReplyBtn" class="btn">Reply</a></li>'

        // Attach link to the reply button
        document.getElementById("threadReplyBtn").href = "/reply?id=" + data["id"];


        // Replies
        let replies = data["replies"]
        replies.forEach((reply) => {
          // Create each thread
          document.getElementById("threadReplies").innerHTML += "<table><thead><td><b>" + reply.author + "</b></td><td>" + reply.date + "</td><td>#" + reply.id + "</td></thead><tbody><tr><td id='reply-img-" + reply.id + "'></td></tr><tr><td class='replybody' id='reply-" + reply.id + "'></td></tr></tbody></table>"
          document.getElementById("reply-" + reply.id).innerText = reply.body;
          if (reply.img != null) {
            document.getElementById("reply-img-" + reply.id).innerHTML += "<img class='reply-img' src='/api/" + reply.img + "'/>'";
          } else {
            document.getElementById("reply-img-" + reply.id).remove()
          }
        })
      }


    });
    break;

  // New Thread Page
  case "/new":
    // Gets the page
    await Pages.new().then(output => Util.main = output);
    // Submit Listener to create a new thread
    document.getElementById("newThreadForm").addEventListener("submit", (event) => {

      event.preventDefault();
      Api.newThread(event).then((response) => {

        if (response.success !== undefined) {
          Util.main = "<p style='color: lightgreen;text-align:center;'>Thread created! <a href='/thread?id=" + response["threadid"] + "'>Click here to visit it</a></p>"
        } else {
          Util.main = "<p style='color: lightred;text-align:center;'>An error occoured.</p>"
        }

        console.log(response);
      })


    });
    break;

  // Reply Page
  case "/reply":
    // Gets the page
    await Pages.reply().then(output => Util.main = output);

    document.getElementById("newReplyId").value = urlParams.get("id");

    // Submit Listener to create a new thread
    document.getElementById("newReplyForm").addEventListener("submit", (event) => {

      event.preventDefault();

      Api.newReply().then((response) => {

        if (response.success !== undefined) {
          Util.main = "<p style='color: lightgreen;text-align:center;'>Reply created! <a href='/thread?id=" + response["threadid"] + "'>Click here to visit it</a></p>"
        } else {
          Util.main = "<p style='color: lightred;text-align:center;'>An error occoured.</p>"
        }

        console.log(response);
      })
    });
    break;


  // All Posts Page
  case "/posts":
    // Get thread page template
    await Pages.posts().then(output => Util.main = output);
    // Get all post data from api.
    await Api.getPosts(urlParams.get('id')).then((data) => {

      // Replies
      let replies = data
      replies.forEach((reply) => {
        // Create each thread
        document.getElementById("allReplies").innerHTML += "<table><thead><td><b>" + reply.author + "</b></td><td>" + reply.date + "</td><td>#" + reply.id + "</td></thead><tbody><tr><td id='reply-img-" + reply.id + "'></td></tr><tr><td class='replybody' id='reply-" + reply.id + "'></td></tr></tbody></table>"
        document.getElementById("reply-" + reply.id).innerText = reply.body;
        if (reply.img != null) {
          document.getElementById("reply-img-" + reply.id).innerHTML += "<img class='reply-img' src='/api/" + reply.img + "'/>'";
        } else {
          document.getElementById("reply-img-" + reply.id).remove()
        }
      })

    });

    break;

  default:
    Util.main = "<h1>Error 404. Page not found.</h1>"
    break;
}
