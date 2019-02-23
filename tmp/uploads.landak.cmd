@ECHO OFF
SETLOCAL
SET CWRSYNCHOME=%PROGRAMFILES%\cwRsync\bin
SET CWOLDPATH=%PATH%
SET PATH=%CWRSYNCHOME%;%PATH%

rsync -avp --chmod=u=rwx,go=rx --delete-before --ignore-errors --force /cygdrive/c/wamp64/www/smep19/ root@smep.landak.lpse.or.id:/var/www/html/smep.landakkab.go.id/smep19new/

pause