'use strict';

const tagsIndex = function () {
  const btnUpdateTags = document.querySelectorAll('#tags-table .btn-edit');
  const btnDeleteTags = document.querySelectorAll('#tags-table .btn-delete');

  for (const btnUpdateTag of btnUpdateTags) {
    btnUpdateTag.addEventListener('click', e => {
      e.preventDefault();
      const id = btnUpdateTag.dataset.id;
      const url = btnUpdateTag.dataset.url;
      const inputTag = document.getElementById(`tag${id}`);

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
  }
  
  for (const btnDeleteTag of btnDeleteTags) {
    btnDeleteTag.addEventListener('click', e => {
      e.preventDefault();
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
  }
};

lotv.useModal();
