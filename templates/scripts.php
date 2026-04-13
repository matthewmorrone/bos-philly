<script defer src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/pluralize/8.0.0/pluralize.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/konami@1.6.3/konami.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="wp.js?version=<?= asset_version('wp.js'); ?>"></script>
<script>
function query() {
    let [page, name] = window.location.pathname.split('/').slice(1);
    return { page, name };
}
function getQueryString() {
    return Object.fromEntries(new URLSearchParams(location.search));
}
function toTitleCase(str) {
    return str.replace(/\w\S*/g, txt => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
}
async function $get(url) {
    try { return await (await fetch(url)).text(); }
    catch(e) { console.error(e); return false; }
}

let route = query();

debug = getQueryString().debug;
if (debug) console.log(getQueryString());

const sort = (a, b) => a.localeCompare(b, 'en', {numeric: true})

function adjustParticleBackground() {
    const content = document.getElementById('content');
    const canvas = document.querySelector('#particle-background canvas');
    if (!content || !canvas) return;
    let increaseBy = content.offsetHeight - canvas.offsetHeight;
    canvas.style.width = (canvas.offsetWidth + increaseBy) + 'px';
    canvas.style.height = (canvas.offsetHeight + increaseBy) + 'px';
}

async function loadTiles() {
    function loadPages(pages, url) {
        pages = pages.map(page => ({
            name: page.post_title,
            url: `${pluralize.singular(url)}s/${page.post_name}`,
            image: page.image
        }));
        const grid = document.querySelector(`#${pluralize.plural(url)} .grid`);
        if (grid) grid.insertAdjacentHTML('beforeend', pages.map(page => {
            return `<div class="tile container no-hover">
                <a href="${page.url}">
                    <img src="${page.image}" loading="lazy" alt="${page.name}" />
                    <div class="tile-caption">${page.name}</div>
                </a>
            </div>`;
        }).join(''));
    }

    function loadGalleries(galleries) {
        if (debug) console.log("galleries: ", galleries);
        galleries = galleries.map(gallery => ({
            name: gallery.post_title,
            date: gallery.date_of_event,
            timestamp: luxon.DateTime.fromMillis(Date.parse(gallery.date_of_event)),
            url: `galleries/${gallery.post_name}`,
            image: gallery.image
        }));
        galleries = galleries.sort((a, b) => a.timestamp > b.timestamp ? -1 : a.timestamp < b.timestamp ? 1 : 0);
        const grid = document.querySelector('#galleries .grid');
        if (grid) grid.insertAdjacentHTML('beforeend', galleries.map(gallery => {
            return `<div class="tile container no-hover">
                <a href="${gallery.url}">
                    <img src="${gallery.image}" loading="lazy" alt="${gallery.name}" />
                    <div class="tile-caption">${gallery.name}</div>
                </a>
            </div>`;
        }).join(''));
    }

    const galleriesMore = document.querySelector('#galleries .more');
    if (galleriesMore) galleriesMore.addEventListener('click', async () => {
        let offset = document.querySelectorAll('#galleries .tile').length;
        let result = await getPages("gallery", offset, true);
        let galleries = result.posts;
        if (galleries.length) loadGalleries(galleries);
        galleriesMore.style.display = 'none';
    });

    const djsMore = document.querySelector('#djs .more');
    if (djsMore) djsMore.addEventListener('click', async () => {
        let offset = document.querySelectorAll('#djs .tile').length;
        let result = await getPages("dj", offset, true);
        let pages = result.posts;
        if (pages.length) loadPages(pages, "dj");
        djsMore.style.display = 'none';
    });

    const boardMore = document.querySelector('#board .more');
    if (boardMore) boardMore.addEventListener('click', () => {
        window.location.href = "about-us";
    });
}

String.prototype.replaceAt = function(index, replacement) {
    return this.substring(0, index) + replacement + this.substring(index + replacement.length);
}
String.prototype.splice = function(idx, rem, str) {
    return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
};

let hasFired = false;
let increment = 1000;
let USDollar = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});
let nums = [0,1,2,3,4,5,6,7,8,9];
window.addEventListener('scroll', () => {
    function elementScrolled(elem) {
        try {
            let el = document.querySelector(elem);
            if (!el) return false;
            let rect = el.getBoundingClientRect();
            return rect.top <= window.innerHeight && rect.top >= 0;
        }
        catch (e) {
            return false;
        }
    }
    if (elementScrolled('#charity')) {
        if (hasFired) return;
        const counter = document.querySelector('.counter');
        if (!counter) return;
        let start = Math.round(parseFloat(counter.getAttribute('start')), 2);
        let end = Math.round(parseFloat(counter.getAttribute('end')), 2);
        counter.textContent = start;

        let flutter = 0;
        let interval = setInterval(() => {
            let value = Math.round(parseFloat(counter.getAttribute('current')), 2);
            if (value + increment <= end) {
                value += increment;
                counter.setAttribute('current', Math.round(value, 2));
                let text = counter.getAttribute('current');
                text = USDollar.format(text).slice(1);
                for(let i = flutter; i < text.length; i++) {
                    if (text[i].match(/\d/) !== null) {
                        text = text.replaceAt(i, ""+nums[Math.floor(Math.random()*10)])
                    }
                }
                counter.textContent = "$"+text;
            }
            else if (increment > .01) {
                increment /= 10;
                flutter++;
            }
            else {
                clearInterval(interval);
                counter.textContent = USDollar.format(end);
            }
        }, 30);
        hasFired = true;
    }
});
// Trigger scroll handler once on load in case charity section is already visible
window.dispatchEvent(new Event('scroll'));

