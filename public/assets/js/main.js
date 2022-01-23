"use strict";
const headerSubMenuElements = Array.from(document.querySelectorAll('[data-menu]'));
const showSubMenu = (event) => {
    const targetElement = event.target;
    targetElement.classList.add('active');
    const menu = targetElement === null || targetElement === void 0 ? void 0 : targetElement.querySelector('ul');
    targetElement.addEventListener('mouseleave', () => targetElement.classList.remove('active'));
    menu === null || menu === void 0 ? void 0 : menu.addEventListener('mouseleave', () => {
        targetElement === null || targetElement === void 0 ? void 0 : targetElement.classList.remove('active');
    });
};
window.addEventListener('keydown', (event) => {
    var _a, _b;
    const target = event.target;
    if (event.key === 'Tab' && ((_a = target.parentElement) === null || _a === void 0 ? void 0 : _a.dataset.menu)) {
        document.querySelectorAll('[data-menu]').forEach((element) => {
            element.classList.remove('active');
        });
    }
    if (event.key === 'Tab' && target.classList.contains('submenu-item')) {
        (_b = target.closest('[data-menu]')) === null || _b === void 0 ? void 0 : _b.classList.add('active');
    }
});
for (const headerSubmenuElement of headerSubMenuElements) {
    headerSubmenuElement.addEventListener('mouseenter', showSubMenu);
}
setTimeout(() => {
    const flashesContainer = document.querySelector('.flashes');
    flashesContainer === null || flashesContainer === void 0 ? void 0 : flashesContainer.classList.add('hidden');
    setTimeout(() => {
        flashesContainer === null || flashesContainer === void 0 ? void 0 : flashesContainer.remove();
    }, 500);
}, 4000);
const searchInput = document.querySelector('.search-form input');
const mainColor = window
    .getComputedStyle(document.documentElement)
    .getPropertyValue('--main-color');
searchInput === null || searchInput === void 0 ? void 0 : searchInput.addEventListener('focus', () => {
    const searchForm = document.querySelector('.search-form');
    searchForm.style.borderColor = mainColor;
});
searchInput === null || searchInput === void 0 ? void 0 : searchInput.addEventListener('blur', () => {
    const searchForm = document.querySelector('.search-form');
    searchForm.style.borderColor = '';
});
