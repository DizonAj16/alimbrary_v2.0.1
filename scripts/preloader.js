// Function to hide the preloader and show the content when everything is loaded
window.addEventListener("load", function() {
  var preloader = document.querySelector(".preloader");

  setTimeout(function() {
    preloader.style.display = "none";
    document.querySelector("body").style.overflow = "auto";
  }, 3000); 
});

