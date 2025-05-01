// Modal 3 - Add Role
const modalRole3 = document.getElementById("modalRole3");
const btnRole3 = document.getElementById("addRoleBtn3");
const iframeRole3 = document.getElementById("iframeRole3");

btnRole3.onclick = function () {
  iframeRole3.src = "../views/modal_add.php";  // Add Role form
  modalRole3.style.display = "flex";
}

// Modal 3 - Add Description
const modalDescription3 = document.getElementById("modalDescription3");
const btnDescription3 = document.getElementById("addDescriptionBtn3");
const iframeDescription3 = document.getElementById("iframeDescription3");

btnDescription3.onclick = function () {
  iframeDescription3.src = "../views/modal_desc.php";  // Add Description form
  modalDescription3.style.display = "flex";
}

// Modal 3 - Remove Role
const modalRemove3 = document.getElementById("modalRemove3");
const btnRemove3 = document.getElementById("removeRoleBtn3");
const iframeRemove3 = document.getElementById("iframeRemove3");

btnRemove3.onclick = function () {
  iframeRemove3.src = "../views/modal_remove.php";  // Remove Role form
  modalRemove3.style.display = "flex";
}

// Handle close buttons for three modals
const revenueCloseBtns = document.querySelectorAll("#revenue .close-btn");
revenueCloseBtns.forEach(btn => {
  btn.onclick = function () {
    const targetId = btn.getAttribute("data-target");
    const modalToClose = document.getElementById(targetId);
    modalToClose.style.display = "none";
    // Clear iframe source
    modalToClose.querySelector("iframe").src = "";
  }
});
