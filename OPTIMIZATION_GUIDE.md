# Site Optimization Guide

Follow these steps to improve performance, loading time, and accessibility.

## 1. Core Metadata and Critical CSS
1. Open `templates/header.php`.
2. Replace the existing `<head>` block up to the CSS links with:
   ```html
   <!-- Core meta -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
   <meta name="description" content="BOS Philly throws world-class circuit parties in Philadelphia while supporting local LGBTQ+ charities. See upcoming events, galleries, and DJs.">
   <link rel="canonical" href="https://bosphilly.org/">

   <!-- Preconnects -->
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link rel="preconnect" href="https://fonts.googleapis.com">

   <!-- Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Teko:wght@600;700&family=Work+Sans:wght@400;600&display=swap" rel="stylesheet">

   <!-- Critical CSS -->
   <style>
     :root{--header-h:64px}
     html{scroll-behavior:smooth}
     body{margin:0}
     header.fixed{position:sticky;top:0;height:var(--header-h);backdrop-filter:saturate(120%) blur(6px)}
     #splash{position:relative;min-height:clamp(320px, 55vh, 720px);overflow:hidden}
     #splash .splash-background{position:absolute;inset:0;display:grid;place-items:center}
     #splash .splash-title{position:relative;z-index:2;text-align:center;padding-top:calc(var(--header-h) + 2rem)}
     img{max-width:100%;height:auto}
     .tile img{aspect-ratio:4/5;object-fit:cover}
     #galleries .tile img{aspect-ratio:1/1}
     @font-face{font-display:swap}
   </style>
   ```
3. Add `defer` to all blocking `<script>` tags except Google Analytics.

## 2. Hero Media
Choose one option:

### Option A – Static Image Hero
1. Export AVIF/WebP/JPEG versions of a hero image at 768, 1280, and 1920px widths.
2. Replace the current hero `<div class="splash-background">` with:
   ```html
   <div class="splash-background" aria-hidden="true">
     <picture>
       <source type="image/avif" srcset="/images/hero-768.avif 768w, /images/hero-1280.avif 1280w, /images/hero-1920.avif 1920w"/>
       <source type="image/webp" srcset="/images/hero-768.webp 768w, /images/hero-1280.webp 1280w, /images/hero-1920.webp 1920w"/>
       <img src="/images/hero-1280.jpg" alt="Crowd dancing at BOS Philly" width="1280" height="720"
            fetchpriority="high" decoding="async" loading="eager"
            sizes="(max-width: 768px) 100vw, 1280px">
     </picture>
   </div>
   ```

### Option B – Lazy-Loaded Video
1. Export a lightweight poster image (`/images/hero-poster.avif`).
2. Replace the current `<video>` block with:
   ```html
   <video id="heroVideo" preload="none" muted playsinline loop aria-label="Background video"
          poster="/images/hero-poster.avif" style="width:100%;height:100%;object-fit:cover">
     <source src="/wordpress/body-shop-background.mp4" type="video/mp4">
   </video>
   <script>
     const v=document.getElementById('heroVideo');
     if(v && !navigator.connection?.saveData){
       const io=new IntersectionObserver(([e])=>{ if(e.isIntersecting){ v.load(); v.play().catch(()=>{}); io.disconnect(); } });
       io.observe(v);
     }
   </script>
   ```

## 3. Responsive Images
1. For each content image, convert `<img>` to `<picture>` with AVIF/WebP/JPEG sources.
2. Provide accurate `width`, `height`, and `alt` attributes.
3. Add `srcset` and `sizes` to serve the correct resolution.
4. Example:
   ```html
   <picture>
     <source type="image/avif" srcset="/media/events/white-party-2-342.avif 342w, /media/events/white-party-2-512.avif 512w, /media/events/white-party-2-768.avif 768w, /media/events/white-party-2-1024.avif 1024w">
     <source type="image/webp" srcset="/media/events/white-party-2-342.webp 342w, /media/events/white-party-2-512.webp 512w, /media/events/white-party-2-768.webp 768w, /media/events/white-party-2-1024.webp 1024w">
     <img src="/media/events/white-party-2-512.jpg" alt="White Party poster" width="683" height="1024" loading="lazy" decoding="async"
          sizes="(max-width: 600px) 48vw, (max-width: 1024px) 33vw, 256px">
   </picture>
   ```

## 4. Defer Non‑Critical JavaScript
1. Replace existing library tags for lightbox, particles, pluralize, and konami with:
   ```html
   <script>
     document.addEventListener('click', async (e)=>{
       const a=e.target.closest('a');
       if(!a) return;
       if(a.matches('[data-lightbox]')){
         e.preventDefault();
         if(!window.lightbox){
           await Promise.all([
             import('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js')
           ]);
         }
         lightbox.start($(a)[0]);
       }
     });

     const deferLib=(src)=>new Promise((res)=>{
       const s=document.createElement('script');
       s.src=src; s.defer=true; s.onload=res;
       document.head.appendChild(s);
     });
     requestIdleCallback?.(()=>{
       deferLib('/js/pluralize.min.js');
       deferLib('/js/konami.min.js');
       deferLib('/js/particles.min.js');
     });
   </script>
   ```
2. Ensure site scripts (`/js/utils.js`, `/js/wp.js`) include `defer`.

## 5. Fonts and Icons
1. Keep only Work Sans 400/600 and Teko 600/700 in Google Fonts URL.
2. Replace Font Awesome icons with inline SVGs that have `aria-hidden="true"`.
3. Give the parent `<a>` an `aria-label` describing the link purpose.

## 6. Caching and Compression
1. Configure Nginx:
   ```nginx
   brotli on; brotli_comp_level 5; brotli_types text/html text/css application/javascript application/json image/svg+xml font/woff2;
   gzip on; gzip_types text/html text/css application/javascript application/json image/svg+xml;
   location ~* \.(?:avif|webp|jpg|jpeg|png|gif|svg|js|css|woff2)$ {
     add_header Cache-Control "public, max-age=31536000, immutable" always;
   }
   location ~* \.(?:html)$ {
     add_header Cache-Control "max-age=0, must-revalidate" always;
   }
   ```
2. On Cloudflare, enable Polish/Image Resizing, Auto Minify (JS/CSS/HTML), Brotli, and set cache TTL to 1 year for assets.

## 7. robots.txt
1. Create `robots.txt` containing:
   ```
   User-agent: *
   Allow: /
   Sitemap: https://bosphilly.org/sitemap.xml
   ```

## 8. Accessibility
1. Provide alt text for all images.
2. Use `aria-label` on social links and navigation icons.
3. Maintain a logical heading order (`<h1>` once per page).

## 9. Verify After Deployment
1. Run Lighthouse in Chrome DevTools.
2. Target metrics (mobile):
   - LCP ≤ 2.5s
   - CLS ≤ 0.1
   - TBT ≤ 200ms
3. Ensure homepage payload ≤ 1.5MB and ≤ 50 requests.
4. Confirm hero asset ≤ 300KB.

