# Fichier : start-symfony.ps1
# À placer à la racine de ton projet (ou ailleurs), puis clic droit > Exécuter avec PowerShell

Write-Host "`n🔍 Vérification de Symfony server..."

# Kill les processus symfony existants
Get-Process symfony -ErrorAction SilentlyContinue | ForEach-Object {
    Write-Host "🛑 Processus Symfony trouvé (PID: $($_.Id)), arrêt..."
    Stop-Process -Id $_.Id -Force
}

# Vérifie si le port 8000 est occupé
$port = 8000
$used = netstat -aon | findstr ":$port"
if ($used) {
    $pidLine = $used -split '\s+' | Where-Object { $_ -match '^\d+$' } | Select-Object -Last 1
    Write-Host "⚠️ Le port $port est déjà utilisé par le processus PID $pidLine."
    $confirm = Read-Host "Tu veux le killer ? (y/n)"
    if ($confirm -eq "y") {
        Stop-Process -Id $pidLine -Force
        Write-Host "✅ Port libéré !"
    } else {
        Write-Host "❌ Opération annulée. Le port est toujours occupé."
        exit
    }
}

# Lancer le serveur Symfony
Write-Host "`n🚀 Lancement du serveur Symfony..."
Start-Process powershell -ArgumentList "symfony server:start -d" -WorkingDirectory "C:\le\chemin\vers\ton\projet"

Start-Sleep -Seconds 2
Start-Process "http://127.0.0.1:8000"
Write-Host "✅ Serveur lancé et ouvert dans le navigateur."

# Pause pour garder la fenêtre ouverte
Pause