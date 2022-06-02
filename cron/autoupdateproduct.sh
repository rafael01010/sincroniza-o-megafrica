#!/bin/sh
sudo wget https://sinc.megafrica.ao/getimages
sleep 3h
cd /var/www/html/cron/
sudo wget https://sinc.megafrica.ao/public/export/csv
sudo wget --spider "https://megafrica.ao/wp-load.php?import_key=jFtA39kupM&import_id=4&action=trigger&rand="$RANDOM
sudo wget -q -O - "https://megafrica.ao/wp-load.php?import_key=jFtA39kupM&import_id=4&action=processing&rand="$RANDOM

sudo rm wp-*
sudo rm csv*
sudo rm wget*
sleep 1h
sudo rm -r /var/www/html/getimages/img/
cd cd /var/www/html/public/
rm *.csv



