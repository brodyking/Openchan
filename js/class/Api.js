export class Api {
  static async newThread(event) {

    const formData = new FormData();

    // Image input
    const imgInput = document.getElementById("newThreadImage");
    if (imgInput != '') {
      const img = imgInput.files[0];
      formData.append("img", img);
    }

    formData.append("author", document.getElementById("newThreadAuthor").value);
    formData.append("board", document.getElementById("newThreadBoard").value);
    formData.append("body", document.getElementById("newThreadContent").value);
    formData.append("title", document.getElementById("newThreadTitle").value);

    let output = []

    try {
      const response = await fetch("/api/newThread.php", {
        method: "POST",
        // Set the FormData instance as the request body
        body: formData
      }).then(response => response.json()) // Parse the JSON response from the server
        .then(data => {
          output = data;
        })
    } catch (e) {
      console.error(e);
    }
    return output;
  }
  static async getThreadsAll() {
    let output = []
    try {
      const response = await fetch("/api/getThreadsAll.php", {
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
  static async getBoardList(board) {
    const formData = new FormData();
    formData.append("board", board);
    let output = []
    try {
      const response = await fetch("/api/getBoardList.php", {
        method: "POST",
        body: formData
      }).then(response => response.json()) // Parse the JSON response from the server
        .then(data => {
          output = data;
        })
    } catch (e) {
      console.error(e);
    }
    return output
  }
  static async getThread(idInput) {
    let messageBody = {
      id: idInput
    }
    let output = []
    try {
      const response = await fetch("/api/getThread.php", {
        method: "POST",
        // Set the FormData instance as the request body
        body: JSON.stringify(messageBody)
      }).then(response => response.json()) // Parse the JSON response from the server
        .then(data => {
          output = data;
        })
    } catch (e) {
      console.error(e);
    }
    return output;
  }
  static async newReply() {

    const formData = new FormData();

    // Image input
    const imgInput = document.getElementById("newThreadImage");
    if (imgInput != '') {
      const img = imgInput.files[0];
      formData.append("img", img);
    }

    formData.append("author", document.getElementById("newReplyAuthor").value);
    formData.append("body", document.getElementById("newReplyContent").value);
    formData.append("threadId", document.getElementById("newReplyId").value);

    let output = []

    try {
      const response = await fetch("/api/newReply.php", {
        method: "POST",
        // Set the FormData instance as the request body
        body: formData,
      }).then(response => response.json()) // Parse the JSON response from the server
        .then(data => {
          output = data;
        })
    } catch (e) {
      console.error(e);
    }
    return output;
  }
  static async getPosts() {
    let output = []
    try {
      const response = await fetch("/api/getPosts.php", {
        method: "GET",
        // Set the FormData instance as the request body
      }).then(response => response.json()) // Parse the JSON response from the server
        .then(data => {
          output = data;
        })
    } catch (e) {
      console.error(e);
    }
    return output;
  }

}
