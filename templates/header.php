<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<base href="/" />
<title>BOS Philly - Bringing Circuit Back to Philly</title>
<link rel="stylesheet" href="css/index.css?version=<?= asset_version('css/index.css'); ?>" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
<link rel="preconnect" href="https://tickets.bosphilly.com" crossorigin>
<!-- Preload primary web fonts to reduce font-swap CLS -->
<link rel="preload" as="font" type="font/woff2" crossorigin href="https://fonts.gstatic.com/s/teko/v23/LYjNdG7kmE0gfaN9pQlCpVo.woff2">
<link rel="preload" as="font" type="font/woff2" crossorigin href="https://fonts.gstatic.com/s/worksans/v24/QGYsz_wNahGAdqQ43Rh_fKDptfpA4Q.woff2">
<!-- Preload the LCP background image used by the charity section to improve LCP (homepage only) -->
<?php if (!query()["name"]): ?>
<link rel="preload" as="image" href="/wordpress/content-charity-parallax" />
<?php endif; ?>
<link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-75x75.png" sizes="32x32">
<link rel="icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png" sizes="192x192">
<link rel="apple-touch-icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png">
<!-- Preconnect to script CDNs used site-wide -->
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E5VXE7X7M6"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
// testing 1 2 3
gtag('config', 'G-E5VXE7X7M6');      // GA4
gtag('config', 'AW-17640299113');    // Google Ads
</script>
<!-- End Google tag (gtag.js) -->
<!-- Facebook verification -->
<meta name="facebook-domain-verification" content="ca9qnb8k0n5fkq5jfuw2unmwkxcj13" />
<!-- End Facebook verification -->
</head>
<body>
<header class="fixed">
    <div class="header-inner">
        <a href="/" id="home">
            <img id="logo" src="wordpress/wp-content/uploads/content-bos-logo.png" alt="BOS Logo" width="117" height="84">
        </a>
        <nav>
            <?php if (!isMobile()): ?>
            <ul id="navbar">
                <li><a class='nav' href="events">Events</a></li>
                <li><a class='nav' href="galleries">Galleries</a></li>
                <li><a class='nav' href="djs">DJs</a></li>
                <li><a class='nav' href="board">Board</a></li>
            </ul>
            <?php endif; ?>
            <span>
                <a class='social' href="http://facebook.com/bosphilly" target="_blank"><?= svg_icon('facebook') ?></a>
                <a class='social' href="http://instagram.com/bosphilly" target="_blank"><?= svg_icon('instagram') ?></a>
                <a class='social' href="mailto:info@bosphilly.org" target="_blank"><?= svg_icon('envelope') ?></a>
                <a class='social' href="https://soundcloud.com/bos-philly" target="_blank"><?= svg_icon('soundcloud') ?></a>
                <a class='social' id="mobileToggle"><?= svg_icon('bars') ?></a>
            </span>
        </nav>
    </div>
</header>
<?php if (isMobile()): ?>
    <ul id="navbar">
        <li><a class='nav' href="events">Events</a></li>
        <li><a class='nav' href="galleries">Galleries</a></li>
        <li><a class='nav' href="djs">DJs</a></li>
        <li><a class='nav' href="board">Board</a></li>
    </ul>
<?php endif; ?>
