@echo off
TITLE DS Fish Hunter - Built-in Server
echo --------------------------------------------------
echo      DS FISH HUNTER - PREMIUM PREDATORS
echo --------------------------------------------------
echo.
echo Sedang menyiapkan server...
echo Server akan berjalan di http://localhost:8000
echo.
echo JANGAN TUTUP JENDELA INI SELAMA MEMBUKA WEB!
echo.

:: Check if php is in PATH
where php >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    set PHP_EXE=php
) else (
    set PHP_EXE="D:\xampp\php\php.exe"
)

:: Start the browser
start "" "http://localhost:8000"

:: Start the PHP Built-in Server
%PHP_EXE% -S localhost:8000


pause
