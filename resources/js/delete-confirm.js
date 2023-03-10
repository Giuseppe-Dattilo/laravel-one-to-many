const deleteForms = document.querySelectorAll('.delete-form');
deleteForms.forEach(form => {
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const name = form.getAttribute('data-name') || 'elemento';
    const hasConfirmed = confirm(`Sei sicuro di voler eliminare questo ${name}?`);
    if (hasConfirmed) form.submit();
  });
});