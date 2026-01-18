import { Config } from '/js/config.js'

export class Util {
  static set title(newtitle) {
    if (newtitle == "") {
      document.title = Config.appName;
    } else {
      document.title = newtitle + " - " + Config.appName;
    }
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
  static set nav(content) {
    document.getElementById("nav").innerHTML = content;
  }
  static set footer(content) {
    document.getElementById("footer").innerHTML = content;
  }
}
