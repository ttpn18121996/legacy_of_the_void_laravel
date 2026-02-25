'use strict';

const actressesShow = function (setting = {}) {
  const { updateTagsUrl } = setting;
  const actressId = setting.actressId;

  if (!actressId) {
    console.error('actressId is required');
    return;
  }

  if (updateTagsUrl) {
    const btnUpdateTags = document.getElementById('update-tags');
    lotv.bindEvent(btnUpdateTags, 'click', e => {
      e.preventDefault();
      const inputTags = document.querySelectorAll('input[name="tags[]"]');
      const tags = [];
      for (const inputTag of inputTags) {
        if (inputTag.checked) {
          tags.push(inputTag.value);
        }
      }

      lotv.ajax({
        method: 'POST',
        url: updateTagsUrl,
        data: {
          actress_id: actressId,
          tags,
        },
        success: res => {
          if (res?.success) {
            window.location.reload();
          }
        }
      });
    });
  }
};

lotv.useModal();
lotv.useSelectionList();
