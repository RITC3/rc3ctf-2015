#!/usr/bin/env bash

cd ..
unzip site.zip

mysql -u root -p'Password!' < create_db.sql
mysql -u root -p'Password!' < create_user.sql

rm create_user.sql
#rm create_db.sql
#rm flag.txt

