'use strict';

const tagsIndex = function () {
  lotv.bindEvent('#tags-table .btn-edit', 'click', e => {
    e.preventDefault();
    const btnUpdateTag = e.target;
    const id = btnUpdateTag.dataset.id;
    const url = btnUpdateTag.dataset.url;
    const inputTag = document.querySelector(`#tag${id} input`);

    lotv.ajax({
      method: 'PUT',
      url,
      data: {
        title: inputTag.value,
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

  lotv.bindEvent('#tags-table .btn-delete', 'click', e => {
    e.preventDefault();
    const btnDeleteTag = e.target;
    const tagId = btnDeleteTag.dataset.id;
    const url = btnDeleteTag.dataset.url;

    const dialog = confirmDialog({
      title: 'Confirm Deletion',
      message: 'Are you sure you want to delete this tag? This action cannot be undone.',
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
              document.getElementById(`tag${tagId}`).remove();
            }
          },
        });
      },
    });
    dialog.show();
  });
};

lotv.useModal();
