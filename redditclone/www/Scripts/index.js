function showMenu() {
  document.getElementById("menu").classList.remove("hidden");
  document.getElementById("menu").classList.add("visible");
}
function hideMenu() {
  document.getElementById("menu").classList.remove("visible");
  document.getElementById("menu").classList.add("hidden");
}

document
  .getElementById("hamburgerMenuOpen")
  .addEventListener("click", showMenu);
document
  .getElementById("hamburgerMenuClose")
  .addEventListener("click", hideMenu);

let textareaArray = document.querySelectorAll("textarea");
for (const textarea of textareaArray) {
  textarea.oninput = () => {
    textarea.style.height = "";
    textarea.style.height = textarea.scrollHeight + "px";
  };
}

const buttons = document.querySelectorAll("input[type='submit']");
for (const button of buttons) {
  button.classList.add("button-submit");
}

const reactionsButtons = document.querySelectorAll(".reaction");
