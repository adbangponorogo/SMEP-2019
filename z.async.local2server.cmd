@ECHO OFF
SETLOCAL
SET CWRSYNCHOME=%PROGRAMFILES%\cwRsync\bin
SET CWOLDPATH=%PATH%
SET PATH=%CWRSYNCHOME%;%PATH%

rem rsync -avp --chmod=u=rwx,go=rx --delete-before --ignore-errors --force lpsepono@lpse.or.id:/home/lpsepono/www/smep_2019/ /cygdrive/c/wamp64/www/smep19old/
rsync -avp --chmod=u=rwx,go=rx --delete-before --ignore-errors --force /cygdrive/c/wamp64/www/smep19old/ /cygdrive/c/wamp64/www/smep19nu/

pause