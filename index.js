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

async function loadPage(page, name) {
    if (!page || !name) return;
    let result, data = {};

    if (page === "events") {
        data = await getPageByName("event", name);
        data = data.posts[0];
        if (debug) console.log("data: ", data);

        let event_template = `
        <div class='event-template'>
            <link rel="stylesheet" href="css/event.css" />
            <div class='shade'>
                <div class='title'>
                    <h1>${data.post_title}</h1>
                </div>
                <div class='images'>
                    <div>
                        ${data.primary_dj
                            ? `<a href="djs/${data.primary_dj.post_name}">
                                <img src='${data.primary_dj.post_image}' class='feature' />
                                <h2>${data.primary_dj.post_title} »</h2>
                            </a>`
                            : '<h2>DJ To Be Announced...</h2>'}
                    </div>
                    <div>
                        <img src='${data.image}' class='feature' />
                        <div class='description'>
                            ${data.post_content}
                        </div>
                    </div>
                </div>
                <div class='info'>
                    <div class='ticket-panel'>
                        <div class='marker'><i class='fas fa-ticket'></i></div>
                        <div class='panel'>
                            <h3>Tickets</h3>
                        </div>
                        <div class='ticket-button'>
                            ${data.ticket_link
                                ? `<a href='${data.ticket_link}'><button>Tickets</button></a>`
                                : '<button>Coming Soon</button>'}
                        </div>
                    </div>
                    <div>
                        <div class='marker'><i class='far fa-clock'></i></div>
                        <div class='panel'>
                            <h3>Time</h3>
                            <p>${data.date_of_event}</p>
                            <p>${data.start_time} - ${data.end_time}</p>
                        </div>
                    </div>
                    <div>
                        <div class='marker'><i class="fas fa-map-marker-alt"></i></div>
                        <div class='panel'>
                            <h3>Location</h3>
                            <h4>${data.venue_name}</h4>
                            <p><a href='http://maps.google.com/?q="${data.venue_address}"'>${data.venue_address}</a></p>
                        </div>
                    </div>
                </div>
                <div class='map-embed'>
                    <iframe
                    title="${data.venue_address}"
                    class='map'
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCFs6ozWaQYHbXaQdd7EaRlJQDrioFxhdg
                        &q=${data.venue_address}"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="button-container">
                    <button class="more"><a href="events">More Events</a></button>
                </div>
            </div>
        </div>
        `;
        $("#content").html(event_template);
        $("#content .event-template").css("background-image", `url('${data.background_image}')`);
        $("title").text(`${data.post_title} - BOS Philly`);
    }
    if (page === "galleries") {
        data = await getPageByName("gallery", name);
        data = data.posts[0];
        if (debug) console.log("data: ", data);

        let gallery_template = `
            <div class='gallery-template'>
                <link rel="stylesheet" href="css/gallery.css" />
                <div class='gallery-content'>
                    <h1>${data.post_title}</h1>
                </div>
                <div class='photo-gallery'>
                ${(function f() {
                    let result = "";
                    for(let i = 0; i < data.images.length; i++) {
                        result += `<div class='photo'>
                            <a href='${data.images[i].large}' data-lightbox='${data.post_name}'>
                                <img src='${data.images[i].medium}' loading="lazy" />
                            </a>
                        </div>`;
                    }
                    return result;
                })()}
                </div>
                <div class="button-container">
                    <button class="more"><a href="galleries">More Galleries</a></button>
                </div>
            </div>
        `;
        $("#content").html(gallery_template);
        $("title").text(`${data.post_title} - BOS Philly`);
    }
    if (page === "models") {
        data = await getPageByName("model", name);
        data = data.posts[0];

        data.photos = data.photos?.map(photo => {
            return {
                small: photo.media_details.sizes.thumbnail?.source_url,
                medium: photo.media_details.sizes.medium?.source_url,
                large: photo.media_details.sizes.large?.source_url,
                full: photo.full_image_url
            }
        }) || [];
        if (debug) console.log("data: ", data);

        let model_template = `
        <div class='model-template'>
            <link rel="stylesheet" href="css/model.css" />
            <div id="particle-background"></div>
            <div class='model-content'>
                <div class='model-image'>
                    <img src='${data.image}' class='featured' />
                </div>
                <div class='model-description'>
                    <h1>${data.post_title}</h1>
                    <h3>Height: ${data.height}</h3>
                    <h3>Weight: ${data.weight}</h3>
                    <p>${data.post_content}</p>
                    ${data.instagram_link ? `<button class='instagram'>
                        <a href="https://www.instagram.com/${data.instagram_link}/" target="_blank"><i class="fab fa-instagram"></i> ${data.instagram_link.split("/").slice(-1)[0]}</a>
                    </button>` : ''}
                </div>
                ${data.photos ? `<div class='gallery'>
                    ${data.photos.map(img => `
                    <a href='${img.large}' data-lightbox='${data.post_name}'>
                        <img src='${img.small}' data-lightbox='${data.post_name}' loading="lazy" />
                    </a>
                    `).join("")}
                </div>` : ''}
            </div>
            <div class="button-container">
                <button class="more"><a href="models">More Models</a></button>
            </div>
        </div>
        `;
        $("#content").html(model_template);
        $("title").text(`${data.post_title} - BOS Philly`);
        particlesJS.load("particle-background", "css/model-particles.json");
    }
    if (page === "djs") {
        data = await getPageByName("dj", name);
        data = data.posts[0];

        data.photos = data.photos?.map(photo => {
            return {
                small: photo.media_details.sizes.thumbnail?.source_url,
                medium: photo.media_details.sizes.medium?.source_url,
                large: photo.media_details.sizes.large?.source_url,
                full: photo.full_image_url
            }
        }) || [];
        data.logo = data.logo?.url;
        if (debug) console.log("data: ", data);

        let dj_template = `
        <div class='dj-template'>
            <link rel="stylesheet" href="css/dj.css" />
            ${data.logo ? `<div class='banner'>
                <img src='${data.logo}' class='logo' />
            </div>` : ''}
            <div id="particle-background"></div>
            <div class='dj-content'>
                <div class='dj-header'>
                    <h2>${data.post_title}</h2>
                    <h3>${data.hometown}</h3>
                </div>
                <div class='dj-left'>
                    <img src='${data.image}' class='featured' />
                    ${data.photos ? `<div class='gallery'>
                        ${data.photos.map(img => `
                        <a href='${img.large}' data-lightbox='${data.post_name}'>
                            <img src='${img.small}' data-lightbox='${data.post_name}' loading="lazy" />
                        </a>
                        `).join("")}
                    </div>` : ''}
                </div>
                <div class='dj-right'>
                    <div class='description'>${data.post_content}</div>
                    ${data.instagram_link ? `<button class='instagram'>
                        <a href="${data.instagram_link}" target="_blank"><i class="fab fa-instagram"></i> ${data.instagram_link.split("/").slice(-2)[0]}</a>
                    </button>` : ''}
                    ${data.soundcloud_link ? `<button class='soundcloud'>
                        <a href="${data.soundcloud_link}"><i class="fa-brands fa-soundcloud"></i> ${data.post_title}</a>
                    </button>` : ''}
                </div>
            </div>
            <div class="button-container">
                <button class="more"><a href="djs">More DJs</a></button>
            </div>
        </div>
        `;
        $("#content").html(dj_template);

        let soundcloud = await $.ajax({
            url: 'wp.php',
            type: 'POST',
            dataType: "text",
            data: {
                action: "soundcloud",
                url: $(".soundcloud a").attr("href")
            }
        });
        if (debug) console.log(soundcloud);
        if (soundcloud) {
            $(".soundcloud").replaceWith(soundcloud);
        }

        $("title").text(`${data.post_title} - BOS Philly`);

        // $("#particle-background canvas").height($("#content").height())
        particlesJS.load("particle-background", "css/dj-particles.json");
    }
    $(".more").click(function(e) {
        e.preventDefault();
        window.location.href = $(this).find("a").attr("href");
    });
}

