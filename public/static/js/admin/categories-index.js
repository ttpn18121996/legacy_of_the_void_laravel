'use strict';

const categoriesIndex = function () {
  const btnUpdateCategories = document.querySelectorAll('#categories-table .btn-edit');
  const btnDeleteCategories = document.querySelectorAll('#categories-table .btn-delete');

  for (const btnUpdateCategory of btnUpdateCategories) {
    btnUpdateCategory.addEventListener('click', e => {
      e.preventDefault();
      const id = btnUpdateCategory.dataset.id;
      const url = btnUpdateCategory.dataset.url;
      const inputCategory = document.getElementById(`category${id}`);

      lotv.ajax({
        method: 'PUT',
        url,
        data: {
          title: inputCategory.value,
        },
        success: res => {
          if (res?.success) {
            lotv.toast.fire({
              title: 'Success',
              message: res.message,
              type: 'success',
            });
            document.getElementById(`slug${id}`).innerText = res.data.slug;
          }
        },
      });
    });
  }
  
  for (const btnDeleteCategory of btnDeleteCategories) {
    btnDeleteCategory.addEventListener('click', e => {
      e.preventDefault();
      const categoryId = btnDeleteCategory.dataset.id;
    });
  }
};
