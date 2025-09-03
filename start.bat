@echo off
echo ========================================
echo  Pioneer Tech ProjectManager - Demarrage
echo ========================================
echo.

echo Verification des services XAMPP...
echo.

REM Verifier si Apache est demarré
netstat -an | findstr :80 >nul
if %errorlevel% == 0 (
    echo [OK] Apache est demarré sur le port 80
) else (
    echo [ERREUR] Apache n'est pas demarré
    echo Veuillez demarrer Apache via le panneau de controle XAMPP
    pause
    exit /b 1
)

REM Verifier si MySQL est demarré
netstat -an | findstr :3306 >nul
if %errorlevel% == 0 (
    echo [OK] MySQL est demarré sur le port 3306
) else (
    echo [ERREUR] MySQL n'est pas demarré
    echo Veuillez demarrer MySQL via le panneau de controle XAMPP
    pause
    exit /b 1
)

echo.
echo Verification de la base de données...

REM Verifier la base de données
C:\xampp\mysql\bin\mysql.exe -u root -e "USE project_manager; SELECT 1;" >nul 2>&1
if %errorlevel% == 0 (
    echo [OK] Base de données 'project_manager' accessible
) else (
    echo [ATTENTION] Base de données non trouvée, création en cours...
    C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS project_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    
    echo Importation du schéma...
    powershell -Command "Get-Content database\schema.sql | C:\xampp\mysql\bin\mysql.exe -u root project_manager"
    
    if %errorlevel% == 0 (
        echo [OK] Base de données créée et initialisée
    ) else (
        echo [ERREUR] Impossible de créer la base de données
        pause
        exit /b 1
    )
)

echo.
echo ========================================
echo   Application prête !
echo ========================================
echo.
echo URL de l'application : http://localhost/pioneer tech
echo.
echo Comptes de demonstration :
echo   Admin    : admin@projectmanager.com / password
echo   Employe  : jean.dupont@example.com / password
echo.
echo Test de connexion : http://localhost/pioneer tech/test_connection.php
echo.

REM Ouvrir automatiquement dans le navigateur
echo Ouverture de l'application dans le navigateur...
start "http://localhost/pioneer tech"

echo.
echo Test de l'application...
powershell -Command "try { $response = Invoke-WebRequest -Uri 'http://localhost/ProjectManager/check_status.php' -UseBasicParsing; if ($response.StatusCode -eq 200) { Write-Host '[OK] Application accessible' } else { Write-Host '[ERREUR] Application non accessible' } } catch { Write-Host '[ERREUR] Impossible d'acceder a l application' }"

echo.
echo ========================================
echo   ACCES A L'APPLICATION
echo ========================================
echo.
echo 1. Application principale : http://localhost/pioneer tech/
echo 2. Test de connexion      : http://localhost/pioneer tech/test_connection.php
echo 3. Verification Apache    : http://localhost/pioneer tech/test_apache.php
echo 4. Statut de l'app        : http://localhost/pioneer tech/check_status.php
echo.
echo Appuyez sur une touche pour fermer cette fenetre...
pause >nul