// parallax image movement
let currentZoom = 1;
let minZoom = 1;
let maxZoom = 2;
let stepSize = 0.005;
let deviceWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
let mobileScrollDirection = 1;

function zoomImage(direction) {
    let newZoom = currentZoom + direction * stepSize;
    if (newZoom < minZoom || newZoom > maxZoom) return;
    currentZoom = newZoom;
    let image = document.querySelector('#parallax');
    if (image) image.style.transform = 'scale(' + currentZoom + ')';
}

function parallax(event) {
    let direction = event.deltaY > 0 ? 1 : -1;
    if (deviceWidth <= 600) direction = mobileScrollDirection;
    zoomImage(direction);
}

window.addEventListener('touchstart', function(e) {
    start = e.changedTouches[0];
});

window.addEventListener('touchmove', function(e) {
    let end = e.changedTouches[0];
    mobileScrollDirection = end.screenY - start.screenY > 0 ? -1 : 1
});

['wheel', 'scroll', 'touchmove']
    .forEach(event => document.querySelector('body').addEventListener(event, parallax, false));

function scrollTo(element, to, duration) {
    const start = element.scrollTop;
    const change = to - start;
    const increment = 20;
    let currentTime = 0;

    const animateScroll = function() {
        currentTime += increment;
        const val = Math.easeInOutQuad(currentTime, start, change, duration);
        element.scrollTop = val;
        if (currentTime < duration) {
            setTimeout(animateScroll, increment);
        }
    };
    animateScroll();
}

Math.easeInOutQuad = function(t, b, c, d) {
    t /= d / 2;
    if (t < 1) return c / 2 * t * t + b;
    t--;
    return -c / 2 * (t * (t - 2) - 1) + b;
};

function scrollToSection(section, offset) {
    scrollUpdatePaused = true;
    window.history.pushState({}, null, `${window.location.origin}/${section}${window.location.search}`);
    const target = document.querySelector(`#${section}`);
    if (!target) return;
    const headerEl = document.querySelector('header');
    const offsetTop = target.offsetTop - (headerEl ? headerEl.offsetHeight : 0) + offset;
    scrollTo(document.documentElement, offsetTop, 500);
    document.title = `BOS Philly :: ${toTitleCase(section)}`;
    setTimeout(() => { scrollUpdatePaused = false; }, 600);
}

const sectionIds = ["events", "galleries", "djs", "board"];
let scrollUpdatePaused = false;
const visibleSections = new Set();

const sectionObserver = new IntersectionObserver((entries) => {
    if (scrollUpdatePaused) return;
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            visibleSections.add(entry.target.id);
        } else {
            visibleSections.delete(entry.target.id);
        }
    });
    const visible = sectionIds.filter(id => visibleSections.has(id));
    if (visible.length) {
        const section = visible[0];
        window.history.replaceState({}, null, `${window.location.origin}/${section}${window.location.search}`);
        document.title = `BOS Philly :: ${toTitleCase(section)}`;
    } else {
        window.history.replaceState({}, null, `${window.location.origin}/${window.location.search}`);
        document.title = 'BOS Philly';
    }
}, { threshold: 0.1 });

