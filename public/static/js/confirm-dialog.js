'use strict';

window.confirmDialog = function (options) {
  const _config = {
    title: options.title || 'Confirm',
    message: options.message || 'Are you sure?',
    onConfirm: options.onConfirm || (() => null),
    onCancel: options.onCancel || (() => null),
  };
  let _dialog = null;

  function createDialog() {
    _dialog = document.createElement('div');
    _dialog.id = 'confirm-dialog';

    const dialogOverlay = document.createElement('div');
    dialogOverlay.classList.add('dialog__overlay');
    
    const dialogBox = document.createElement('div');
    dialogBox.classList.add('dialog__box');
    
    const dialogHeader = document.createElement('div');
    dialogHeader.classList.add('dialog__header');
    dialogHeader.innerText = _config.title;
    
    const dialogBody = document.createElement('div');
    dialogBody.classList.add('dialog__body');
    dialogBody.innerHTML = `<p>${_config.message}</p>`;

    const dialogFooter = document.createElement('div');
    dialogFooter.classList.add('dialog__footer');
    
    const cancelButton = document.createElement('button');
    cancelButton.classList.add('btn--sm', 'btn--secondary');
    cancelButton.innerText = 'Cancel';
    cancelButton.addEventListener('click', () => {
      _config.onCancel();
      hide();
    });
    
    const confirmButton = document.createElement('button');
    confirmButton.classList.add('btn--sm', 'btn--primary');
    confirmButton.innerText = 'Confirm';
    confirmButton.addEventListener('click', () => {
      _config.onConfirm();
      hide();
    });
    
    dialogFooter.appendChild(cancelButton);
    dialogFooter.appendChild(confirmButton);
    
    dialogBox.appendChild(dialogHeader);
    dialogBox.appendChild(dialogBody);
    dialogBox.appendChild(dialogFooter);
    
    _dialog.appendChild(dialogOverlay);
    _dialog.appendChild(dialogBox);
  }

  function show() {
    createDialog();
    document.body.appendChild(_dialog);
  }

  function hide() {
    if (_dialog) {
      _dialog.remove();
      _dialog = null;
    }
  }

  return {
    show,
    hide,
  };
};
