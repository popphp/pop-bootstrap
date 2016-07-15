@echo off
REM
REM Pop Web Bootstrap Application Framework Windows CLI Batch Script
REM

SET SCRIPT_DIR=%~dp0

php %SCRIPT_DIR%app.php %*
