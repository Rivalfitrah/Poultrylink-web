
// Toggling Dropdown Menu
const accountIcon = document.getElementById("account-icon");
const accountDropdown = document.getElementById("account-dropdown");

accountIcon.addEventListener("click", function () {
  accountDropdown.classList.toggle("hidden");

  if (!accountDropdown.classList.contains("hidden")) {
    accountDropdown.classList.add("opacity-100", "scale-100");
    accountDropdown.classList.remove("opacity-0", "scale-95");
  } else {
    accountDropdown.classList.add("opacity-0", "scale-95");
    accountDropdown.classList.remove("opacity-100", "scale-100");
  }
});

// Klik di luar dropdown untuk menutup
window.addEventListener("click", function (e) {
  if (!accountIcon.contains(e.target)) {
    accountDropdown.classList.add("hidden");
    accountDropdown.classList.add("opacity-0", "scale-95");
    accountDropdown.classList.remove("opacity-100", "scale-100");
  }
});