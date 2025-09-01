# BOS

This repository contains the code and WordPress installation for the BOS
project. The site is designed to run inside the [DDEV](https://ddev.com/)
development environment.

## Installation

1. Install [DDEV](https://ddev.com/) and Docker.
2. Clone this repository and run `ddev start` from the project root.
3. After the containers build, WordPress will be available at the URL
   printed by DDEV (typically `https://bos.ddev.site`).

### WordPress Configuration

WordPress lives in the `wordpress/` directory. The provided `wp-config.php`
is configured to work with DDEV. If you are running without DDEV, update the
database credentials in `wp-config.php` to match your local database.

### Installing Node Dependencies

Some data migration utilities rely on Node.js packages. Change into the
`tools/` directory and run `npm install` to install them. If Puppeteer is not
needed, you can set the `PUPPETEER_SKIP_DOWNLOAD=1` environment variable.

### Linting JavaScript

Run `npm install` in the project root to install ESLint. Then execute `npm run lint` to check all JavaScript files for style issues.

Run `node scrape.js [url]` from inside the `tools/` directory to save the
rendered HTML from a page. If `url` is omitted, it defaults to
`https://dev.bosphilly.com/`.

### Data Migration Scripts

The `tools/` folder contains PHP scripts that help move data between
installations:

* `db-to-csv.php` – export custom post types and metadata to CSV files.
* `csv-to-db.php` – import those CSV files back into the database.
* `content-to-csv.php` – export post content to `csv/content.csv`.
* `csv-to-content.php` – import content from that CSV back into WordPress.
* `connect-featured-images.php` – associate downloaded images as featured
  images.
* `connect-extra-images.php` – attach additional gallery images.
* `generate-thumbs.php` – rebuild thumbnails for the image galleries.

Run any of these tools with `php tools/<script-name.php>` once WordPress is
configured and accessible.

#### Running Node Scripts

Some migration utilities are written in Node.js, such as `tools/scrape.js`.
Install dependencies in the `tools/` directory with:

```bash
npm install
```

Then execute a script using `node` and any required arguments:

```bash
node tools/scrape.js <url>
```

Replace `<url>` with the site you want to scrape or process.

### Running the Site Locally

With DDEV running, visit the site at the URL printed by `ddev start` (for
example `https://bos.ddev.site`). Development assets can be live reloaded by
running `ddev browsersync`.

## To-Do List
+ wysiwyg editor for custom event pages
+ Move tickets.bosphilly.com to .org
+ Add redirect of bosphilly.com to bosphilly.org
+ lock down .git on www.dev.bosphilly.org
+ lock down wordpress
+ interface wordpress with google calendar via ajax

### Next Steps
+ Consider adding related pages
+ Consider implementing board details
+ Consider url redirection strategies for events
+ Create front-end for gallery album management

### Known Bugs
+ Background image resizes when section loads more
+ Links generated after saving a page are incorrect
Test change for PR flow — no functional code changes.
