<script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
<script src="utils.js" defer></script>
<script src="wp.js" defer></script>
<script type="module">
  // Example: lightbox only when a gallery image is clicked
  document.addEventListener('click', async (e)=>{
    const a = e.target.closest('a');
    if(!a) return;
    if (a.matches('[data-lightbox]')) {
      e.preventDefault();
      if (!window.lightbox) {
        try {
          await Promise.all([
            import('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js'),
            new Promise((res, rej) => {
              const l = document.createElement('link');
              l.rel = 'stylesheet';
              l.href = 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css';
              l.onload = res;
              l.onerror = () => rej(new Error('Failed to load lightbox stylesheet.'));
              document.head.appendChild(l);
            })
          ]);
        } catch (err) {
          if (window.Swal) {
            Swal.fire({
              icon: 'error',
              title: 'Lightbox failed to load',
              text: err.message || 'Please check your connection and try again.',
            });
          } else {
            alert('Lightbox failed to load: ' + (err.message || 'Please check your connection and try again.'));
          }
          return;
        }
      }
      lightbox.start($(a)[0]);
    }
  });

  // Defer non-critical libs until idle
  const deferLib = (src)=> new Promise((res)=>{
    const s=document.createElement('script'); s.src=src; s.defer=true; s.onload=res; document.head.appendChild(s);
  });
  requestIdleCallback?.(()=>{
    deferLib('https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js');
    deferLib('https://cdnjs.cloudflare.com/ajax/libs/pluralize/8.0.0/pluralize.min.js');
    deferLib('https://cdn.jsdelivr.net/npm/konami@1.6.3/konami.min.js');
  });

  // Lazy-load hero video when idle and in view, with IntersectionObserver polyfill for legacy browsers
  const startHeroVideo = () => {
    const v = document.getElementById('heroVideo');
    if (v && !navigator.connection?.saveData) {
      const observeVideo = () => {
        const io = new IntersectionObserver(([e]) => {
          if (e.isIntersecting) {
            v.load();
            v.play().catch(() => {});
            io.disconnect();
          }
        });
        io.observe(v);
      };
      if ('IntersectionObserver' in window) {
        observeVideo();
      } else {
        // Load IntersectionObserver polyfill for legacy browsers
        const polyfillScript = document.createElement('script');
        polyfillScript.src = 'https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver';
        polyfillScript.onload = observeVideo;
        document.head.appendChild(polyfillScript);
      }
    }
  };
  if (window.requestIdleCallback) {
    requestIdleCallback(startHeroVideo);
  } else {
    startHeroVideo();
  }
</script>
<script>
if (typeof query !== 'function') {
    function query() {
        let qs = {}, slice = 1;
        let [page, name] = [...window.location.pathname.split("/").slice(slice)];
        qs.page = page;
        qs.name = name;
        return qs;
    }
}
let route = query();

debug = getQueryString().debug;
if (debug) console.log(getQueryString());

const sort = (a, b) => a.localeCompare(b, 'en', {numeric: true})

function adjustParticleBackground() {
    let increaseBy = $("#content").height() - $("#particle-background canvas").height();
    $("#particle-background canvas").css({
        width: $("#particle-background canvas").width()+increaseBy,
        height: $("#particle-background canvas").height()+increaseBy
    });
}

