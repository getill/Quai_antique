/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

// start the Stimulus application
import "./bootstrap";

console.log("🔪 GET ILL.");

//------------- Anime JS ------------------

import anime from "animejs/lib/anime.es.js";
import $ from "jquery/dist/jquery";

//-------------------- Element selectors --------------------

const introT = document.querySelector(".introT");
const menuTitle = document.querySelector(".menuTitle");
const menu1 = document.querySelector(".menu1");
const menu2 = document.querySelector(".menu2");

//------------- Animations ----------------

anime({
  targets: ".introT",
  opacity: [0, 1],
  duration: 10000,
  delay: 1500,
});

anime({
  targets: ".logo",
  opacity: [0, 1],
  translateY: [-50, 0],
  easing: "easeInOutExpo",
  delay: 100,
});

anime({
  targets: ".nav1",
  opacity: [0, 1],
  translateY: [-50, 0],
  easing: "easeInOutExpo",
  delay: 500,
});

anime({
  targets: ".nav2",
  opacity: [0, 1],
  translateY: [-50, 0],
  easing: "easeInOutExpo",
  delay: 600,
});

anime({
  targets: ".nav3",
  opacity: [0, 1],
  translateY: [-50, 0],
  easing: "easeInOutExpo",
  delay: 700,
});

anime({
  targets: ".login",
  opacity: [0, 1],
  translateY: [-50, 0],
  easing: "easeInOutExpo",
  delay: 1000,
});

const selection = anime({
  targets: ".selection",
  opacity: [0, 1],
  autoplay: false,
});

const menuTitleAnime = anime({
  targets: ".menuTitle",
  opacity: [0, 1],
  translateY: [-50, 0],
  easing: "easeInOutExpo",
  autoplay: false,
});

const menu1Anime = anime({
  targets: ".menu1",
  opacity: [0, 1],
  easing: "easeInOutExpo",
});

const menu2Anime = anime({
  targets: ".menu2",
  opacity: [0, 1],
  easing: "easeInOutExpo",
});

//--------------------- Scroll logic ---------------------------------

const animateOnScroll = function (div, speed = 100, offset = 0) {
  const scrollPercent = window.scrollY - div.offsetTop;
  return (scrollPercent + offset) / speed;
};

//---------------------------- Animation trigger ---------------------------

window.onscroll = function () {
  selection.seek(animateOnScroll(introT, 2000, 300) * selection.duration);
  menuTitleAnime.seek(
    animateOnScroll(menuTitle, 1000, 1300) * menuTitleAnime.duration
  );
  menu1Anime.seek(animateOnScroll(menu1, 1000, 1200) * menu1Anime.duration);
  menu2Anime.seek(animateOnScroll(menu2, 1000, 1200) * menu2Anime.duration);
};

//---------------- Tooltip ---------------------------------------

const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

//-------------------------------------- Easepick ----------------------------------
const DaysOff = [];
const picker = new easepick.create({
  element: ".datepicker",
  css: ["https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css"],
  zIndex: 10,
  lang: "fr-FR",
  format: "DD/MM/YYYY",
  AmpPlugin: {
    dropdown: {
      months: true,
      years: true,
      minYear: 2023,
      maxYear: 2026,
    },
  },
  LockPlugin: {
    minDate: new Date(),
    minDays: null,
    maxDays: null,
    filter(date, picked) {
      return DaysOff.includes(date.format("YYYY-MM-DD"));
    },
  },
  plugins: ["AmpPlugin", "LockPlugin"],
});

let time;
// Functions to execute when date is selected
picker.on("select", () => {
  let pickerDate = picker.getDate();
  let date = pickerDate.toLocaleDateString("en");
  time = "00:00";

  // QueryString creation
  const params = new URLSearchParams();

  params.set("dateTime", date + time);
  //Get active URL
  const Url = new URL(window.location.href);

  // AJAX request
  fetch(Url.pathname + "?" + params.toString() + "&ajax=1", {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // Get content
      const content = document.querySelector("#content");

      // Content replacement
      content.innerHTML = data.content;

      // URL Update
      history.pushState({}, null, Url.pathname + "?" + params.toString());
    })
    .catch((e) => alert(e));
});

// Functions to execute when time is selected
$("#content").on("click", ".time-btn", (e) => {
  let pickerDate = picker.getDate();
  let date = pickerDate.toLocaleDateString("en");
  time = e.target.textContent;

  // QueryString creation
  const params = new URLSearchParams();

  params.set("dateTime", date + time);
  //Get active URL
  const Url = new URL(window.location.href);

  // AJAX request
  fetch(Url.pathname + "?" + params.toString() + "&ajax=1", {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // Get content
      const content = document.querySelector("#content");

      // Content replacement
      content.innerHTML = data.content;

      // URL Update
      history.pushState({}, null, Url.pathname + "?" + params.toString());
    })
    .catch((e) => alert(e));
});

function attachButtonClickHandlers() {
  // Récupérer tous les boutons avec l'ID "timeBtn"
  const buttons = document.querySelectorAll(".time-btn");

  // Vérifier si les boutons ont déjà été cliqués précédemment
  buttons.forEach((button) => {
    const isButtonClicked = localStorage.getItem(button.id);

    if (isButtonClicked) {
      button.classList.add("active");
    }

    // Ajouter un gestionnaire d'événement pour chaque bouton
    button.addEventListener("click", () => {
      // Ajouter ou supprimer la classe "active" au bouton cliqué
      button.classList.toggle("active");

      // Mettre à jour le statut du bouton cliqué dans le stockage local
      const isActive = button.classList.contains("active");
      localStorage.setItem(button.id, isActive ? "clicked" : "");
    });
  });
}

// Appeler la fonction pour attacher les gestionnaires d'événements aux boutons existants

document.body.onClick(function (event) {
  return event.target.classList.contains("active")
    ? true
    : attachButtonClickHandlers();
});
