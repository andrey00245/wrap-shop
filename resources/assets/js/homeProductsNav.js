function updateHomeProductsNavTrack(navWrap) {
    const nav = navWrap.querySelector('.home-products-nav');
    const bar = navWrap.querySelector('.home-products-nav-track__bar');
    const active = nav?.querySelector('.item.active');

    if (!nav || !bar || !active) {
        return;
    }

    const wrapRect = navWrap.getBoundingClientRect();
    const activeRect = active.getBoundingClientRect();

    bar.style.width = `${activeRect.width}px`;
    bar.style.transform = `translateX(${activeRect.left - wrapRect.left}px)`;
}

function wrapHomeProductsNav(nav) {
    if (!nav || nav.closest('.home-products-nav-wrap')) {
        return;
    }

    const navWrap = document.createElement('div');
    navWrap.className = 'home-products-nav-wrap';

    const track = document.createElement('div');
    track.className = 'home-products-nav-track';
    track.innerHTML = '<span class="home-products-nav-track__bar" aria-hidden="true"></span>';

    nav.parentNode.insertBefore(navWrap, nav);
    navWrap.appendChild(nav);
    navWrap.appendChild(track);

    const refresh = () => updateHomeProductsNavTrack(navWrap);

    nav.addEventListener('scroll', refresh, { passive: true });
    window.addEventListener('resize', refresh);
    document.addEventListener('click', (event) => {
        if (nav.contains(event.target.closest('.item'))) {
            window.requestAnimationFrame(refresh);
        }
    });

    refresh();
}

export function initHomeProductsNavTracks() {
    if (window.innerWidth > 768) {
        return;
    }

    document.querySelectorAll('.home-products-nav').forEach(wrapHomeProductsNav);
}
