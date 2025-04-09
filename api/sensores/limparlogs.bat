@echo off
setlocal enabledelayedexpansion

for /d %%a in (*) do (
    if exist "%%a\log.txt" (
        type nul > "%%a\log.txt"
        echo Limpando: %%a\log.txt
    )
)