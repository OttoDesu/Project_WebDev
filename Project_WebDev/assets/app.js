document.addEventListener('DOMContentLoaded', () => {
  const flashes = document.querySelectorAll('.alert');
  flashes.forEach(f => setTimeout(() => f.classList.add('fade'), 4000));
});
