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
                <a class='social' href="http://facebook.com/bosphilly" target="_blank" aria-label="BOS Philly on Facebook">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" role="img" focusable="false"><path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647Z"/></svg>
                </a>
                <a class='social' href="http://instagram.com/bosphilly" target="_blank" aria-label="BOS Philly on Instagram">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" role="img" focusable="false"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.9.2 2.3.4.6.2 1 .5 1.5 1 .5.5.8.9 1 1.5.2.4.3 1.1.4 2.3.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.9-.4 2.3-.2.6-.5 1-1 1.5-.5.5-.9.8-1.5 1-.4.2-1.1.3-2.3.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.9-.2-2.3-.4-.6-.2-1-.5-1.5-1-.5-.5-.8-.9-1-1.5-.2-.4-.3-1.1-.4-2.3C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.9.4-2.3.2-.6.5-1 1-1.5.5-.5.9-.8 1.5-1 .4-.2 1.1-.3 2.3-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.1-1 .1-1.6.2-2 .3-.5.2-.8.4-1.1.7-.3.3-.5.6-.7 1.1-.1.4-.2 1-.3 2-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1 .2 1.6.3 2 .2.5.4.8.7 1.1.3.3.6.5 1.1.7.4.1 1 .2 2 .3 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1-.1 1.6-.2 2-.3.5-.2.8-.4 1.1-.7.3-.3.5-.6.7-1.1.1-.4.2-1 .3-2 .1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1-.2-1.6-.3-2-.2-.5-.4-.8-.7-1.1-.3-.3-.6-.5-1.1-.7-.4-.1-1-.2-2-.3-1.2-.1-1.6-.1-4.7-.1zm0 3.3a6.7 6.7 0 1 1 0 13.4 6.7 6.7 0 0 1 0-13.4zm0 1.8a4.9 4.9 0 1 0 0 9.8 4.9 4.9 0 0 0 0-9.8zm6.9-2.1a1.6 1.6 0 1 1-3.2 0 1.6 1.6 0 0 1 3.2 0z"/></svg>
                </a>
                <a class='social' href="mailto:info@bosphilly.org" target="_blank" aria-label="Email BOS Philly">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 512 512" role="img" focusable="false"><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>
                </a>
                <a class='social' href="https://soundcloud.com/bos-philly" target="_blank" aria-label="BOS Philly on SoundCloud">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" role="img" focusable="false"><path d="M23.999 14.165c-.052 1.796-1.612 3.169-3.4 3.169h-8.18a.68.68 0 0 1-.675-.683V7.862a.747.747 0 0 1 .452-.724s.75-.513 2.333-.513a5.364 5.364 0 0 1 2.76 3.755 5.433 5.433 0 0 1 2.57 3.54c.282-.08.574-.121.868-.12.884 0 1.73.358 2.347.992s.948 1.49.922 2.373ZM10.721 8.421c.247 2.98.427 5.697 0 8.672a.264.264 0 0 1-.53 0c-.395-2.946-.22-5.718 0-8.672a.264.264 0 0 1 .53 0ZM9.072 9.448c.285 2.659.37 4.986-.006 7.655a.277.277 0 0 1-.55 0c-.331-2.63-.256-5.02 0-7.655a.277.277 0 0 1 .556 0Zm-1.663-.257c.27 2.726.39 5.171 0 7.904a.266.266 0 0 1-.532 0c-.38-2.69-.257-5.21 0-7.904a.266.266 0 0 1 .532 0Zm-1.647.77a26.108 26.108 0 0 1-.008 7.147.272.272 0 0 1-.542 0 27.955 27.955 0 0 1 0-7.147.275.275 0 0 1 .55 0Zm-1.67 1.769c.421 1.865.228 3.5-.029 5.388a.257.257 0 0 1-.514 0c-.21-1.858-.398-3.549 0-5.389a.272.272 0 0 1 .543 0Zm-1.655-.273c.388 1.897.26 3.508-.01 5.412-.026.28-.514.283-.54 0-.244-1.878-.347-3.54-.01-5.412a.283.283 0 0 1 .56 0Zm-1.668.911c.4 1.268.257 2.292-.026 3.572a.257.257 0 0 1-.514 0c-.241-1.262-.354-2.312-.023-3.572a.283.283 0 0 1 .563 0Z"/></svg>
                </a>
                <a class='social' id="mobileToggle" aria-label="Toggle navigation">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" role="img" focusable="false"><path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 7h18v2H3v-2z"/></svg>
                </a>
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
