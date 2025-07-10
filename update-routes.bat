@echo off
echo Updating routes file...
copy /Y routes\web.php.new routes\web.php
echo Routes file updated successfully!
echo Please run: php artisan route:clear
echo And then: php artisan optimize