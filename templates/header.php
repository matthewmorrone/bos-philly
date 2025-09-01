<?php include_once __DIR__ . '/../template-parts/icon.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Core meta -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="description" content="BOS Philly throws world-class circuit parties in Philadelphia while supporting local LGBTQ+ charities. See upcoming events, galleries, and DJs.">
<link rel="canonical" href="https://bosphilly.org/">

<!-- Preconnects -->
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">

<!-- Fonts: use display=swap to prevent layout shift; keep families minimal -->
<link href="https://fonts.googleapis.com/css2?family=Teko:wght@600;700&family=Work+Sans:wght@400;600&display=swap" rel="stylesheet">

<!-- Critical CSS (very small): ensures above-the-fold has stable layout) -->
<style>
  :root{--header-h:64px}
  html{scroll-behavior:smooth}
  body{margin:0}
  header.fixed{position:sticky;top:0;height:var(--header-h);backdrop-filter:saturate(120%) blur(6px)}
  /* Reserve space for hero to avoid CLS */
  #splash{position:relative;min-height:clamp(320px, 55vh, 720px);overflow:hidden}
  #splash .splash-background{position:absolute;inset:0;display:grid;place-items:center}
  #splash .splash-title{position:relative;z-index:2;text-align:center;padding-top:calc(var(--header-h) + 2rem)}
  /* Image defaults with intrinsic ratio to prevent reflow */
  img{max-width:100%;height:auto}
  .tile img{aspect-ratio:4/5;object-fit:cover}
  #galleries .tile img{aspect-ratio:1/1}
</style>

<title>BOS Philly - Bringing Circuit Back to Philly</title>
<base href="/" />
<link rel="stylesheet" href="css/index.css?version=<?= randomId(4); ?>" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-75x75.png" sizes="32x32">
<link rel="icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png" sizes="192x192">
<link rel="apple-touch-icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png">
<script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E5VXE7X7M6"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-E5VXE7X7M6');
</script>
<!-- End Google tag (gtag.js) -->
<!-- Facebook verification -->
<meta name="facebook-domain-verification" content="ca9qnb8k0n5fkq5jfuw2unmwkxcj13" />
<!-- End Facebook verification -->
</head>
<body>
<?php include __DIR__ . '/../images/icons.svg'; ?>
<header class="fixed">
    <div class="header-inner">
        <a href="/" id="home" aria-label="BOS Philly home">
            <img id="logo" src="wordpress/wp-content/uploads/content-bos-logo.png" alt="BOS Logo" decoding="async">
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
                <a class='social' href="http://facebook.com/bosphilly" target="_blank"><?= icon('facebook') ?></a>
                <a class='social' href="http://instagram.com/bosphilly" target="_blank"><?= icon('instagram') ?></a>
                <a class='social' href="mailto:info@bosphilly.org" target="_blank"><?= icon('envelope') ?></a>
                <a class='social' href="https://soundcloud.com/bos-philly" target="_blank"><?= icon('soundcloud') ?></a>
                <a class='social' id="mobileToggle"><?= icon('bars') ?></a>
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
