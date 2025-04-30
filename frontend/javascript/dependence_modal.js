// Modal 6 - Add Role
const modalRole6 = document.getElementById("modalRole6");
const btnRole6 = document.getElementById("addRoleBtn6");
const iframeRole6 = document.getElementById("iframeRole6");

btnRole6.onclick = function () {
  iframeRole6.src = "../views/modal_add.php";  // Add Role form
  modalRole6.style.display = "flex";
}

// Modal 6 - Add Description
const modalDescription6 = document.getElementById("modalDescription6");
const btnDescription6 = document.getElementById("addDescriptionBtn6");
const iframeDescription6 = document.getElementById("iframeDescription6");

btnDescription6.onclick = function () {
  iframeDescription6.src = "../views/modal_desc.php";  // Add Description form
  modalDescription6.style.display = "flex";
}

// Modal 6 - Remove Role
const modalRemove6 = document.getElementById("modalRemove6");
const btnRemove6 = document.getElementById("removeRoleBtn6");
const iframeRemove6 = document.getElementById("iframeRemove6");

btnRemove6.onclick = function () {
  iframeRemove6.src = "../views/modal_remove.php";  // Remove Role form
  modalRemove6.style.display = "flex";
}

// Handle close buttons for three modals
const dependenceCloseBtns = document.querySelectorAll("#dependence .close-btn");
dependenceCloseBtns.forEach(btn => {
  btn.onclick = function () {
    const targetId = btn.getAttribute("data-target");
    const modalToClose = document.getElementById(targetId);
    modalToClose.style.display = "none";
    // Clear iframe source
    modalToClose.querySelector("iframe").src = "";
  }
});

// // Make sure the code below is in the correct javascript file. It should be in the last js script file that is importedin the metrics.html file
// const allModals = [...document.querySelectorAll("#performance .modal"), ...document.querySelectorAll("#finance .modal"), ...document.querySelectorAll("#revenue .modal"), ...document.querySelectorAll("#market .modal"), ...document.querySelectorAll("#susceptible .modal"), ...document.querySelectorAll("#dependence .modal")];
// window.onclick = function(event) {
//   allModals.forEach(modal => {
//     if (event.target === modal) {
//       modal.style.display = "none";
//       const iframe = modal.querySelector("iframe");
//       if (iframe) { iframe.src = ""; }
//     }
//   });
// };