<!DOCTYPE html>
<html lang="en">
<head>
<?php $media_base = function_exists('bos_media_prod_base_url') ? bos_media_prod_base_url() : 'https://www.bosphilly.org'; ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TNNFCZT4');</script>
<!-- End Google Tag Manager -->
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1255364439052457');
fbq('track', 'PageView');
</script>
<!-- End Meta Pixel Code -->
<base href="/" />
<?php
$_bos_route = query();
$_bos_page  = $_bos_route['page'] ?? '';
$_bos_name  = $_bos_route['name'] ?? '';
if ( $_bos_name ) {
    $_bos_post = get_page_by_path( $_bos_name, OBJECT, [ 'event', 'dj', 'page' ] );
    $_bos_label = $_bos_post ? $_bos_post->post_title : ucfirst( str_replace( '-', ' ', $_bos_name ) );
    $page_title = 'BOS Philly :: ' . $_bos_label;
} elseif ( $_bos_page ) {
    $page_title = 'BOS Philly :: ' . ucfirst( $_bos_page );
} else {
    $page_title = 'BOS Philly';
}
?>
<title><?= esc_html( $page_title ) ?></title>
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
<link rel="preload" as="image" href="<?= esc_url($media_base . '/wordpress/wp-content/uploads/content-charity-parallax.png'); ?>" />
<?php endif; ?>
<link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="icon" href="<?= esc_url($media_base . '/wordpress/wp-content/uploads/content-bos-logo.png'); ?>" sizes="32x32">
<link rel="icon" href="<?= esc_url($media_base . '/wordpress/wp-content/uploads/content-bos-logo.png'); ?>" sizes="192x192">
<link rel="apple-touch-icon" href="<?= esc_url($media_base . '/wordpress/wp-content/uploads/content-bos-logo.png'); ?>">
<!-- Preconnect to script CDNs used site-wide -->
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y5X648WGJN"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-Y5X648WGJN');      // GA4 - BOS Philly
gtag('config', 'AW-17640299113');     // Google Ads
</script>
<!-- End Google tag (gtag.js) -->
<!-- Universe Ticket Widget: Meta Pixel event bridge -->
<script>
document.addEventListener('unii:tracking:metapixel', ((data) => {
  if (fbq) {
    fbq(data.detail.command, data.detail.sendTo, data.detail.target, data.detail.options, { eventID: data.detail.eventId });
  }
}), false);
</script>
<!-- Universe Ticket Widget: GA4 event bridge -->
<script>
document.addEventListener('unii:tracking:googleanalytics', ((data) => {
  window.dataLayer = window.dataLayer || [];
  dataLayer.push(data.detail.command, data.detail.target, data.detail.options);
}), false);
</script>
<!-- Facebook verification -->
<meta name="facebook-domain-verification" content="ca9qnb8k0n5fkq5jfuw2unmwkxcj13" />
<!-- End Facebook verification -->
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TNNFCZT4"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Meta Pixel Code (noscript) -->
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1255364439052457&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code (noscript) -->
<header class="fixed">
    <div class="header-inner">
        <a href="/" id="home">
            <img id="logo" src="<?= esc_url($media_base . '/wordpress/wp-content/uploads/content-bos-logo.png'); ?>" alt="BOS Logo" width="117" height="84">
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
