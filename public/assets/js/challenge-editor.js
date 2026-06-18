document.querySelectorAll('[data-reset-editor]').forEach((button) => {
  button.addEventListener('click', () => {
    const form = button.closest('form');
    const editor = form ? form.querySelector('.code-editor') : null;
    if (!editor) {
      return;
    }
    editor.value = editor.dataset.starter || '';
    editor.focus();
  });
});
