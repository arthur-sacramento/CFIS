// Run only after all page is loaded
window.onload = function() {
    // JavaScript code to change the styles of all links
    var allLinks = document.getElementsByTagName("a");

    for (var i = 0; i < allLinks.length; i++) {
      allLinks[i].style.fontFamily = "Arial, sans-serif";
      allLinks[i].style.color = "#444";
      allLinks[i].style.textDecoration = "none";
    }
};