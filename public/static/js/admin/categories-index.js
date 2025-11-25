'use strict';

const categoriesIndex = function () {
  lotv.bindEvent('#categories-table .btn-edit', 'click', e => {
    e.preventDefault();
    const btnUpdateCategory = e.target;
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
};
