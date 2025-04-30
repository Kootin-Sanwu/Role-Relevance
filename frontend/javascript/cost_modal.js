// Modal 2 - Add Role
const modalRole2 = document.getElementById("modalRole2");
const btnRole2 = document.getElementById("addRoleBtn2");
const iframeRole2 = document.getElementById("iframeRole2");

btnRole2.onclick = function () {
  iframeRole2.src = "../views/modal_add.php";  // Add Role form
  modalRole2.style.display = "flex";
}

// Modal 2 - Add Description
const modalDescription2 = document.getElementById("modalDescription2");
const btnDescription2 = document.getElementById("addDescriptionBtn2");
const iframeDescription2 = document.getElementById("iframeDescription2");

btnDescription2.onclick = function () {
  iframeDescription2.src = "../views/modal_desc.php";  // Add Description form
  modalDescription2.style.display = "flex";
}

// Modal 3 - Remove Role
const modalRemove2 = document.getElementById("modalRemove2");
const btnRemove2 = document.getElementById("removeRoleBtn2");
const iframeRemove2 = document.getElementById("iframeRemove2");

btnRemove2.onclick = function () {
  iframeRemove2.src = "../views/modal_remove.php";  // Remove Role form
  modalRemove2.style.display = "flex";
}

// Handle close buttons for three modals
const financeCloseBtns = document.querySelectorAll("#finance .close-btn");
financeCloseBtns.forEach(btn => {
  btn.onclick = function () {
    const targetId = btn.getAttribute("data-target");
    const modalToClose = document.getElementById(targetId);
    modalToClose.style.display = "none";
    // Clear iframe source
    modalToClose.querySelector("iframe").src = "";
  }
});


