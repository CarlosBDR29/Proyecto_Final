@echo off
cd /d %~dp0

REM IP LOCAL - CAMBIA ESTA A LA IP DE TU RED WIFI SI ES DISTINTA
REM Cambiar por Dirección IPv4 actual
set IP_LOCAL=192.168.0.22

start cmd /k "echo Iniciando Vite... && npm run dev"
start cmd /k "echo Migrando, limpiando configuración y arrancando Laravel... && php artisan migrate && php artisan config:clear && php artisan serve --host=%IP_LOCAL% --port=8000"

