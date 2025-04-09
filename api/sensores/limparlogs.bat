@echo off
setlocal enabledelayedexpansion

for /d %%a in (*) do (
    if exist "%%a\log.txt" (
        type nul > "%%a\log.txt"
        echo A limpar: %%a\log.txt
    )
)