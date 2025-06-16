import puppeteer from 'puppeteer';
import fs from 'fs';
import pretty from 'pretty';
import { pathToFileURL } from 'url';

/**
 * Scrape the provided URL and return the formatted HTML.
 * Returns null if an error occurs.
 * @param {string} url
 * @returns {Promise<string|null>}
 */
export async function scrape(url) {
  try {
    const browser = await puppeteer.launch({
      defaultViewport: null,
    });
    const [page] = await browser.pages();

    await page.goto(url, { waitUntil: 'networkidle2' });
    let data = await page.evaluate(() =>
      document.querySelector('*').outerHTML
    );

    data = pretty(data);

    fs.writeFileSync('copy.html', data);

    await browser.close();
    return data;
  } catch (err) {
    console.error(err);
    return null;
  }
}

// Run automatically when executed from the command line
if (import.meta.url === pathToFileURL(process.argv[1]).href) {
  const url = process.argv[2] || 'https://dev.bosphilly.com/';
  scrape(url);
}