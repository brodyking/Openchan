import { Config } from '/js/config.js' // Configuration file
import { Util } from '/js/class/Util.js' // Utility class for changing dom elements and such
import { Pages } from '/js/class/Pages.js' // All page template getters
import { Api } from "/js/class/Api.js" // All API methods
import { Components } from "/js/class/Components.js" // Small components such as Nav and Footer

let url = Util.url;
const urlParams = new URLSearchParams(window.location.search);

// Cuts off trailing slashes
if (url !== "/" && url.charAt(url.length - 1) == "/") {
  url = url.substr(0, url.length - 1);
}

// Generate Navigation
await Components.nav().then(output => {
  Util.nav = output; document.getElementById("navSiteName").innerText = Config.appName
});

// Generate Footer
await Components.footer().then(output => {
  Util.footer = output; document.getElementById("footerVersion").innerText = "v" + Config.version
});

// Router
const main = async () => {
  switch (url) {
    case "/": // Home Threads Page
      await index();
      break;
    case "/threads":
      await threads();
      break;
    case "/meta":
      await meta();
      break;
    case "/thread": // Individual Thread Page
      await thread();
      break;
    case "/new": // New Thread Page
      await newThread();
      break;
    case "/reply": // Reply Page
      await reply();
      break;
    case "/posts": // All Posts Page
      await posts();
      break;
    default:
      await error404(); // Error 404 Page
      break;
  }
}

// Shows all threads across all boards
const threads = async () => {
  // Get threads page template
  await Pages.threads().then(output => Util.main = output);
  // Populate thread list
  await Api.getThreadsAll().then((output) => {
    output.forEach((element) => {
      if (element.role == "admin") {
        element.author = "<span class='admin'>## Admin ##</span> " + element.author;
      }
      document.getElementById("threadsBody").innerHTML += '<tr><td><a href="/thread?id=' + element.id + '">' + element.title + '</a></td><td>' + element.author + '</td><td>' + JSON.parse(element.content).length + '</td><td>' + element.date + '</td></tr>';
    })
  });
}

// Site homepage that shows list of boards
const index = async () => {
  // Get threads page template
  await Pages.index().then(output => Util.main = output);
  Config.boards.forEach((board) => {
    document.getElementById("boardsBody").innerHTML += '<tr><td><a href="/' + board[0] + '/">/' + board[0] + '/</a></td><td>' + board[1] + '</td></tr>';
  })
}
// Shows the meta board
const meta = async () => {
  // Get threads page template
  await Pages.meta().then(output => Util.main = output);
  // Populate thread list
  await Api.getThreadsFromBoard("meta").then((output) => {
    output.forEach((element) => {
      console.log(JSON.parse(element.content));
      if (element.role == "admin") {
        element.author = "<span class='admin'>## Admin ##</span> " + element.author;
      }
      document.getElementById("threadsBody").innerHTML += '<tr><td><a href="/thread?id=' + element.id + '">' + element.title + '</a></td><td>' + element.author + '</td><td>' + JSON.parse(element.content).length + '</td><td>' + element.date + '</tr>';
    })
  });
}

// Error 404 Page
const error404 = async () => {
  // Get threads page template
  await Pages.error404().then(output => Util.main = output);
}

// Thread Viewing page. 
const thread = async () => {
  if (urlParams.get('id') == undefined) {
    location.href = "/";
    return 0;
  }
  // Get thread page template
  await Pages.thread().then(output => Util.main = output);
  // Get thread data from api.
  await Api.getThread(urlParams.get('id')).then((data) => {
    // If there is an error, such as a thread not existing
    if (data.error !== undefined) {
      location.href = "/";
    } else {
      // Set thread metadata
      document.getElementById("threadAuthor").innerText = data["author"]
      document.getElementById("threadTitle").innerText = data["title"]
      // If thread is creatd by an admin
      if (data.role == "admin") {
        document.getElementById("threadAuthor").innerHTML = "<span class='admin'>## Admin ##</span> " + data["author"]
      }
      // Set page title to the thread name
      Util.title = data["title"];
      // Add reply button
      navInner.innerHTML += '<li style="float:right;"><a href="#" id="threadReplyBtn" class="btn"><i class="bi bi-reply-all-fill"></i> Reply</a></li>'
      // Attach link to the reply button
      document.getElementById("threadReplyBtn").href = "/reply?id=" + data["id"];
      // Replies
      let replies = data["replies"]
      replies.forEach((reply) => {
        // Create each thread
        console.log(reply.role);
        if (reply.role == "admin") {
          reply.author = "<span class='admin'>## Admin ##</span> " + reply.author;
        }
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
}

// New thread page
const newThread = async () => {
  // Gets the page
  await Pages.new().then(output => Util.main = output);

  Config.boards.forEach((board) => document.getElementById("newThreadBoard").innerHTML += "<option value='" + board[0] + "'>/" + board[0] + "/</option>")

  // Submit Listener to create a new thread
  document.getElementById("newThreadForm").addEventListener("submit", (event) => {
    event.preventDefault();
    Api.newThread(event).then((response) => {
      if (response.success !== undefined) {
        Util.main = "<p style='color: lightgreen;text-align:center;'>Thread created! <a href='/thread?id=" + response["threadid"] + "'>Click here to visit it</a></p>"
      } else {
        Util.main = "<p style='color: lightred;text-align:center;'>" + response.errormessage + "</p>"
      }
    })
  });
}

// Reply page
const reply = async () => {
  if (urlParams.get('id') == undefined) {
    location.href = "/";
    return 0;
  }
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
        Util.main = "<p style='color: lightred;text-align:center;'>" + response.errormessage + "</p>"
      }
    })
  });
}


// All posts page
const posts = async () => {
  // Get thread page template
  await Pages.posts().then(output => Util.main = output);
  // Get all post data from api.
  await Api.getPosts(urlParams.get('id')).then((data) => {
    // Replies
    let replies = data
    replies.forEach((reply) => {

      if (reply.role == "admin") {
        reply.author = "<span class='admin'>## Admin ##</span> " + reply.author;
      }

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
}

main();
