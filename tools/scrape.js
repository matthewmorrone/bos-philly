import puppeteer from 'puppeteer';
import fs from 'fs';
import pretty from "pretty";

const url = 'https://dev.bosphilly.com/';

try {
    const browser = await puppeteer.launch({
        defaultViewport: null
    });
    const [page] = await browser.pages();

    await page.goto(url, {waitUntil: 'networkidle2'});
    let data = await page.evaluate(() => document.querySelector('*').outerHTML);

    data = pretty(data);

    fs.writeFileSync("copy.html", data);

    await browser.close();
} 
catch (err) {
    console.error(err);
}