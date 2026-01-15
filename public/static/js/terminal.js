'use strict';

const terminal = {
  _terminal: null,
  _prompt: null,
  _input: null,
  _output: null,
  _caret: null,
  _timeout: null,
  _canvas: null,
  _context: null,
  _info: {
    username: null,
    is_logged_in: false,
    php_ver: 'unknown',
    laravel_ver: 'unknown',
  },

  _history: [],
  _historyIndex: -1,
  

  configure(data) {
    this._info = { ...this._info, ...data };

    return this;
  },

  init() {
    this._terminal = this.createElement('terminal__container');
    this._output = this.createElement('terminal__output');
    this._prompt = this.createElement('terminal__prompt');
    this._input = this.createElement('terminal__input', 'input', { autofocus: true, name: 'terminal_input' });
    this._caret = this.createElement('terminal__caret');
    this._canvas = document.createElement('canvas');
    this._context = this._canvas.getContext('2d');
    this.mountElements();
    this.bindEvents();
  },

  createElement(className, elementType = 'div', attributes = {}) {
    const element = document.createElement(elementType);
    element.className = className;
    Object.entries(attributes).forEach(([key, value]) => {
      element.setAttribute(key, value);
    });
    return element;
  },

  mountElements() {
    this._terminal.appendChild(this._output);

    const inputWrapper = this.createElement('terminal__input-wrapper');
    inputWrapper.appendChild(this._prompt);
    const inputContainer = this.createElement('terminal__input');
    inputContainer.appendChild(this._input);
    inputContainer.appendChild(this._caret);
    inputWrapper.appendChild(inputContainer);

    this._terminal.appendChild(inputWrapper);

    document.body.appendChild(this._terminal);
  },

  bindEvents() {
    document.addEventListener('click', () => {
      this._input.focus();
    });
    this.authenticate();
    this.inputEvents();
  },

  authenticate() {
    if (this._info.is_logged_in) {
      this._input.type = 'text';
      this._prompt.textContent = `${this._info.username} →`;
      return;
    }

    if (!this._info.username) {
      this._prompt.textContent = 'Enter username:';
    } else {
      this._prompt.textContent = 'Enter password:';
      this._input.type = 'password';
    }
  },

  inputEvents() {
    this._input.addEventListener('focus', () => {
      this._caret.style.display = 'inline-block';
    });
    this._input.addEventListener('blur', event => {
      event.target.classList.remove('typing');
      this._caret.style.display = 'none';
    });
    this._input.addEventListener('keydown', event => {
      requestAnimationFrame(() => {
        this.updateCaret();
      });
      if (event.key === 'Enter') {
        event.preventDefault();
        const val = event.target.value.trim();
        if (val) {
          if (!this._info.is_logged_in) {
            this.loginHandler(val);
          } else {
            this.executeCommand(val);
          }
        }
        this._input.value = '';
      } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        if (this._historyIndex > 0) {
          this._historyIndex -= 1;
          this._input.value = this._history[this._historyIndex];
        }
      } else if (event.key === 'ArrowDown') {
        event.preventDefault();
        if (this._historyIndex < this._history.length - 1) {
          this._historyIndex += 1;
          this._input.value = this._history[this._historyIndex];
        } else {
          this._historyIndex = this._history.length;
          this._input.value = '';
        }
      }

      this.updateCaret();
    });
    this._input.addEventListener('input', event => {
      event.target.classList.add('typing');
      this.updateCaret();

      clearTimeout(this._timeout);
      this._timeout = setTimeout(() => {
        event.target.classList.remove('typing');
      }, 500);
    });
  },

  updateCaret() {
    const input = this._input;
    const style = window.getComputedStyle(this._input);

    this._context.font = style.font;

    const cursorIndex = input.selectionStart;
    const textBeforeCursor = input.value.slice(0, cursorIndex);

    const textWidth = this._context.measureText(textBeforeCursor).width;

    const paddingLeft = parseFloat(style.paddingLeft) || 0;
    const letterSpacing = parseFloat(style.letterSpacing) || 0;
    const scrollLeft = input.scrollLeft;

    this._caret.style.left =
      `${paddingLeft
        + textWidth
        + letterSpacing * cursorIndex
        - scrollLeft}px`;
  },

  executeCommand(command) {
    this._history.push(command);
    this._historyIndex = this._history.length;
    const parts = command.split(' ');
    const baseCommand = parts[0].toLowerCase();
    const args = parts.slice(1);

    switch (baseCommand) {
      case 'help':
        this.helpOutput();
        break;
      case 'clear':
      case 'cls':
        this._output.innerHTML = '';
        break;
      case 'exit':
      case 'quit':
        this.terminate();
        break;
      case 'logout':
        this.logout();
        break;
      case 'refresh':
        window.location.reload();
        break;
      default:
        this.callToCommandExecutor(baseCommand, args);
        break;
    }
  },

  loginHandler(data) {
    if (!this._info.username && data === 'root') {
      this._info.username = data;
      this.authenticate();
    } else if (!this._info.is_logged_in && data === 'password') {
      this._info.is_logged_in = true;
      this.authenticate();
      this.printOutput(`Welcome, ${this._info.username}! Type 'help' for a list of commands.`);
    }
  },

  printOutput(message, isClear = false) {
    if (isClear) {
      this._output.innerHTML = '';
    }
    this._output.innerHTML += message + '<br>';
    this._terminal.scrollTop = this._terminal.scrollHeight;
  },

  helpOutput() {
    const helpText = `
      <p>PHP version: ${this._info.php_ver} - Laravel version: ${this._info.laravel_ver}</p><br>
      Available commands:<br>
      <table class="terminal__list">
        <tr><th>help</th><td>Show this help message</td></tr>
        <tr><th>&nbsp;</th><td>&nbsp;</td></tr>
        <tr><th>videos:detail &lt;id&gt;</th><td>Show video details</td></tr>
        <tr><th>videos:list</th><td>List all videos</td></tr>
        <tr><th>videos:tags &lt;tag1&gt; [&lt;tag2&gt; ...]</th><td>List videos with specific tags</td></tr>
        <tr><th>&nbsp;</th><td>&nbsp;</td></tr>
        <tr><th>refresh</th><td>Reload the terminal page</td></tr>
        <tr><th>clear|cls</th><td>Clear the terminal</td></tr>
        <tr><th>exit|quit</th><td>Exit the terminal</td></tr>
        <tr><th>logout</th><td>Logout of this session</td></tr>
      </table>
    `;
    this.printOutput(helpText);
  },

  callToCommandExecutor(command, args = []) {
    this.printOutput('Processing...', true);

    fetch('/terminal/execute-command', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ command, args }),
    }).then(response => response.json()).then(data => {
      if (data.success) {
        this.printOutput(data.content, true);
      } else {
        this.printOutput(`Error: ${data.message}`);
      }
    });
  },

  terminate() {
    this.printOutput('Goodbye!', true);

    fetch('/terminal/terminate', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
    }).then(response => response.json()).then(data => {
      if (data.success) {
        setTimeout(() => {
          window.location.href = data.redirect_to;
        }, 3000);
      } else {
        this.printOutput(`Error: ${data.message}`);
      }
    });
  },

  logout() {
    this.printOutput('Logging out...', true);

    fetch('/logout', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
    }).then(data => {
      setTimeout(() => {
        window.location.href = 'login';
      }, 3000);
    });
  },
};
window.terminal = terminal;
