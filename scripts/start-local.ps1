param([int]$Port = 8000)
$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$phpExecutable = Join-Path $projectRoot '.tools/php/php.exe'
$databaseExecutable = Join-Path $projectRoot '.tools/mariadb/mariadb-11.4.5-winx64/bin/mariadbd.exe'
$databaseConfig = Join-Path $projectRoot '.tools/data/my.ini'
if (!(Test-Path -LiteralPath $phpExecutable) -or !(Test-Path -LiteralPath $databaseConfig)) {
    throw 'Portable local runtimes are missing. Follow the manual setup in README.md.'
}
function Test-LocalPort([int]$ListenPort) {
    $client = [System.Net.Sockets.TcpClient]::new()
    try {
        $connection = $client.ConnectAsync('127.0.0.1', $ListenPort)
        return $connection.Wait(300) -and $client.Connected
    } catch { return $false } finally { $client.Dispose() }
}
function Start-LocalProcess([string]$Executable, [string[]]$Arguments, [int]$ListenPort, [string]$LogName) {
    if (Test-LocalPort $ListenPort) {
        $running = Get-Process -ErrorAction SilentlyContinue | Where-Object { $_.Path -eq $Executable }
        if (!$running) {
            throw "Port $ListenPort is already used by a different application."
        }
        Write-Host "Already running on port $ListenPort."
        return
    }
    $process = Start-Process -FilePath $Executable -ArgumentList $Arguments -WorkingDirectory $projectRoot -WindowStyle Hidden -PassThru -RedirectStandardOutput (Join-Path $projectRoot ".tools/$LogName.out.log") -RedirectStandardError (Join-Path $projectRoot ".tools/$LogName.err.log")
    for ($attempt = 0; $attempt -lt 50; $attempt++) {
        if ($process.HasExited) { throw "Server exited. Check .tools/$LogName.err.log." }
        if (Test-LocalPort $ListenPort) { return }
        Start-Sleep -Milliseconds 100
    }
    throw "Server did not start. Check .tools/$LogName.err.log."
}
Start-LocalProcess $databaseExecutable @("--defaults-file=`"$databaseConfig`"", '--bind-address=127.0.0.1', '--console') 3307 'database'
Start-LocalProcess $phpExecutable @('-S', "127.0.0.1:$Port", 'router.php') $Port 'php-server'
Write-Host "Lumora is ready at http://127.0.0.1:$Port"
