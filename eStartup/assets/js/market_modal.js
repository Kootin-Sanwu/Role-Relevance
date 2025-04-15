// Modal 4 - Add Role
const modalRole4 = document.getElementById("modalRole4");
const btnRole4 = document.getElementById("addRoleBtn4");
const iframeRole4 = document.getElementById("iframeRole4");

btnRole4.onclick = function () {
  iframeRole4.src = "../views/modal_add.php";  // Add Role form
  modalRole4.style.display = "flex";
}

// Modal 4 - Add Description
const modalDescription4 = document.getElementById("modalDescription4");
const btnDescription4 = document.getElementById("addDescriptionBtn4");
const iframeDescription4 = document.getElementById("iframeDescription4");

btnDescription4.onclick = function () {
  iframeDescription4.src = "../views/modal_desc.php";  // Add Description form
  modalDescription4.style.display = "flex";
}

// Modal 4 - Remove Role
const modalRemove4 = document.getElementById("modalRemove4");
const btnRemove4 = document.getElementById("removeRoleBtn4");
const iframeRemove4 = document.getElementById("iframeRemove4");

btnRemove4.onclick = function () {
  iframeRemove4.src = "../views/modal_remove.php";  // Remove Role form
  modalRemove4.style.display = "flex";
}

// Handle close buttons for three modals
const marketCloseBtns = document.querySelectorAll("#market .close-btn");
marketCloseBtns.forEach(btn => {
  btn.onclick = function () {
    const targetId = btn.getAttribute("data-target");
    const modalToClose = document.getElementById(targetId);
    modalToClose.style.display = "none";
    // Clear iframe source
    modalToClose.querySelector("iframe").src = "";
  }
});
