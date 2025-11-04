'use strict';

const listViewWatch = function (setting = {}) {
  const { publishUrl } = setting;
  const videoTitle = setting.videoTitle;

  if (publishUrl) {
    const btnPublish = document.getElementById('publish-video');
    btnPublish.addEventListener('click', e => {
      e.preventDefault();
      lotv.loader.start();

      lotv.ajax({
        method: 'PUT',
        url: publishUrl,
        data: {
          title: videoTitle,
        },
        success: res => {
          if (res?.success) {
            window.location.href = '/list-view?path=approved';
          } else {
            lotv.toast.fire({
              title: 'Error',
              message: res.message,
              type: 'error',
            });
          }
        },
        error: () => {
          lotv.toast.fire({
            title: 'Error',
            message:'An error occurred while trying to publish the video.',
            type: 'error',
          });
        },
      });
    });
  }
};

lotv.useVideoPlayer();