async function loadTiles() {
    let result;
    if ($("#events .tile").length < 1) {
        result = await getPages("event");
        if (result.post_count <= result.post_limit) {
            $("#events .more").hide();
        }
        else {
            $("#events .more").attr("post-count", result.post_count);
            $("#events .more").attr("post-limit", result.post_limit);
        }

        let events = result.posts;
        if (events.length) {
            if (debug) console.log("events: ", events);
            events = events.map(event => {
                return {
                    name: event.post_title,
                    date: event.date_of_event,
                    timestamp: luxon.DateTime.fromMillis(Date.parse(event.date_of_event)),
                    url: `events/${event.post_name}`,
                    image: event.image
                }
            });
            events = events.sort(function(a, b) {
                return a.timestamp > b.timestamp ? 1 : a.timestamp < b.timestamp ? -1 : 0;
            });
            $("#events .grid").append(events.map(event => {
                return `<div class="tile container">
                    <a href="${event.url}">
                        <img src="${event.image}" loading="lazy" />
                        <h3>${event.name}</h3>
                        <h4>${event.date}</h4>
                        <button class='tickets'>Tickets</button>
                    </a>
                </div>`;
            }));
        }
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
        $("#galleries .grid").append(galleries.map(gallery => {
            return `<div class="tile container">
                <a href="${gallery.url}">
                    <img src="${gallery.image}" class="hover" loading="lazy" />
                    <div class="overlay"><div class="hover-text">${gallery.name}</div></div>
                </a>
            </div>`;
        }));
    }

    if ($("#galleries .tile").length < 1) {
        result = await getPages("gallery", 12);
        if (result.post_count <= result.post_limit) {
            $("#galleries .more").hide();
        }
        else {
            $("#galleries .more").attr("post-count", result.post_count);
            $("#galleries .more").attr("post-limit", result.post_limit);
        }
        let galleries = result.posts;
        if (galleries.length) loadGalleries(galleries);
        
    
        $("#galleries .more").click(async function() {
            let offset = $("#galleries .more").attr("post-limit");
            let result = await getPages("gallery", offset, true);
            let galleries = result.posts;
            if (galleries.length) loadGalleries(galleries);
            $("#galleries .more").hide();
        });
    }


    async function tileLoader(url, limit) {

        function loadPages(pages, url) {
            pages = pages.map(page => {
                return {
                    name: page.post_title,
                    url: `${pluralize.plural(url)}/${page.post_name}`,
                    image: page.image || ''
                }
            });
            $(`#${pluralize.plural(url)} .grid`).append(pages.map(page => {
                return `<div class="tile container">
                    <a href="${page.url}">
                        <img src="${page.image}" class="hover" loading="lazy" />
                        <div class="overlay"><div class="hover-text">${page.name}</div></div>
                    </a>
                </div>`;
            }));
        }

        let result = await getPages(url, limit);
        if (result.post_count <= result.post_limit) {
            $(`#${pluralize.plural(url)} .more`).hide();
        }
        else {
            $(`#${pluralize.plural(url)} .more`).attr("post-count", result.post_count);
            $(`#${pluralize.plural(url)} .more`).attr("post-limit", result.post_limit);
        }
        let pages = result.posts;
        if (pages.length) {
            if (debug) console.log(`${pluralize.plural(url)}: `, pages);
            loadPages(pages, url);
        }

        $(`#${pluralize.plural(url)} .more`).click(async function() {
            let offset = $(`#${pluralize.plural(url)} .more`).attr("post-limit");
            let result = await getPages(url, offset, true);
            let pages = result.posts;
            if (pages.length) loadPages(pages, url);
            $(`#${pluralize.plural(url)} .more`).hide();
        });
    }
    if ($("#models .tile").length < 1) {
        await tileLoader("model", 6);
    }
    if ($("#djs .tile").length < 1) {
        await tileLoader("dj", 4);
    }

}


