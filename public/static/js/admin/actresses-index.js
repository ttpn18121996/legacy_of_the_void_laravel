'use strict';

const actressesIndex = function () {
  lotv.bindEvent('#actresses-table .btn-delete', 'click', e => {
    e.preventDefault();
    const btnDeleteActress = e.target;
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

  lotv.bindEvent('#actresses-table .btn-update-thumbnail', 'click', e => {
    e.preventDefault();
    const url = e.target.dataset.url;

    lotv.ajax({
      method: 'PATCH',
      url,
      success: res => {
        if (res?.success) {
          window.location.reload();
        }
      },
    });
  });
};
