import { Util } from './Util.js'

export class Pages {
  static async getStatic(url) {
    try {
      // 1. Await the fetch call to get the Response object
      const response = await fetch(url);

      // Check for HTTP errors (e.g., 404, 500)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      // 2. Await response.text() to get the HTML string
      const html = await response.text();

      // 3. Explicitly RETURN the final HTML string from the async function
      return html;

    } catch (error) {
      console.error('Failed to fetch page: ', error);
      // Return a 404 error HTML or null/empty string on failure
      return '<h1>Error loading template. Check console.</h1>';
    }
  }
  static async new() {
    Util.title = "New Thread";
    return await this.getStatic("/static/new.html");
  }
  static async meta() {
    Util.title = "Meta"
    return await this.getStatic("/static/meta.html");
  }
  static async index() {
    Util.title = ""
    return await this.getStatic("/static/index.html");
  }
  static async threads() {
    Util.title = "All Threads"
    return await this.getStatic("/static/threads.html");
  }
  static async thread() {
    Util.title = "Thread"
    return await this.getStatic("/static/thread.html")
  }
  static async reply() {
    Util.title = "Reply"
    return await this.getStatic("/static/reply.html")
  }
  static async posts() {
    Util.title = "Posts"
    return await this.getStatic("/static/posts.html")
  }
  static async error404() {
    Util.title = "Error 404"
    return await this.getStatic("/static/error404.html")
  }
}
