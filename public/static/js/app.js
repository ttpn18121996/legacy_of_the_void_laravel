'use strict';

window.lotv = (function () {
  const loader = {
    start() {
      const elemLoading = document.createElement('div');
      elemLoading.style.width = '100vw';
      elemLoading.style.height = '100vh';
      elemLoading.style.position = 'fixed';
      elemLoading.style.top = '0';
      elemLoading.style.left = '0';
      elemLoading.style.zIndex = '100';
      elemLoading.style.backgroundColor = '#000';
      elemLoading.style.opacity = '.25';
      elemLoading.style.display = 'flex';
      elemLoading.style.justifyContent = 'center';
      elemLoading.style.alignItems = 'center';
      elemLoading.id = 'loading';

      const elemSpinner = document.createElement('div');
      elemSpinner.style.width = '32px';
      elemSpinner.style.height = '32px';
      elemSpinner.style.borderRadius = '100%';
      elemSpinner.style.borderStyle = 'solid';
      elemSpinner.style.borderWidth = '4px';
      elemSpinner.style.borderColor = '#f1f1f1';
      elemSpinner.style.borderTopColor = 'transparent';
      elemSpinner.animate([{ transform: 'rotate(0deg)' }, { transform: 'rotate(360deg)' }], {
        duration: 500,
        iterations: Infinity,
        easing: 'linear',
      });

      elemLoading.appendChild(elemSpinner);

      document.body.appendChild(elemLoading);
    },
    stop() {
      document.body.removeChild(document.getElementById('loading'));
    },
  };

  const toast = {
    identifier: 0,
    timeout: null,
    iconSuccess:
      '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg icon-success"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    iconError:
      '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg icon-danger"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>',
    fire({ title = '', message = '', type = 'success', duration = 3000 }) {
      const toastId = `toast-${this.identifier}`;
      const eleToastTitleText = document.createElement('div');
      eleToastTitleText.classList.add('toast__title-text', 'space-x-2');
      eleToastTitleText.innerHTML = `${type === 'success' ? this.iconSuccess : this.iconError}<span>${title}</span>`;

      const handleClose = target => {
        const _target = document.getElementById(target);
        _target.style.opacity = 0;
        this.timeout && clearTimeout(this.timeout);
        this.timeout = setTimeout(() => _target.remove(), 500);
      };

      const eleToastClose = document.createElement('button');
      eleToastClose.classList.add('toast__title-close');
      eleToastClose.dataset.target = toastId;
      eleToastClose.innerHTML =
        '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
      eleToastClose.addEventListener('click', e => {
        e.preventDefault();
        handleClose(eleToastClose.dataset.target);
      });

      const eleToastTitle = document.createElement('div');
      eleToastTitle.classList.add('toast__title');
      eleToastTitle.appendChild(eleToastTitleText);
      eleToastTitle.appendChild(eleToastClose);

      const eleToastMessage = document.createElement('div');
      eleToastMessage.classList.add('toast__message');
      eleToastMessage.innerText = message;

      const eleToast = document.createElement('div');
      eleToast.classList.add('toast');
      eleToast.id = toastId;
      eleToast.appendChild(eleToastTitle);
      eleToast.appendChild(eleToastMessage);

      if (duration > 0) {
        let timeout = setTimeout(() => {
          handleClose(toastId);
          clearTimeout(timeout);
        }, duration);
      }

      document.body.appendChild(eleToast);
      this.identifier++;
    },
  };

  function init() {
    sidebarHandler();
    dropdownHandler();
    menuHasChildrenHandler();
    inputPasswordToggle();
  }

  function sidebarHandler() {
    const btnToggleSidebar = document.querySelector('.btn-toggle-sidebar');
    const sidebar = document.getElementById('sidebar');
    if (!btnToggleSidebar || !sidebar) {
      return;
    }

    btnToggleSidebar.addEventListener('click', () => {
      if (sidebar.classList.contains('sidebar-hidden')) {
        sidebar.classList.remove('sidebar-hidden');
        sidebar.classList.add('sidebar-show');
      } else {
        sidebar.classList.add('sidebar-hidden');
        sidebar.classList.remove('sidebar-show');
      }
    });
    const main = document.querySelector('main');
    main.addEventListener('click', () => {
      sidebar.classList.add('sidebar-hidden');
      sidebar.classList.remove('sidebar-show');
    });
  }

  function dropdownHandler() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
      const trigger = dropdown.querySelector('.dropdown-trigger');
      const content = dropdown.querySelector(trigger.dataset.target);
      content.style.display = 'none';
      trigger.addEventListener('click', () => {
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
      });
    });

    const main = document.querySelector('main');
    main.addEventListener('click', () => {
      dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const content = dropdown.querySelector(trigger.dataset.target);
        content.style.display = 'none';
      });
    });
  }

  function menuHasChildrenHandler() {
    const menuHasChildren = document.querySelectorAll('.has-children');
    if (!menuHasChildren) {
      return;
    }

    menuHasChildren.forEach(item => {
      item.addEventListener('click', () => {
        item.classList.toggle('open');
      });
    });
  }

  function inputPasswordToggle() {
    const inputGroups = document.querySelectorAll('.form-input__group');
    if (!inputGroups) {
      return;
    }

    for (const inputGroup of inputGroups) {
      const inputPassword = inputGroup.querySelector('input[type="password"]');
      if (!inputPassword) {
        continue;
      }
      const toggleIcon = document.createElement('div');
      toggleIcon.classList.add('form-input__icon-toggle');
      toggleIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
      </svg>`;
      toggleIcon.addEventListener('click', e => {
        const _this = e.target;

        if (inputPassword.type === 'password') {
          inputPassword.type = 'text';
          _this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
          </svg>`;
        } else {
          inputPassword.type = 'password';
          _this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
          </svg>`;
        }
      });

      inputGroup.appendChild(toggleIcon);
    }
  }

  function dispatchVideoThumbnail() {
    const videoControls = document.querySelectorAll('.video-card__thumbnails-control');

    for (const videoControl of videoControls) {
      const btnSlideItems = videoControl.querySelectorAll('button');

      for (const btnSlideItem of btnSlideItems) {
        if (btnSlideItem.dataset.isDefault === '1') {
          document.querySelector(btnSlideItem.dataset.target).src = btnSlideItem.dataset.src;
        }

        btnSlideItem.addEventListener('click', e => {
          e.preventDefault();
          const target = document.querySelector(btnSlideItem.dataset.target);
          const src = btnSlideItem.dataset.src;
          target.src = src;
          btnSlideItem.classList.add('active');

          for (const otherControl of btnSlideItems) {
            if (otherControl !== btnSlideItem) {
              otherControl.classList.remove('active');
            }
          }
        });
      }
    }
  }

  function useModal() {
    const btnToggles = document.querySelectorAll('.toggle-modal');

    for (const btnToggle of btnToggles) {
      const modal = document.querySelector(`${btnToggle.dataset.target} .modal-wrapper`);
      const overlay = modal.querySelector('.modal__overlay');

      overlay.addEventListener('click', () => {
        modal.style.display = 'none';
      });

      btnToggle.addEventListener('click', () => {
        modal.style.display = 'block';
        modal.querySelector('.close-modal').addEventListener('click', () => {
          modal.style.display = 'none';
        });
      });
    }
  }

  function useSelectionList() {
    const inputSearchs = document.querySelectorAll('.selection-list__search-input');
    for (const inputSearch of inputSearchs) {
      inputSearch.addEventListener('input', e => {
        const keyword = e.target.value;
        const items = e.target
          .closest('.selection-list')
          .querySelectorAll('.selection-list__items .selection-list__checkbox');

        for (const item of items) {
          if (item.querySelector('label').textContent.toLowerCase().includes(keyword.toLowerCase())) {
            item.style.display = 'flex';
          } else {
            item.style.display = 'none';
          }
        }
      });
    }
  }

  function useScrollToTop() {
    const btnScrollToTop = document.createElement('button');
    btnScrollToTop.style.position = 'fixed';
    btnScrollToTop.style.right = '1rem';
    btnScrollToTop.style.bottom = '1rem';
    btnScrollToTop.style.width = '2.5rem';
    btnScrollToTop.style.height = '2.5rem';
    btnScrollToTop.style.border = 'none';
    btnScrollToTop.style.outline = 'none';
    btnScrollToTop.style.borderRadius = '50%';
    btnScrollToTop.style.background = '#fff';
    btnScrollToTop.style.cursor = 'pointer';
    btnScrollToTop.style.display = 'none';
    btnScrollToTop.style.justifyContent = 'center';
    btnScrollToTop.style.alignItems = 'center';
    btnScrollToTop.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.2)';
    btnScrollToTop.style.zIndex = '100';
    btnScrollToTop.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
      </svg>
    `;
    document.body.appendChild(btnScrollToTop);
    btnScrollToTop.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    });
    window.addEventListener('scroll', () => {
      if (window.scrollY > 200) {
        btnScrollToTop.style.display = 'flex';
      } else {
        btnScrollToTop.style.display = 'none';
      }
    });
  }

  function ajax(config = {}) {
    const _config = {
      url: '',
      method: 'GET',
      data: null,
      headers: {},
      success: () => {},
      error: error => console.error(error.message),
      complete: () => {},
      ...config,
    };
    const _requests = {
      method: _config.method,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        ..._config.headers,
      },
    };
    const body = _config.data;
    if (body) {
      _requests.body = JSON.stringify(body);
    }

    loader.start();

    return fetch(_config.url, {
      ..._requests,
    })
      .then(res => res.json())
      .then(_config.success)
      .catch(_config.error)
      .finally(() => {
        _config.complete();
        loader.stop();
      });
  }

  function useVideoPlayer() {
    const videoPlayer = document.getElementById('video-player');
    if (!videoPlayer) return;

    function isTypingField(el) {
      if (!el) return false;
      const tag = el.tagName.toLowerCase();
      if (tag === 'input' || tag === 'textarea' || tag === 'select' || el.isContentEditable) return true;
      return false;
    }

    document.addEventListener('keydown', e => {
      if (isTypingField(e.target)) return;
      if (e.ctrlKey || e.altKey || e.metaKey) return;

      const key = e.key.toLowerCase();
      switch (key) {
        case ' ':
        case 'k':
          e.preventDefault();
          if (videoPlayer.paused) {
            videoPlayer.play();
          } else {
            videoPlayer.pause();
          }
          break;
        case 'l':
          e.preventDefault();
          videoPlayer.currentTime = Math.min(videoPlayer.duration, videoPlayer.currentTime + 10);
          break;
        case 'j':
          e.preventDefault();
          videoPlayer.currentTime = Math.max(0, videoPlayer.currentTime - 10);
          break;
        case 'arrowright':
          e.preventDefault();
          videoPlayer.currentTime = Math.min(videoPlayer.duration, videoPlayer.currentTime + 5);
          break;
        case 'arrowleft':
          e.preventDefault();
          videoPlayer.currentTime = Math.max(0, videoPlayer.currentTime - 5);
          break;
        case 'f':
          e.preventDefault();
          if (videoPlayer.requestFullscreen) {
            videoPlayer.requestFullscreen();
          } else if (videoPlayer.webkitRequestFullscreen) {
            videoPlayer.webkitRequestFullscreen();
          } else if (videoPlayer.mozRequestFullScreen) {
            videoPlayer.mozRequestFullScreen();
          } else if (videoPlayer.msRequestFullscreen) {
            videoPlayer.msRequestFullscreen();
          }
          break;
      }
    });
  }

  function bindEvent(selector, event, callback) {
    let elements;

    if (selector instanceof HTMLElement) {
      elements = [selector];
    } else if (typeof selector === 'string') {
      selector = selector.trim();
      elements = document.querySelectorAll(selector);
    }

    if (!elements) {
      return;
    }

    for (const element of elements) {
      if (event === 'clickOutside') {
        handleClickOutside(element, callback);
      } else {
        element.addEventListener(event, callback);
      }
    }
  }

  function handleClickOutside(element, callback) {
    function onClick(event) {
      if (!element.contains(event.target)) {
        callback();
        document.removeEventListener("click", onClick);
      }
    }
    document.addEventListener('click', onClick);
  }

  return {
    init,
    dispatchVideoThumbnail,
    useModal,
    useSelectionList,
    useScrollToTop,
    useVideoPlayer,
    ajax,
    loader,
    toast,
    bindEvent,
  };
})();
