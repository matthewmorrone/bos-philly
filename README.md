# BOS

## Steps for Migration to Dev
+ Clone or pull in terminal: git clone http://github.com/matthewmorrone/bos-phillu dev.bosphilly.com --depth=1
+ Change the database credentials in wpconfig.php

## To-Do List
+ wysiwyg editor for custom event pages
+ Add link to apple and/or google BOS calendars
+ Move dev to .org
+ Move tickets.bosphilly.com to .org
+ Move img.bosphilly.com to .org
+ Add redirect of bosphilly.com to bosphilly.org

### Appearance
+ Charity animation should be slower and more chaotic

### Next Steps
+ Consider adding related pages
+ Consider implementing board details
+ Consider importing draft data
+ Consider url redirection strategies for events
+ Create front-end for gallery album management
+ Consider Instagram feeds and image hosting

### Under the hood
+ Separate out index.js into separate files
+ Optimize deployment from localhost -> github -> dev.bosphilly.com

### Known Bugs
+ Background image resizes when section loads more
+ Links generated after saving a page are incorrect