async function loadTiles() {
    function loadPages(pages, url) {
        pages = pages.map(page => {
            return {
                name: page.post_title,
                url: `${pluralize.singular(url)}s/${page.post_name}`,
                image: page.image
            }
        });
        const isMobile = window.matchMedia("(width < 600px)").matches;
        $(`#${pluralize.plural(url)} .grid`).append(pages.map(page => {
            return `<div class="tile container">
                <a href="${page.url}">
                <img src="${page.image}" class="hover" loading="lazy" />
                ${isMobile
                    ? `<div class='label'>${page.name}</div>`
                    : `<div class="overlay"><div class="hover-text">${page.name}</div></div>`}
                </a>
            </div>`;
        }));
    }

    function loadGalleries(galleries) {
        if (debug) console.log("galleries: ", galleries);
        galleries = galleries.map(gallery => {
            return {
                name: gallery.post_title,
                date: gallery.date_of_event,
                timestamp: luxon.DateTime.fromMillis(Date.parse(gallery.date_of_event)),
                url: `galleries/${gallery.post_name}`,
                image: gallery.image
            }
        });
        galleries = galleries.sort(function(a, b) {
            return a.timestamp > b.timestamp ? -1 : a.timestamp < b.timestamp ? 1 : 0;
        });
        const isMobile = window.matchMedia("(width < 600px)").matches;
        $("#galleries .grid").append(galleries.map(gallery => {
            return `<div class="tile container">
                <a href="${gallery.url}">
                    <img src="${gallery.image}" class="hover" loading="lazy" />
                    ${isMobile
                        ? `<div class='label'>${gallery.name}</div>`
                        : `<div class="overlay"><div class="hover-text">${gallery.name}</div></div>`}
                </a>
            </div>`;
        }));
    }

    $("#galleries .more").click(async () => {
        let offset = $("#galleries .tile").length;
        let result = await getPages("gallery", offset, true);
        let galleries = result.posts;
        if (galleries.length) loadGalleries(galleries);
        $("#galleries .more").hide();
    });

    $(`#djs .more`).click(async () => {
        let offset = $(`#djs .tile`).length;
        let result = await getPages("dj", offset, true);
        let pages = result.posts;
        if (pages.length) loadPages(pages, "dj");
        $(`#djs .more`).hide();
    });

    $(`#board .more`).click(async () => {
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
$(window).scroll(() => {
    function elementScrolled(elem) {
        try {
            let docViewTop = $(window).scrollTop();
            let docViewBottom = docViewTop + $(window).height();
            let elemTop = $(elem).offset().top;
            return ((elemTop <= docViewBottom) && (elemTop >= docViewTop));
        }
        catch (e) {
            return false;
        }
    }
    if (elementScrolled('#charity')) {
        if (hasFired) return;
        let start = Math.round(parseFloat($(".counter").attr("start")), 2);
        let end = Math.round(parseFloat($(".counter").attr("end")), 2);
        $(".counter").text(start);

        let flutter = 0;
        let interval = setInterval(() => {
            let value = Math.round(parseFloat($(".counter").attr("current")), 2);
            if (value + increment <= end) {
                value += increment;
                $(".counter").attr("current", Math.round(value, 2));
                let text = $(".counter").attr("current");
                text = USDollar.format(text).slice(1);
                for(let i = flutter; i < text.length; i++) {
                    if (text[i].match(/\d/) !== null) {
                        text = text.replaceAt(i, ""+nums[Math.floor(Math.random()*10)])
                    }
                }
                $(".counter").text("$"+text);
            }
            else if (increment > .01) {
                increment /= 10;
                flutter++;
            }
            else {
                clearInterval(interval);
                $(".counter").text(USDollar.format(end))
            }
        }, 30);
        hasFired = true;
    }
}).scroll();

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
    window.history.pushState({}, null, `${window.location.origin}/${section}${window.location.search}`);
    const target = document.querySelector(`#${section}`);
    if (!target) return
    const offsetTop = target.offsetTop - $("header").height() + offset;
    scrollTo(document.documentElement, offsetTop, 500);
    $("title").text(`${section.toTitleCase()} - BOS Philly`);
}

document.querySelectorAll('.nav').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        let section = this.getAttribute('href');
        scrollToSection(section, 0);
    });
});

$(async () => {
    $.ajaxSetup({cache: false});
    $(window).on("resize", () => $("#splash video").width($("#splash").width())).resize();

    const isMobile = window.matchMedia("(width < 600px)").matches;
    if (isMobile) $(".overlay").remove()

    $("#mobileToggle").click(() => {
        $("#navbar").slideToggle();
    });

    let route = query();
    if (route.page === "about-us") {
        $("#splash, #charity, #pandering, #events, #galleries, #djs").remove();
        $("#board .button-container button, #board h1, #board #separator").remove();
        let page = await $get("board.html");
        $("#board").prepend(page);
    }
    else if (route.page) {
        console.log(route);
    }
    else {
        loadTiles();
    }

    $('#calendar').click(function() {
        Swal.fire({
            title: "<strong>Live Calendar</strong>",
            icon: "info",
            html: `Stay up to date with our latest events by subscribing to our live calendar. Just click below and it will open in your default calendar app.`,
            confirmButtonText: `
<a href="webcal://calendar.google.com/calendar/ical/c_e5ccfcf9265560b5a19219e3e0cc2047926d5adb287c163e59322c00137ec065%40group.calendar.google.com/public/basic.ics">Subscribe</a>
  `,
            iconHtml: `<?= icon('calendar-days') ?>`,
            showCloseButton: true,
            showCancelButton: true,
            iconColor: "#ed208b",
            confirmButtonColor: "#ed208b",
        });
    });

    const easterEgg = new Konami(() => {
        $(document.body).toggleClass("konami")
    });
});

// Smooth scroll to top function for blowout pages - defined globally
function scrollToTop() {
    $('html, body').animate({
        scrollTop: 0
    }, 600);
}

// Show/hide floating back to top button based on scroll position
$(window).scroll(function() {
    const scrollTop = $(this).scrollTop();
    const backToTopButton = $('.floating-back-to-top');
    
    if (scrollTop > 300) {
        backToTopButton.addClass('show');
    } else {
        backToTopButton.removeClass('show');
    }
});
</script>
</body>
</html>
