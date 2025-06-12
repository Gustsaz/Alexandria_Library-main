const toggleBtnL = document.querySelector(".toggle-btnL");
const Logo = document.querySelector(".logo-sidebar");
const Info = document.getElementById("info-icon");
const Down = document.getElementById("download-icon");
const Visu = document.getElementById("visua-icon");
const Salvo = document.getElementById("saved-icon");
const Conta = document.getElementById("conta-icon");
const Fechar = document.getElementById("fechar-icon");

const toggleThemeBtn = document.querySelector(".mode-toggle");
const themeIcon = document.getElementById("theme-icon");

const categories = document.querySelectorAll(".category");

const userBtn = document.getElementById("userBtn");
const logoutBtn = document.getElementById("logoutBtn");
const userForm = document.getElementById("userForm");
const logoutBubble = document.getElementById("logoutBubble");

const alternarFormularioBtn = document.getElementById("alternarFormularioBtn");
const formTitle = document.getElementById("form-title");
const acaoInput = document.getElementById("acao");
const nomeField = document.getElementById("nome-field");
const submitButton = document.getElementById("submit-button");
const nomeInput = document.querySelector("#nome-field input[name='nome']");


function applyTheme(theme) {
    if (theme === "dark") {
        document.body.classList.add("dark-mode");
        if (themeIcon) themeIcon.src = "img/Claro.png";
        if (Logo) Logo.src = "img/LogoClaro.png";
        if (Info) Info.src = "img/InfoClaro.png";
        if (Down) Down.src = "img/DownloadClaro.png";
        if (Visu) Visu.src = "img/EyeClaro.png";
        if (Salvo) Salvo.src = "img/SavedClaro.png";
        if (Fechar) Fechar.src = "img/FecharClaro.png";
    } else {
        document.body.classList.remove("dark-mode");
        if (themeIcon) themeIcon.src = "img/Escuro.png";
        if (Logo) Logo.src = "img/LogoEscuro.png";
        if (Info) Info.src = "img/InfoEscuro.png";
        if (Down) Down.src = "img/DownloadEscuro.png";
        if (Visu) Visu.src = "img/EyeEscuro.png";
        if (Salvo) Salvo.src = "img/SavedEscuro.png";
        if (Conta) Conta.src = "img/ContaEscuro.png";
        if (Fechar) Fechar.src = "img/FecharEscuro.png";
    }
}


/*abrir sidebars*/
const sidebarR = document.querySelector(".right-sidebar");



function openRightSidebar(pdfUrl) {
    const sidebar = document.getElementById("rightSidebar");
    sidebar.classList.add("expanded");

    sidebar.innerHTML = `
        <div style="display: flex; justify-content: flex-end; width: 100%;">
            <button onclick="closeRightSidebar()" style="
                border: none;
                background: none;
                cursor: pointer;
                padding: 10px;
            "><img draggable="false" src="img/FecharEscuro.png" style=" width= 30px;height: 30px;" id="fechar-icon"></button>
        </div>
        ${pdfUrl ? `
        <div style="width: 100%; height: calc(100% - 50px);">
            <iframe src="${pdfUrl}" style="width: 100%; height: 100%;" frameborder="0"></iframe>
        </div>` : `
        <p style="padding: 1rem;">Este livro não possui PDF disponível.</p>
        `}
    `;
}

function closeRightSidebar() {
    const sidebar = document.getElementById("rightSidebar");
    sidebar.classList.remove("expanded");
    sidebar.innerHTML = ""; // limpa o iframe ou mensagem
}





function toggleForm() {
    if (userForm && !userForm.classList.contains("hidden")) {
        userForm.classList.add("hidden");
    }
    if (logoutBubble && !logoutBubble.classList.contains("hidden")) {
        logoutBubble.classList.add("hidden");
    }
}


