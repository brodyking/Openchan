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
    return await this.getStatic("/static/new.html");
  }
  static async threads() {
    return await this.getStatic("/static/threads.html");
  }
  static async thread() {
    return await this.getStatic("/static/thread.html")
  }
}
