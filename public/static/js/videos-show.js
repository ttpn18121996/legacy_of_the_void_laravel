'use strict';

const videosShow = function (setting = {}) {
  const { updateTagsUrl, updateActressesUrl, updateCategoriesUrl, getActressesOptionsUrl, getTagsOptionsUrl } = setting;
  const videoId = setting.videoId;

  if (!videoId) {
    console.error('videoId is required');
    return;
  }

  if (updateActressesUrl) {
    const btnUpdateActresses = document.getElementById('update-actresses');
    btnUpdateActresses.addEventListener('click', e => {
      e.preventDefault();
      lotv.loader.start();
      const inputActresses = document.querySelectorAll('input[name="actresses[]"]');
      const actresses = [];
      for (const inputActress of inputActresses) {
        if (inputActress.checked) {
          actresses.push(inputActress.value);
        }
      }

      lotv.ajax({
        method: 'POST',
        url: updateActressesUrl,
        data: {
          video_id: videoId,
          actresses,
        },
        success: res => {
          if (res?.success) {
            window.location.reload();
          }
        },
      });
    });
  }

  if (updateCategoriesUrl) {
    const btnUpdateCategories = document.getElementById('update-categories');
    btnUpdateCategories.addEventListener('click', e => {
      e.preventDefault();
      const inputCategories = document.querySelectorAll('input[name="categories[]"]');
      const categories = [];
      for (const inputCategory of inputCategories) {
        if (inputCategory.checked) {
          categories.push(inputCategory.value);
        }
      }

      lotv.ajax({
        method: 'POST',
        url: updateCategoriesUrl,
        data: {
          video_id: videoId,
          categories,
        },
        success: res => {
          if (res?.success) {
            window.location.reload();
          }
        },
      });
    });
  }

  if (updateTagsUrl) {
    const btnUpdateTags = document.getElementById('update-tags');
    btnUpdateTags.addEventListener('click', e => {
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
          video_id: videoId,
          tags,
        },
        success: res => {
          if (res?.success) {
            window.location.reload();
          }
        },
      });
    });
  }

  if (getActressesOptionsUrl) {
    function loadActressesOptions() {
      lotv.ajax({
        method: 'GET',
        url: getActressesOptionsUrl,
        success: res => {
          if (res?.options) {
            const selectActresses = document.getElementById('actresses-list');
            selectActresses.innerHTML = '';
            let items = '';
            res.options.forEach(option => {
              items += `<div class="selection-list__checkbox">
              <input
                  id="actresses-${option.value}"
                  type="checkbox"
                  name="actresses[]"
                  value="${option.value}"
                  ${option.selected ? 'checked' : ''}
              >
              <label for="actresses-${option.value}">${option.label}</label>
            </div>`;
            });
            selectActresses.innerHTML = items;
          }
        },
      });
    }
    loadActressesOptions();
    document.querySelectorAll('.toggle-modal').forEach(btn => {
      const target = btn.dataset.target;
      if (target === '#actresses-modal') {
        btn.addEventListener('click', e => {
          e.preventDefault();
          loadActressesOptions();
        });
      }
    });
  }

  if (getTagsOptionsUrl) {
    function loadTagsOptions() {
      lotv.ajax({
        method: 'GET',
        url: getTagsOptionsUrl,
        success: res => {
          if (res?.options) {
            const selectTags = document.getElementById('tags-list');
            selectTags.innerHTML = '';
            let items = '';
            res.options.forEach(option => {
              items += `<div class="selection-list__checkbox">
              <input
                  id="tags-${option.value}"
                  type="checkbox"
                  name="tags[]"
                  value="${option.value}"
                  ${option.selected ? 'checked' : ''}
              >
              <label for="tags-${option.value}">${option.label}</label>
            </div>`;
            });
            selectTags.innerHTML = items;
          }
        },
      });
    }
    loadTagsOptions();
    document.querySelectorAll('.toggle-modal').forEach(btn => {
      const target = btn.dataset.target;
      if (target === '#tags-modal') {
        btn.addEventListener('click', e => {
          e.preventDefault();
          loadTagsOptions();
        });
      }
    });
  }
};

lotv.useModal();
lotv.useSelectionList();
lotv.useVideoPlayer();
