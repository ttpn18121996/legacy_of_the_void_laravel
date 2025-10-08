'use strict';

const reviewWatch = function (setting = {}) {
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
            window.location.href = '/approved';
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