if (toggleThemeBtn) {
    toggleThemeBtn.addEventListener("click", () => {
        const isDarkMode = document.body.classList.toggle("dark-mode");
        const newTheme = isDarkMode ? "dark" : "light";
        localStorage.setItem("theme", newTheme);
        applyTheme(newTheme);
    });
}


if (userBtn) {
    userBtn.addEventListener("click", () => {
        if (userForm) userForm.classList.toggle("hidden");
        if (logoutBubble && !logoutBubble.classList.contains("hidden")) {
            logoutBubble.classList.add("hidden");
        }
    });
}


if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
        if (logoutBubble) logoutBubble.classList.toggle("hidden");
        if (userForm && !userForm.classList.contains("hidden")) {
            userForm.classList.add("hidden");
        }
    });
}


if (alternarFormularioBtn) {
    alternarFormularioBtn.addEventListener("click", () => {
        if (acaoInput.value === "cadastrar") {
            formTitle.textContent = "Entrar";
            acaoInput.value = "login";
            if (nomeField) nomeField.style.display = "none";
            if (nomeInput) nomeInput.removeAttribute("required");
            if (submitButton) submitButton.textContent = "Entrar";
            alternarFormularioBtn.textContent = "Não tem uma conta? Cadastre-se";
        } else {
            formTitle.textContent = "Cadastro";
            acaoInput.value = "cadastrar";
            if (nomeField) nomeField.style.display = "block";
            if (nomeInput) nomeInput.setAttribute("required", "required");
            if (submitButton) submitButton.textContent = "Cadastrar";
            alternarFormularioBtn.textContent = "Já tem uma conta? Entrar";
        }
    });
}

window.onload = function () {
    const successMessage = document.querySelector(".message.success");
    const errorMessage = document.querySelector(".message.error");

    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = "0";
            setTimeout(() => successMessage.style.display = "none", 500);
        }, 5000);
    }

    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.opacity = "0";
            setTimeout(() => errorMessage.style.display = "none", 500);
        }, 5000);
    }


    const savedTheme = localStorage.getItem("theme");
    if (savedTheme) {
        applyTheme(savedTheme);
    } else {
        applyTheme("light");
    }

    toggleForm();

    if (nomeInput && acaoInput) {
        if (acaoInput.value === "cadastrar") {
            nomeInput.setAttribute("required", "required");
        } else {
            nomeInput.removeAttribute("required");
        }
    }
};


const bookList = document.querySelector('.book-list');

if (bookList) {
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
        const walk = (x - startX) * 1.5;
        bookList.scrollLeft = scrollLeft - walk;
    });
}

//scroll bunitin pras secao

const baixados = document.querySelector(".download-button");
const baixadosContainer = document.querySelector(".highlight-Baixados");

if (baixados && baixadosContainer) {
    baixados.addEventListener("click", () => {
        baixadosContainer.scrollIntoView({ behavior: "smooth" });
    });
}

const lista = document.querySelector(".saved-button");
const listaContainer = document.querySelector(".highlight-saved");

if (lista && listaContainer) {
    lista.addEventListener("click", () => {
        listaContainer.scrollIntoView({ behavior: "smooth" });
    });
}

const lidos = document.querySelector(".visua-button");
const lidosContainer = document.querySelector(".highlight-Lidos");

if (lidos && lidosContainer) {
    lidos.addEventListener("click", () => {
        lidosContainer.scrollIntoView({ behavior: "smooth" });
    });
}

const info = document.querySelector(".info-button");
const infoContainer = document.querySelector(".footer");

