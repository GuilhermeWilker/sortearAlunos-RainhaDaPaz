@echo off
start php -S localhost:8888
timeout /t 2 >nul
start http://localhost:8888