'use strict';

const videosIndex = function () {
  const btnDeleteVideos = document.querySelectorAll('#videos-table .btn-delete');

  for (const btnDeleteVideo of btnDeleteVideos) {
    btnDeleteVideo.addEventListener('click', e => {
      e.preventDefault();
      const videoId = btnDeleteVideo.dataset.id;
      const url = btnDeleteVideo.dataset.url;

      const dialog = confirmDialog({
        title: 'Confirm Deletion',
        message: 'Are you sure you want to delete this video? This action cannot be undone.',
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
                document.getElementById(`video${videoId}`).remove();
              }
            },
          });
        },
      });
      dialog.show();
    });
  }
};
