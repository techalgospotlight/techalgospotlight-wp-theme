/**
 * techalgospotlight Hero slider
 *
 * @since 1.0.0
 */
let techalgospotlightHeroSlider = function (el) {
  let spinner = el.querySelector(".techalgospotlight-spinner");

  // Hide spinner
  let hideSpinner = function () {
    spinner.classList.remove("visible");

    setTimeout(function () {
      spinner.style.display = "none";
    }, 300);

    el.classList.add("loaded");
  };

  // Wait for images to load
  imagesLoaded(el, function () {
    let preloader = document.getElementById("techalgospotlight-preloader");

    // Wait for preloader to finish before we show fade in animation
    if (
      preloader &&
      !document.body.classList.contains("techalgospotlight-loaded")
    ) {
      document.body.addEventListener(
        "techalgospotlight-preloader-done",
        function () {
          setTimeout(hideSpinner, 300);
        }
      );
    } else {
      setTimeout(hideSpinner, 300);
    }
  });

  return el;
};

// Main
(function () {
  // On ready event
  document.addEventListener("DOMContentLoaded", function () {
    // Initialize hero sliders
    document
      .querySelectorAll(".techalgospotlight-hero-slider")
      .forEach((item) => {
        techalgospotlightHeroSlider(item);
      });
  });
})();
