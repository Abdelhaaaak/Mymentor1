@echo off
REM ---------------------------------------------------------
REM Ce script recherche :
REM  - Les vues Blade (*.blade.php) jamais référencées.
REM  - Les images dans public\images jamais utilisées.
REM Il génère ensuite unused-elements.json au format
REM “Generic Issue Data” pour SonarQube.
REM ---------------------------------------------------------

setlocal enabledelayedexpansion

REM 1) Définir les chemins
set "BASE_DIR=%~dp0"
set "VIEW_DIR=%BASE_DIR%resources\views"
set "IMG_DIR=%BASE_DIR%public\images"
set "OUTPUT_FILE=%BASE_DIR%unused-elements.json"

REM 2) Début du JSON
> "%OUTPUT_FILE%" (
  echo { "issues": [
)

set "first_issue=1"

REM 3) Vues Blade orphelines
for /r "%VIEW_DIR%" %%F in (*.blade.php) do (
  set "full_path=%%~fF"
  set "rel_path=!full_path:%BASE_DIR%=!"
  set "view_path=!rel_path:resources\views\=!"
  set "view_no_ext=!view_path:.blade.php=!"
  set "view_key=!view_no_ext:\=.!"

  REM Recherche silencieuse de view_key parmi les fichiers texte
  powershell -Command "Select-String -Path '%BASE_DIR%**\*.php','%BASE_DIR%**\*.blade.php','%BASE_DIR%**\*.js','%BASE_DIR%**\*.vue' -Pattern '!view_key!' -Quiet"
  if errorlevel 1 (
    if "!first_issue!"=="1" (
      set "first_issue=0"
    ) else (
      >> "%OUTPUT_FILE%" echo ,
    )
    >> "%OUTPUT_FILE%" echo       {
    >> "%OUTPUT_FILE%" echo         "engineId": "batch-issues",
    >> "%OUTPUT_FILE%" echo         "ruleId": "unused-blade",
    >> "%OUTPUT_FILE%" echo         "severity": "MAJOR",
    >> "%OUTPUT_FILE%" echo         "type": "CODE_SMELL",
    >> "%OUTPUT_FILE%" echo         "primaryLocation": {
    >> "%OUTPUT_FILE%" echo           "message": "Vue Blade non référencée : !rel_path!",
    >> "%OUTPUT_FILE%" echo           "filePath": "!rel_path!",
    >> "%OUTPUT_FILE%" echo           "textRange": { "startLine": 1, "endLine": 1 }
    >> "%OUTPUT_FILE%" echo         }
    >> "%OUTPUT_FILE%" echo       }
  )
)

REM 4) Images non utilisées
for /r "%IMG_DIR%" %%F in (*.png *.jpg *.jpeg *.gif *.svg *.webp) do (
  set "full_path=%%~fF"
  set "rel_path=!full_path:%BASE_DIR%=!"
  set "filename=%%~nxF"

  powershell -Command "Select-String -Path '%BASE_DIR%**\*.php','%BASE_DIR%**\*.blade.php','%BASE_DIR%**\*.js','%BASE_DIR%**\*.vue','%BASE_DIR%**\*.css','%BASE_DIR%**\*.html' -Pattern '!filename!' -Quiet"
  if errorlevel 1 (
    if "!first_issue!"=="1" (
      set "first_issue=0"
    ) else (
      >> "%OUTPUT_FILE%" echo ,
    )
    >> "%OUTPUT_FILE%" echo       {
    >> "%OUTPUT_FILE%" echo         "engineId": "batch-issues",
    >> "%OUTPUT_FILE%" echo         "ruleId": "unused-image",
    >> "%OUTPUT_FILE%" echo         "severity": "MINOR",
    >> "%OUTPUT_FILE%" echo         "type": "CODE_SMELL",
    >> "%OUTPUT_FILE%" echo         "primaryLocation": {
    >> "%OUTPUT_FILE%" echo           "message": "Image non référencée : !rel_path!",
    >> "%OUTPUT_FILE%" echo           "filePath": "!rel_path!",
    >> "%OUTPUT_FILE%" echo           "textRange": { "startLine": 1, "endLine": 1 }
    >> "%OUTPUT_FILE%" echo         }
    >> "%OUTPUT_FILE%" echo       }
  )
)

REM 5) Fin du JSON
>> "%OUTPUT_FILE%" echo   ]
>> "%OUTPUT_FILE%" echo }

echo Fichier JSON exporté : %OUTPUT_FILE%
endlocal
