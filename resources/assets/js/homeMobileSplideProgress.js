function canScrollHomeProducts(splide) {
    return splide.Components.Controller.getEnd() > 0;
}

function getScrollProgress(splide) {
    const Controller = splide.Components.Controller;
    const Move = splide.Components.Move;
    const end = Controller.getEnd();

    if (end <= 0) {
        return 0;
    }

    const minPos = Move.toPosition(0, true);
    const maxPos = Move.toPosition(end, true);
    const range = maxPos - minPos;

    if (range > 0) {
        const position = Move.getPosition();
        const clamped = Math.min(maxPos, Math.max(minPos, position));
        const ratio = (clamped - minPos) / range;

        return Math.min(100, Math.max(0, ratio * 100));
    }

    const index = Math.min(Math.max(Controller.getIndex(), 0), end);

    return (index / end) * 100;
}

function updateSplideProgress(root, splide) {
    const line = root.querySelector('.home-products-slide-buttons .line');

    if (!line || !splide) {
        return;
    }

    const scrollable = canScrollHomeProducts(splide);

    line.style.display = scrollable ? 'block' : 'none';

    if (!scrollable) {
        line.style.setProperty('--home-products-progress', '0%');
        return;
    }

    const pct = getScrollProgress(splide);

    line.style.setProperty('--home-products-progress', `${pct}%`);
}

function isHomeProductsSliderRoot(root) {
    if (!root?.classList.contains('home-products-list')) {
        return false;
    }

    return Boolean(root.closest('.home-products.row, .customBlocks, .home-youtube-cards'));
}

export function bindHomeProductsSplideProgress(root, splide) {
    if (!root || !splide || window.innerWidth > 767 || !isHomeProductsSliderRoot(root)) {
        return;
    }

    const line = root.querySelector('.home-products-slide-buttons .line');
    const refresh = () => updateSplideProgress(root, splide);

    if (root._splideProgressBound !== splide) {
        root._splideProgressBound = splide;

        splide.on('mounted', () => {
            requestAnimationFrame(refresh);
        });
        splide.on('move', refresh);
        splide.on('updated', refresh);
        splide.on('drag', () => {
            if (line) {
                line.classList.add('is-dragging');
            }

            refresh();
        });
        splide.on('dragged', () => {
            if (line) {
                line.classList.remove('is-dragging');
            }

            refresh();
        });
        splide.on('scroll', refresh);
        splide.on('resized', refresh);
    }

    if (splide.root?.classList.contains('is-initialized')) {
        requestAnimationFrame(refresh);
    }
}

export function initHomeProductsSliderProgress() {
    if (window.innerWidth > 767) {
        return;
    }

    document.querySelectorAll(
        '.home-products.row .splide.home-products-list, .customBlocks .splide.home-products-list, .home-youtube-cards .splide.home-products-list'
    ).forEach((root) => {
        const block = root.closest('.home-products, .customBlocks, .home-youtube-cards');
        const splide = root.splide || block?._splideInstance;

        if (splide) {
            bindHomeProductsSplideProgress(root, splide);
        }
    });
}
