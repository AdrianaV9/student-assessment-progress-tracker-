// SCRUM-31: Responsive navigation and interface behaviour.

document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.nav-toggle');
    const navigation = document.getElementById('primary-navigation');

    if (!toggle || !navigation) {
        return;
    }

    const closeNavigation = function () {
        navigation.classList.remove('nav-open');
        toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', function () {
        const isOpen = navigation.classList.toggle('nav-open');

        toggle.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );
    });

    navigation.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 700) {
                closeNavigation();
            }
        });
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 700) {
            closeNavigation();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeNavigation();
            toggle.focus();
        }
    });
});
