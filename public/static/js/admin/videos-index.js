'use strict';

const videosIndex = function () {
  lotv.bindEvent('#videos-table .btn-sync-tags', 'click', e => {
    e.preventDefault();
    const btnSyncTags = e.target;
    const videoId = btnSyncTags.dataset.id;
    const url = btnSyncTags.dataset.url;

    lotv.ajax({
      method: 'PATCH',
      url,
      data: {
        video_id: videoId,
      },
      success: res => {
        if (res?.success) {
          window.location.reload();
        }
      },
    });
  });

  lotv.bindEvent('#videos-table .btn-delete', 'click', e => {
    e.preventDefault();
    const btnDeleteVideo = e.target;
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
};
