// Filtro per categorie sul portfolio
const filters = document.querySelectorAll('[data-filter]');
const cards = document.querySelectorAll('[data-category]');


filters.forEach(btn => {
btn.addEventListener('click', () => {
const f = btn.dataset.filter;
filters.forEach(b => b.classList.remove('active'));
btn.classList.add('active');
cards.forEach(c => {
c.style.display = (f === 'all' || c.dataset.category === f) ? '' : 'none';
});
});
});

