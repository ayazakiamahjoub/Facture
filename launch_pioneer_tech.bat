@echo off
color 0A
echo.
echo  ██████╗ ██╗ ██████╗ ███╗   ██╗███████╗███████╗██████╗     ████████╗███████╗ ██████╗██╗  ██╗
echo  ██╔══██╗██║██╔═══██╗████╗  ██║██╔════╝██╔════╝██╔══██╗    ╚══██╔══╝██╔════╝██╔════╝██║  ██║
echo  ██████╔╝██║██║   ██║██╔██╗ ██║█████╗  █████╗  ██████╔╝       ██║   █████╗  ██║     ███████║
echo  ██╔═══╝ ██║██║   ██║██║╚██╗██║██╔══╝  ██╔══╝  ██╔══██╗       ██║   ██╔══╝  ██║     ██╔══██║
echo  ██║     ██║╚██████╔╝██║ ╚████║███████╗███████╗██║  ██║       ██║   ███████╗╚██████╗██║  ██║
echo  ╚═╝     ╚═╝ ╚═════╝ ╚═╝  ╚═══╝╚══════╝╚══════╝╚═╝  ╚═╝       ╚═╝   ╚══════╝ ╚═════╝╚═╝  ╚═╝
echo.
echo                                    PROJECT MANAGER
echo                              Innovation • Excellence • Performance
echo.
echo ================================================================================
echo.

echo [INFO] Verification des services XAMPP...
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
echo [INFO] Verification de la base de données Pioneer Tech...

REM Verifier la base de données
C:\xampp\mysql\bin\mysql.exe -u root -e "USE pioneer_tech; SELECT 1;" >nul 2>&1
if %errorlevel% == 0 (
    echo [OK] Base de données 'pioneer_tech' accessible
) else (
    echo [ATTENTION] Base de données non trouvée, création en cours...
    C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS pioneer_tech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    
    echo [INFO] Importation du schéma Pioneer Tech...
    powershell -Command "Get-Content database\schema.sql | C:\xampp\mysql\bin\mysql.exe -u root pioneer_tech"
    
    if %errorlevel% == 0 (
        echo [OK] Base de données Pioneer Tech créée et initialisée
    ) else (
        echo [ERREUR] Impossible de créer la base de données
        pause
        exit /b 1
    )
)

echo.
echo ================================================================================
echo                            PIONEER TECH PROJECTMANAGER
echo                                   PRET A L'EMPLOI !
echo ================================================================================
echo.
echo [URL PRINCIPALE] http://localhost/pioneer tech/
echo.
echo [COMPTES DE DEMONSTRATION]
echo   Administrateur : admin@pioneertech.com / password
echo   Employe 1      : jean.dupont@pioneertech.com / password
echo   Employe 2      : marie.martin@pioneertech.com / password
echo.
echo [OUTILS DE DIAGNOSTIC]
echo   Test connexion : http://localhost/pioneer tech/test_connection.php
echo   Diagnostic     : http://localhost/pioneer tech/diagnostic.php
echo   Statut JSON    : http://localhost/pioneer tech/check_status.php
echo.

echo [INFO] Ouverture de l'application Pioneer Tech...
start "" "http://localhost/pioneer tech/"

echo.
echo [SUCCESS] Application Pioneer Tech ProjectManager lancée avec succès !
echo.
echo Appuyez sur une touche pour fermer cette fenetre...
pause >nul
