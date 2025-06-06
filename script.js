const categories = document.querySelectorAll(".category");

categories.forEach((cat) => {
  cat.addEventListener("click", () => {
    categories.forEach((c) => c.classList.remove("active"));
    cat.classList.add("active");
  });
});

/*abrir sidebars*/

const sidebarR = document.querySelector(".sidebar-right");

sidebarR.addEventListener("click", () => {
  sidebarR.classList.toggle("expanded");
});

function openRightSidebar() {
  const sidebar = document.getElementById("sidebarRight");
  sidebar.classList.add("expanded");
}

//modo escuor/claro -----------------------------------

const toggleThemeBtn = document.querySelector(".mode-toggle");
const themeIcon = document.getElementById("theme-icon");
const Logo = document.querySelector(".logo-sidebar");
const Info = document.getElementById("info-icon");
const Down = document.getElementById("download-icon");

function applyTheme(theme) {
  if (theme === "dark") {
    document.body.classList.add("dark-mode");
    themeIcon.src = "img/Claro.png";
    Logo.src = "img/LogoClaro.png";
    Info.src = "img/InfoClaro.png";
    Down.src = "img/DownloadClaro.png";
  } else {
    document.body.classList.remove("dark-mode");
    themeIcon.src = "img/Escuro.png";
    Logo.src = "img/LogoEscuro.png";
    Info.src = "img/InfoEscuro.png";
    Down.src = "img/DownloadEscuro.png";
  }
}

const savedTheme = localStorage.getItem("theme");
applyTheme(savedTheme || "light"); // padrão é 'light'

toggleThemeBtn.addEventListener("click", () => {
  const isDarkMode = document.body.classList.toggle("dark-mode");
  const newTheme = isDarkMode ? "dark" : "light";
  localStorage.setItem("theme", newTheme);
  applyTheme(newTheme);
});

/*CADASTRO*/
const userBtn = document.getElementById('userBtn');
const userForm = document.getElementById('userForm');

userBtn.addEventListener('click', () => {
  userForm.classList.toggle('hidden');
});

// Ocultar ao clicar fora
document.addEventListener('click', function(e) {
  if (!userForm.contains(e.target) && !userBtn.contains(e.target)) {
    userForm.classList.add('hidden');
  }
});

// book list
const bookList = document.querySelector('.book-list');

let isDown = false;
let startX;
let scrollLeft;

bookList.addEventListener('mousedown', (e) => {
  isDown = true;
  bookList.classList.add('active');
  startX = e.pageX - bookList.offsetLeft;
  scrollLeft = bookList.scrollLeft;
});

bookList.addEventListener('mouseleave', () => {
  isDown = false;
  bookList.classList.remove('active');
});

bookList.addEventListener('mouseup', () => {
  isDown = false;
  bookList.classList.remove('active');
});

bookList.addEventListener('mousemove', (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - bookList.offsetLeft;
  const walk = (x - startX) * 1.5; // Aumente esse valor para mover mais rápido
  bookList.scrollLeft = scrollLeft - walk;
});