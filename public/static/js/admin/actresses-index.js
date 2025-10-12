'use strict';

const actressesIndex = function () {
  const btnDeleteActresses = document.querySelectorAll('#actresses-table .btn-delete');
  const btnUpdateThumbnail = document.querySelectorAll('#actresses-table .btn-update-thumbnail');

  for (const btnDeleteActress of btnDeleteActresses) {
    btnDeleteActress.addEventListener('click', e => {
      e.preventDefault();
      const actressId = btnDeleteActress.dataset.id;
      const url = btnDeleteActress.dataset.url;

      const dialog = confirmDialog({
        title: 'Confirm Deletion',
        message: 'Are you sure you want to delete this actress? This action cannot be undone.',
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
                document.getElementById(`actress${actressId}`).remove();
              }
            },
          });
        },
      });
      dialog.show();
    });
  }
};
