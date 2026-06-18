document.querySelectorAll('[data-steps]').forEach((list) => {
  let steps = [];
  try {
    steps = JSON.parse(list.dataset.steps || '[]');
  } catch (error) {
    steps = String(list.dataset.steps || '').split(/\r?\n/).filter(Boolean);
  }

  list.innerHTML = '';
  steps.forEach((step, index) => {
    const item = document.createElement('li');
    item.textContent = step;
    item.style.setProperty('--step-index', String(index + 1));
    list.appendChild(item);
  });
});
