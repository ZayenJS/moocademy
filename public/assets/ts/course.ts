const courseCurriculumItems = Array.from(document.querySelectorAll('.course-curriculum__item'));

for (const courseCurriculumItem of courseCurriculumItems) {
  courseCurriculumItem.addEventListener('click', (event) => {
    const target = event.target as HTMLLIElement;
    const clickableHeader = target.closest('li')?.querySelector('div');
    clickableHeader?.classList.toggle('open');

    const icon = target.querySelector('i') as HTMLElement;

    if (!icon.style.transform || icon.style.transform === 'rotate(0deg)') {
      icon.style.transform = 'rotate(180deg)';
      return;
    }

    icon.style.transform = 'rotate(0deg)';
  });
}
