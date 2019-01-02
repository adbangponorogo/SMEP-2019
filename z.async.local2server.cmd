@ECHO OFF
SETLOCAL
SET CWRSYNCHOME=%PROGRAMFILES%\cwRsync\bin
SET CWOLDPATH=%PATH%
SET PATH=%CWRSYNCHOME%;%PATH%

rsync -avp --chmod=u=rwx,go=rx --delete-before --ignore-errors --force /cygdrive/c/wamp64/www/smep19/ lpsepono@lpse.or.id:/home/lpsepono/www/smep_2019/

pause