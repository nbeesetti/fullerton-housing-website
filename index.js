function expandSearch() {
  const searchInput = document.querySelector(".search-input");
  searchInput.classList.toggle("expanded");

  if (searchInput.classList.contains("expanded")) {
    searchInput.focus();
  }
}