document.addEventListener('DOMContentLoaded', () => {
    if (!route.name) {
        sectionIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) sectionObserver.observe(el);
        });
    }
});

function isHomeSectionRoute(route) {
    if (!route || !route.page) return false;
    const sectionRoutes = new Set(["events", "galleries", "djs", "board", "charity", "pandering", "splash"]);
    return sectionRoutes.has(route.page);
}

function scrollToRouteSection(route) {
    if (!isHomeSectionRoute(route)) return;
    const target = document.querySelector(`#${route.page}`);
    if (!target) return;
    // Defer to ensure layout/header height is stable (esp. on cold loads).
    requestAnimationFrame(() => {
        requestAnimationFrame(() => scrollToSection(route.page, 0));
    });
}

document.querySelectorAll('.nav').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        let section = this.getAttribute('href');
        const isSection = isHomeSectionRoute({ page: section });
        const onHomepage = !route.name;
        console.log('[nav click]', { section, isSection, onHomepage, route, href: this.href });
        if (!isSection || !onHomepage) {
            console.log('[nav] allowing default navigation to:', section);
            return;
        }
        console.log('[nav] intercepting for scroll to section:', section);
        e.preventDefault();
        scrollToSection(section, 0);
    });
});

document.addEventListener('DOMContentLoaded', async () => {
    const splashVideo = document.querySelector('#splash video');
    if (splashVideo) {
        window.addEventListener('resize', () => {
            const splash = document.getElementById('splash');
            if (splash) splashVideo.style.width = splash.offsetWidth + 'px';
        });
        splashVideo.style.width = document.getElementById('splash')?.offsetWidth + 'px';
    }

    const isMobile = window.matchMedia("(width < 600px)").matches;
    if (isMobile) document.querySelectorAll('.overlay').forEach(el => el.remove());

    const mobileToggle = document.getElementById('mobileToggle');
    if (mobileToggle) mobileToggle.addEventListener('click', () => {
        const navbar = document.getElementById('navbar');
        if (navbar) navbar.classList.toggle('open');
    });

    let route = query();
    if (route.page === "about-us") {
        document.querySelectorAll('#splash, #charity, #pandering, #events, #galleries, #djs').forEach(el => el.remove());
        document.querySelectorAll('#board .button-container button, #board h1, #board #separator').forEach(el => el.remove());
        let resp = await fetch('board.html');
        let page = await resp.text();
        const board = document.getElementById('board');
        if (board) board.insertAdjacentHTML('afterbegin', page);
    }
    if (route.page === "social-media") {
        document.querySelectorAll('#splash, #charity, #pandering, #events, #galleries, #djs, #board').forEach(el => el.remove());
        let resp = await fetch('social.html');
        let page = await resp.text();
        const header = document.querySelector('header');
        if (header) header.insertAdjacentHTML('afterend', page);
    }
    else {
        loadTiles();
        scrollToRouteSection(route);
    }

    const calendarBtn = document.getElementById('calendar');
    if (calendarBtn) calendarBtn.addEventListener('click', function() {
        Swal.fire({
            title: "<strong>Live Calendar</strong>",
            icon: "info",
            html: `Stay up to date with our latest events by subscribing to our live calendar. Just click below and it will open in your default calendar app.`,
            confirmButtonText: `
<a href="webcal://calendar.google.com/calendar/ical/c_e5ccfcf9265560b5a19219e3e0cc2047926d5adb287c163e59322c00137ec065%40group.calendar.google.com/public/basic.ics">Subscribe</a>
  `,
            iconHtml: `<?= svg_icon('calendar-days') ?>`,
            showCloseButton: true,
            showCancelButton: true,
            iconColor: "#ed208b",
            confirmButtonColor: "#ed208b",
        });
    });

    const easterEgg = new Konami(() => {
        document.body.classList.toggle('konami');
    });

    if (typeof GLightbox !== 'undefined') GLightbox();
});

// Support back/forward navigation between section routes.
window.addEventListener("popstate", () => {
    scrollToRouteSection(query());
});

// Smooth scroll to top function for blowout pages - defined globally
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Show/hide floating back to top button based on scroll position
window.addEventListener('scroll', function() {
    const scrollTop = window.scrollY;
    const backToTopButton = document.querySelector('.floating-back-to-top');
    if (!backToTopButton) return;
    if (scrollTop > 300) {
        backToTopButton.classList.add('show');
    } else {
        backToTopButton.classList.remove('show');
    }
});
</script>
</body>
</html>
