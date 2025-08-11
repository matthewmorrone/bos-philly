# BOS

## Steps for Migration to Dev
+ Clone or pull in terminal: git clone http://github.com/matthewmorrone/bos-philly dev.bosphilly.com --depth=1
+ Change the database credentials in wpconfig.php
+ Export extant wordpress configuration (backup/backup) to current configuration
+ Run PHP scripts to reconnect images if necessary
+ Run PHP script to rebuild data if necessary