// All of the Node.js APIs are available in the preload process.
// It has the same sandbox as a Chrome extension.
window.addEventListener('DOMContentLoaded', () => {

    var config = require("config.json")('./settings.json');
  
    const replaceText = (selector, text) => {
        const element = document.getElementById(selector)
        if (element) element.innerText = text
    } 
  
    const setValue = (selector, value) => {
        const element = document.getElementById(selector)
        if (element) element.value = value;
    }
  
    const replaceHTML = (selector, html) => {
        const element = document.getElementById(selector);
        if (element) element.innerHTML = html;
    }
  
    const appendHTML = (selector, html) => {
        const element = document.getElementById(selector);
        if (element) element.innerHTML += html;
    }
  
    localStorage.setItem("protocol","http://");
    localStorage.setItem("domain",config.domain);
    localStorage.setItem("host",config.host);
    localStorage.setItem("port",config.port);
    localStorage.setItem("database",config.database);
  
    localStorage.setItem("config", JSON.stringify(config));
  
    localStorage.setItem("cwd", process.cwd());
  });