const headerSubMenuElements = Array.from(document.querySelectorAll('[data-menu]'));

const showSubMenu = (event: Event) => {
  const targetElement = event.target as HTMLUListElement;
  targetElement.classList.add('active');
  const menu = targetElement?.querySelector('ul');

  targetElement.addEventListener('mouseleave', () => targetElement.classList.remove('active'));
  menu?.addEventListener('mouseleave', () => {
    targetElement?.classList.remove('active');
  });
};

window.addEventListener('keydown', (event) => {
  const target = event.target as HTMLElement;

  if (event.key === 'Tab' && target.parentElement?.dataset.menu) {
    document.querySelectorAll('[data-menu]').forEach((element) => {
      element.classList.remove('active');
    });
  }

  if (event.key === 'Tab' && target.classList.contains('submenu-item')) {
    target.closest('[data-menu]')?.classList.add('active');
  }
});

for (const headerSubmenuElement of headerSubMenuElements) {
  headerSubmenuElement.addEventListener('mouseenter', showSubMenu);
}

setTimeout(() => {
  const flashesContainer = document.querySelector('.flashes');
  flashesContainer?.classList.add('hidden');

  setTimeout(() => {
    flashesContainer?.remove();
  }, 500);
}, 4_000);

const searchInput = document.querySelector('.search-form input');
const mainColor = window
  .getComputedStyle(document.documentElement)
  .getPropertyValue('--main-color');

searchInput?.addEventListener('focus', () => {
  const searchForm = document.querySelector('.search-form') as HTMLFormElement;
  searchForm.style.borderColor = mainColor;
});

searchInput?.addEventListener('blur', () => {
  const searchForm = document.querySelector('.search-form') as HTMLFormElement;
  searchForm.style.borderColor = '';
});
