// // Modal 1 - Add Role
// const modalRole1 = document.getElementById("modalRole1");
// const btnRole1 = document.getElementById("addRoleBtn1");
// const iframeRole1 = document.getElementById("iframeRole1");

// btnRole1.onclick = function () {
//   iframeRole1.src = "../views/modal_add.php";  // Add Role form
//   modalRole1.style.display = "flex";
// }

// // Modal 2 - Add Description
// const modalDescription1 = document.getElementById("modalDescription1");
// const btnDescription1 = document.getElementById("addDescriptionBtn1");
// const iframeDescription1 = document.getElementById("iframeDescription1");

// btnDescription1.onclick = function () {
//   iframeDescription1.src = "../views/modal_desc.php";  // Add Description form
//   modalDescription1.style.display = "flex";
// }

// // Modal 3 - Remove Role
// const modalRemove1 = document.getElementById("modalRemove1");
// const btnRemove1 = document.getElementById("removeRoleBtn1");
// const iframeRemove1 = document.getElementById("iframeRemove1");

// btnRemove1.onclick = function () {
//   iframeRemove1.src = "../views/modal_remove.php";  // Remove Role form
//   modalRemove1.style.display = "flex";
// }

// // Handle close buttons for three modals
// const performanceCloseBtns = document.querySelectorAll("#performance .close-btn");
// performanceCloseBtns.forEach(btn => {
//   btn.onclick = function () {
//     const targetId = btn.getAttribute("data-target");
//     const modalToClose = document.getElementById(targetId);
//     modalToClose.style.display = "none";
//     // Clear iframe source
//     modalToClose.querySelector("iframe").src = "";
//   }
// });


// Modal 1 - Add Role
const modalRole1 = document.getElementById("modalRole1");
const btnRole1 = document.getElementById("addRoleBtn1");
const iframeRole1 = document.getElementById("iframeRole1");

btnRole1.onclick = function () {
  iframeRole1.src = "../views/modal_add.php";  // Add Role form
  modalRole1.style.display = "flex";
}

// Modal 2 - Add Description
const modalDescription1 = document.getElementById("modalDescription1");
const btnDescription1 = document.getElementById("addDescriptionBtn1");
const iframeDescription1 = document.getElementById("iframeDescription1");

btnDescription1.onclick = function () {
  iframeDescription1.src = "../views/modal_desc.php";  // Add Description form
  modalDescription1.style.display = "flex";
}

// Modal 3 - Remove Role
const modalRemove1 = document.getElementById("modalRemove1");
const btnRemove1 = document.getElementById("removeRoleBtn1");
const iframeRemove1 = document.getElementById("iframeRemove1");

btnRemove1.onclick = function () {
  iframeRemove1.src = "../views/modal_remove.php";  // Remove Role form
  modalRemove1.style.display = "flex";
}

// Handle close buttons for three modals
const performanceCloseBtns = document.querySelectorAll("#performance .close-btn");
performanceCloseBtns.forEach(btn => {
  btn.onclick = function () {
    const targetId = btn.getAttribute("data-target");
    const modalToClose = document.getElementById(targetId);
    modalToClose.style.display = "none";
    modalToClose.querySelector("iframe").src = ""; // Clear iframe
  }
});

// ✅ Add a message listener for iframe communication
window.addEventListener('message', function (event) {
  const { data } = event;

  if (!data || typeof data !== 'object' || !data.type) return;

  switch (data.type) {
    case 'roleDescriptionAdded':
      // Close the description modal and optionally reload
      modalDescription1.style.display = "none";
      iframeDescription1.src = "";
      location.reload();
      break;

    case 'roleExists':
      modalRole1.style.display = "none";
      iframeRole1.src = "";
      location.reload();
      break;

    case 'roleAdded':
      modalRole1.style.display = "none";
      iframeRole1.src = "";
      location.reload();
      break;

    case 'roleRemoved':
      modalRemove1.style.display = "none";
      iframeRemove1.src = "";
      location.reload();
      break;

    default:
      console.warn("Unknown postMessage type:", data.type);
  }
});