let hasFired = false;
let increment = 1000;
let USDollar = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

$(window).scroll(function () {
    function elementScrolled(elem) {
        let docViewTop = $(window).scrollTop();
        let docViewBottom = docViewTop + $(window).height();
        let elemTop = $(elem).offset().top;
        return ((elemTop <= docViewBottom) && (elemTop >= docViewTop));
    }
    if (elementScrolled('#charity')) {
        if (hasFired) return;
        let start = Math.round(parseFloat($(".counter").attr("start")), 2);
        let end = Math.round(parseFloat($(".counter").attr("end")), 2);
        $(".counter").text(start);

        let interval = setInterval(function () {
            let value = Math.round(parseFloat($(".counter").attr("current")), 2);
            if (value + increment <= end) {
                value += increment;
                $(".counter").attr("current", Math.round(value, 2));
                $(".counter").text(USDollar.format($(".counter").attr("current")))
            }
            else if (increment > .001) {
                increment /= 10;
            }
            else {
                clearInterval(interval);
            }
        }, 15);
        hasFired = true;
    }
});


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
    image.style.transform = 'scale(' + currentZoom + ')';
}
function parallax(event) {
    let direction = event.deltaY > 0 ? 1 : -1;
    if (deviceWidth <= 600) direction = mobileScrollDirection;
    zoomImage(direction);
}
window.addEventListener('touchstart', function (e) {
    start = e.changedTouches[0];
});
window.addEventListener('touchmove', function (e) {
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
    const offsetTop = target.offsetTop - $("header").height();
    scrollTo(document.documentElement, offsetTop, 500);
    $("title").text(`${section.toTitleCase()} - BOS Philly`);
}
document.querySelectorAll('.nav').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        let section = this.getAttribute('href');
        router(section, "");
    });
});


async function router(page, name) {
    if (page && name) {
        $("#splash, #charity, #events, #galleries, #models, #djs, #board").hide();
        await loadPage(page, name);
        $("#content").show();
    }
    else if (page) {
        await loadTiles();
        $("#splash, #charity, #events, #galleries, #models, #djs, #board").show();
        $("#content").hide();
        scrollToSection(page);
    }
    else {
        await loadTiles();
        $("#splash, #charity, #events, #galleries, #models, #djs, #board").show();
        $("#content").hide();
    }
}

$(async () => {
    $.ajaxSetup({cache: false});
    $("#splash video").width($("#splash").width());
    // $(".splash-title").css("margin-top", -($("#splash").height()-$(".splash-title").height())/2)

    // hide and show the menu on mobile
    $("#mobileToggle").click(() => $("nav ul").slideToggle());

    let route = query();
    router(route.page, route.name);

    const easterEgg = new Konami(() => {
        $(document.body).toggleClass("konami")
    });
});