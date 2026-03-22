'use strict';

const categoriesIndex = function () {
  lotv.bindEvent('#categories-table .btn-delete', 'click', e => {
    e.preventDefault();
    const btnDeleteCategory = e.target;
    const categoryId = btnDeleteCategory.dataset.id;
    const url = btnDeleteCategory.dataset.url;

    const dialog = confirmDialog({
      title: 'Confirm Deletion',
      message: 'Are you sure you want to delete this category? This action cannot be undone.',
      onConfirm: () => {
        lotv.ajax({
          method: 'DELETE',
          url,
          success: res => {
            if (res?.success) {
              lotv.toast.fire({
                title: 'Success',
                message: res.message,
                type: 'success',
              });
              document.getElementById(`category${categoryId}`).remove();
            }
          },
        });
      },
    });
    dialog.show();
  });
};