if (info && infoContainer) {
    info.addEventListener("click", () => {
        infoContainer.scrollIntoView({ behavior: "smooth" });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector("input[type='text']");
    const resultsContainer = document.querySelector(".search-results");

    if (!searchInput || !resultsContainer) {
        console.error("Elemento de busca não encontrado!");
        return;
    }

    let livros = [];

    //caregano os livros do JSON
    fetch("data/livros.json")
        .then(response => response.json())
        .then(data => {
            livros = data;
        })
        .catch(error => {
            console.error("Erro ao carregar livros.json:", error);
        });

    //update nos resultados
    function atualizarResultados(query) {
        resultsContainer.innerHTML = "";

        if (query.trim() === "") {
            resultsContainer.classList.add("hidden");
            return;
        }

        const filtrados = livros.filter(livro =>
            livro.nome.toLowerCase().includes(query.toLowerCase())
        );

        if (filtrados.length === 0) {
            const vazio = document.createElement("div");
            vazio.classList.add("result-item");
            vazio.textContent = "Nenhum livro encontrado";
            resultsContainer.appendChild(vazio);
        } else {
            filtrados.forEach(livro => {
                const item = document.createElement("div");
                item.classList.add("result-item");

                item.innerHTML = `
          <div style="display: flex; align-items: center; gap: 10px;">
            <img src="${livro.capa}" alt="${livro.nome}" style="width: 40px; height: 60px; object-fit: cover; border: 1px solid #ccc;">
            <div>
              <strong>${livro.nome}</strong><br>
              <small>${livro.autor}</small>
            </div>
          </div>
        `;

                item.addEventListener("click", () => {
                    searchInput.value = livro.nome;
                    resultsContainer.classList.add("hidden");
                });

                resultsContainer.appendChild(item);
            });
        }

        resultsContainer.classList.remove("hidden");
    }

    //digitando
    searchInput.addEventListener("input", () => {
        const query = searchInput.value;
        atualizarResultados(query);
    });

    //fechando dropwdown clicando forta 
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".search-container")) {
            resultsContainer.classList.add("hidden");
        }
    });
});

//categorias para mostrar só as que tem livros
document.querySelectorAll('.category').forEach(cat => {
    cat.addEventListener('click', () => {
        const categoriaSelecionada = cat.dataset.category;

        // remove atual e mostra outro
        document.querySelectorAll('.category').forEach(c => c.classList.remove('active'));
        cat.classList.add('active');

        // mostra/esconde as seções
        document.querySelectorAll('.highlight[data-category]').forEach(section => {
            const listaLivros = section.querySelector('.book-list');

            if (categoriaSelecionada === "Todos") {
                section.style.display = "block";
                if (listaLivros) listaLivros.classList.remove("catalogo-grid"); // remove grid
            } else {
                if (section.dataset.category === categoriaSelecionada) {
                    section.style.display = "block";
                    if (listaLivros) listaLivros.classList.add("catalogo-grid"); // ativa grid
                } else {
                    section.style.display = "none";
                    if (listaLivros) listaLivros.classList.remove("catalogo-grid"); // limpa grid
                }
            }
        });
    });
});

// Animação scroll

const revealElements = document.querySelectorAll('.scroll-reveal, .scroll-reveal-delay, .scroll-reveal-cascade');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            observer.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.1
});

revealElements.forEach(el => observer.observe(el));

function applyDraggableScroll(bookListElement) {
    let isDown = false;
    let startX;
    let scrollLeft;

    bookListElement.addEventListener('mousedown', (e) => {
        isDown = true;
        bookListElement.classList.add('active');
        startX = e.pageX - bookListElement.offsetLeft;
        scrollLeft = bookListElement.scrollLeft;
    });

    bookListElement.addEventListener('mouseleave', () => {
        isDown = false;
        bookListElement.classList.remove('active');
    });

    bookListElement.addEventListener('mouseup', () => {
        isDown = false;
        bookListElement.classList.remove('active');
    });

    bookListElement.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - bookListElement.offsetLeft;
        const walk = (x - startX) * 1.5;
        bookListElement.scrollLeft = scrollLeft - walk;
    });
}

// Seleciona todos os elementos com a classe .book-list
const allBookLists = document.querySelectorAll('.book-list');

// Aplica a função de scroll dragável a cada um deles
allBookLists.forEach(applyDraggableScroll);