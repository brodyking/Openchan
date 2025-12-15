export class Api {
  static async newThread() {
    let messageBody = {
      author: document.getElementById("newThreadAuthor").value,
      body: document.getElementById("newThreadContent").value,
      title: document.getElementById("newThreadTitle").value
    }

    console.log(JSON.stringify(messageBody))
    try {
      const response = await fetch("/api/newThread.php", {
        method: "POST",
        // Set the FormData instance as the request body
        body: JSON.stringify(messageBody),
      }).then(response => response.json()) // Parse the JSON response from the server
        .then(data => {
          console.log('Success:', data); // Handle the success
        })
    } catch (e) {
      console.error(e);
    }
  }
  static async getThreadList() {
    let output = []
    try {
      const response = await fetch("/api/getThreadList.php", {
        method: "GET",
      }).then(response => response.json()) // Parse the JSON response from the server
        .then(data => {
          output = data;
        })
    } catch (e) {
      console.error(e);
    }
    return output
  }
}
