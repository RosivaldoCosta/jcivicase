#!/bin/sh

# dumpDB.sh --> backs up the db to a dump file

# * * * NOTE * * * there can be no space between -p and the password!
mysqldump -u cc5tudio -p23wesdxc@#WESDXC --add-drop-table cc5tudio_jo153 > cc5tudio_jo153.mysqldump
chmod 644 cc5tudio_jo153.mysqldump
