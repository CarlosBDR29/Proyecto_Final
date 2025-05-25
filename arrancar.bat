@echo off
cd /d %~dp0

start cmd /k "echo Iniciando Vite... && npm run dev"
start cmd /k "echo Migrando, limpiando configuración y arrancando Laravel... && php artisan migrate && php artisan config:clear && php artisan serve"
