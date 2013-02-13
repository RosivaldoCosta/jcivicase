sudo chown apache.apache media/civicrm/upload
sudo chown apache.apache media/civicrm/custom
sudo chown apache.apache media/civicrm/templates_c
sudo mkdir tmp/backup
sudo chown apache.apache tmp/backup
sudo touch tmp/backup/index.html
sudo rm -rf media/civicrm/templates_c/en_US
