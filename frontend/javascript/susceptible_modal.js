// Modal 5 - Add Role
const modalRole5 = document.getElementById("modalRole5");
const btnRole5 = document.getElementById("addRoleBtn5");
const iframeRole5 = document.getElementById("iframeRole5");

btnRole5.onclick = function () {
  iframeRole5.src = "../views/modal_add.php";  // Add Role form
  modalRole5.style.display = "flex";
}

// Modal 5 - Add Description
const modalDescription5 = document.getElementById("modalDescription5");
const btnDescription5 = document.getElementById("addDescriptionBtn5");
const iframeDescription5 = document.getElementById("iframeDescription5");

btnDescription5.onclick = function () {
  iframeDescription5.src = "../views/modal_desc.php";  // Add Description form
  modalDescription5.style.display = "flex";
}

// Modal 5 - Remove Role
const modalRemove5 = document.getElementById("modalRemove5");
const btnRemove5 = document.getElementById("removeRoleBtn5");
const iframeRemove5 = document.getElementById("iframeRemove5");

btnRemove5.onclick = function () {
  iframeRemove5.src = "../views/modal_remove.php";  // Remove Role form
  modalRemove5.style.display = "flex";
}

// Handle close buttons for three modals
const susceptibleCloseBtns = document.querySelectorAll("#susceptible .close-btn");
susceptibleCloseBtns.forEach(btn => {
  btn.onclick = function () {
    const targetId = btn.getAttribute("data-target");
    const modalToClose = document.getElementById(targetId);
    modalToClose.style.display = "none";
    // Clear iframe source
    modalToClose.querySelector("iframe").src = "";
  }
});
