"use strict";
const courseCurriculumItems = Array.from(document.querySelectorAll('.course-curriculum__item'));
for (const courseCurriculumItem of courseCurriculumItems) {
    courseCurriculumItem.addEventListener('click', (event) => {
        var _a;
        const target = event.target;
        const clickableHeader = (_a = target.closest('li')) === null || _a === void 0 ? void 0 : _a.querySelector('div');
        clickableHeader === null || clickableHeader === void 0 ? void 0 : clickableHeader.classList.toggle('open');
        const icon = target.querySelector('i');
        if (!icon.style.transform || icon.style.transform === 'rotate(0deg)') {
            icon.style.transform = 'rotate(180deg)';
            return;
        }
        icon.style.transform = 'rotate(0deg)';
    });
}
