# BOS

## Steps for Migration to Dev
+ Clone or pull in terminal: git clone http://github.com/matthewmorrone/bos-phillu dev.bosphilly.com --depth=1
+ Change the database credentials in wpconfig.php

## To-Do List

+ Fix post page appearances
+ Document steps for deployment to server

### Appearance
+ Use flex for layout where appropriate
+ Fix particle animations
+ On mobile, remove hover and put titles under grid cells
+ Sticky header animation doesn't seem worth the trouble
+ Charity animation should be slower and more chaotic

### Next Steps
+ Consider adding related pages
+ Consider implementing board details
+ Consider importing draft data
+ Consider url redirection strategies for events
+ Create front-end for gallery album management
+ Consider "label" implementation for DJ-to-Event relationship
+ Consider Instagram feeds and image hosting
+ And of course, speed everything wayyyy up

### Under the hood
+ Separate out index.js into separate files
+ Optimize deployment from localhost -> github -> dev.bosphilly.com

### Known Bugs
+ Charity animation doesn’t start on mobile until scroll/tap
+ Particle animations aren't tall enough