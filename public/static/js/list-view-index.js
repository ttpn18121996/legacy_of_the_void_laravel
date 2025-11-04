'use strict';

const listViewIndex = function () {
  const frmSearch = document.getElementById('reviews-search');
  const inputSearch = frmSearch.querySelector('input[type="search"]');
  inputSearch.addEventListener('input', function (e) {
    const searchValue = e.target.value.toLowerCase().trim();
    const items = document.querySelectorAll('.data__item--list');

    items.forEach(item => {
      const title = item.dataset.title.toLowerCase();
      if (searchValue.length === 0 || title.includes(searchValue) || (searchValue === '#some' && title.includes(', '))) {
        item.style.display = 'flex';
      } else {
        item.style.display = 'none';
      }
    });
  });
};
