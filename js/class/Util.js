export class Util {
  static set title(newtitle) {
    document.title = newtitle
  }
  static get url() {
    return location.pathname
  }
  static set main(content) {
    document.getElementById("main").innerHTML = content;
  }
  static get main() {
    return document.getElementById("main").innerHTML;
  }
}
