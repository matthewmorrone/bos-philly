<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=.9, viewport-fit=cover">
<base href="/" />
<title>BOS Philly - Bringing Circuit Back to Philly</title>
<link rel="stylesheet" href="css/index.css?version=<?= randomId(4); ?>" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-75x75.png" sizes="32x32">
<link rel="icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png" sizes="192x192">
<link rel="apple-touch-icon" href="https://bosphilly.org/wp-content/uploads/2022/08/android-chrome-512x512-1-300x300.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
        <a href="/" id="home">
            <img id="logo" src="wordpress/wp-content/uploads/content-bos-logo.png" alt="BOS Logo">
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
                <a class='social' href="http://facebook.com/bosphilly" target="_blank"><i class="fab fa-facebook"></i></a>
                <a class='social' href="http://instagram.com/bosphilly" target="_blank"><i class="fab fa-instagram"></i></a>
                <a class='social' href="mailto:info@bosphilly.org" target="_blank"><i class="fas fa-envelope"></i></a>
                <a class='social' href="https://soundcloud.com/bos-philly" target="_blank"><i class="fab fa-soundcloud"></i></a>
                <a class='social' id="mobileToggle"><i class="fa-solid fa-bars"></i></a>
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
