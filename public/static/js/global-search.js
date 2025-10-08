'use strict';

const globalSearch = {
  init: () => {
    const formSearch = document.getElementById('form-search');
    const inputSearch = formSearch.querySelector('input[name="q"]');
    let timeout = null;

    inputSearch.addEventListener('input', (event) => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        const q = event.target.value.trim();

        if (q.length < 2) {
          formSearch.querySelector('.form-search__suggestion').remove();
          return;
        }

        lotv.ajax({
          url: '/global-search?q=' + encodeURIComponent(q),
          success: res => {
            globalSearch.showSuggestions(res);
          },
        });
      }, 300);
    });
  },

  showSuggestions: data => {
    const formSearch = document.getElementById('form-search');
    const suggestion = document.createElement('div');
    suggestion.classList.add('form-search__suggestion');

    if (data?.actresses?.length) {
      const actressGroup = globalSearch.createGroup('Actresses');
      const actressItems = document.createElement('div');
      actressItems.classList.add('form-search__suggestion-items');

      data.actresses.forEach(actress => {
        const item = document.createElement('a');
        item.classList.add('form-search__suggestion-item');
        item.href = `/actresses/${actress.id}`;
        item.textContent = actress.name + (actress.another_name ? ` (${actress.another_name})` : '');
        actressItems.appendChild(item);
      });
      
      actressGroup.appendChild(actressItems);
      suggestion.appendChild(actressGroup);
    }

    if (data?.videos?.length) {
      const videoGroup = globalSearch.createGroup('Videos');
      const videoItems = document.createElement('div');
      videoItems.classList.add('form-search__suggestion-items');

      data.videos.forEach(video => {
        const item = document.createElement('a');
        item.classList.add('form-search__suggestion-item');
        item.href = `/videos/${video.id}`;
        item.textContent = video.title;
        videoItems.appendChild(item);
      });

      videoGroup.appendChild(videoItems);
      suggestion.appendChild(videoGroup);
    }

    if (data?.tags?.length) {
      const tagGroup = globalSearch.createGroup('Tags');
      const tagItems = document.createElement('div');
      tagItems.classList.add('form-search__suggestion-items');

      data.tags.forEach(tag => {
        const item = document.createElement('a');
        item.classList.add('form-search__suggestion-item');
        item.href = `/videos?tags[]=${tag.slug}`;
        item.textContent = `#${tag.title}`;
        tagItems.appendChild(item);
      });

      tagGroup.appendChild(tagItems);
      suggestion.appendChild(tagGroup);
    }

    formSearch.querySelector('.form-search__suggestion')?.remove();

    if (suggestion.children.length > 0) {
      formSearch.appendChild(suggestion);
    }
  },

  createGroup: title => {
    const group = document.createElement('div');
    const groupTitle = document.createElement('div');

    group.classList.add('form-search__suggestion-group');
    groupTitle.classList.add('form-search__suggestion-group-title');
    groupTitle.textContent = title;
    group.appendChild(groupTitle);

    return group;
  }
};
