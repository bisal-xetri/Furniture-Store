var swiper = new Swiper(".mySwiper", {
  spaceBetween: 30,
  centeredSlides: true,
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});
var hasVisitedFirstIndex = localStorage.getItem("hasVisitedFirstIndex");

// Check if it's the first index page
if (document.location.pathname === "/" && !hasVisitedFirstIndex) {
  var loader = document.querySelector(".ldr");
  setTimeout(function () {
    loader.style.top = "-100%";
    // Set the flag to indicate that the user has visited the first index page
    localStorage.setItem("hasVisitedFirstIndex", true);
  }, 6000);
} else {
  // If the user has visited before or is on a different page, hide the loader immediately
  var loader = document.querySelector(".ldr");
  loader.style.display = "none";
}