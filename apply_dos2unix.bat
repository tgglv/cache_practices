for /f "tokens=* delims=" %%a in ('dir %cd% /s /b') do (
dos2unix %%a
)
