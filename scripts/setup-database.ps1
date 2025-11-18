# Interactive MySQL Database Setup for HRM System
# This script helps you create the database and user

Write-Host "=== HRM System Database Setup ===" -ForegroundColor Cyan
Write-Host ""

# Check if MySQL is running
$mysqlService = Get-Service -Name "MySQL91" -ErrorAction SilentlyContinue
if ($mysqlService -and $mysqlService.Status -eq "Running") {
  Write-Host "✓ MySQL service is running" -ForegroundColor Green
}
else {
  Write-Host "✗ MySQL service not found or not running" -ForegroundColor Red
  Write-Host "  Please start MySQL from Laragon or Services" -ForegroundColor Yellow
  exit 1
}

Write-Host ""
Write-Host "This script will:" -ForegroundColor Yellow
Write-Host "  1. Create database 'hrm-system'"
Write-Host "  2. Create user 'hrm_user' with password 'HRm4n@gep@ss'"
Write-Host "  3. Grant privileges to the user"
Write-Host ""

# Prompt for root password
Write-Host "Please choose setup method:" -ForegroundColor Cyan
Write-Host "  [1] I know my MySQL root password (enter it)"
Write-Host "  [2] Use MySQL Workbench/phpMyAdmin (manual setup)"
Write-Host "  [3] Use SQLite instead (quick alternative)"
Write-Host ""
$choice = Read-Host "Enter choice (1, 2, or 3)"

switch ($choice) {
  "1" {
    Write-Host ""
    $rootPassword = Read-Host "Enter MySQL root password" -AsSecureString
    $rootPasswordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto(
      [Runtime.InteropServices.Marshal]::SecureStringToBSTR($rootPassword)
    )
        
    Write-Host "Attempting to create database..." -ForegroundColor Yellow
        
    # Create temporary SQL file
    $sqlContent = @"
CREATE DATABASE IF NOT EXISTS ``hrm-system`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'hrm_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
CREATE USER IF NOT EXISTS 'hrm_user'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
GRANT ALL PRIVILEGES ON ``hrm-system``.* TO 'hrm_user'@'localhost';
GRANT ALL PRIVILEGES ON ``hrm-system``.* TO 'hrm_user'@'127.0.0.1';
FLUSH PRIVILEGES;
SELECT 'Database setup completed successfully!' as Result;
"@
        
    $sqlContent | Out-File -FilePath "setup-temp.sql" -Encoding UTF8
        
    # Execute SQL
    $output = & mysql -u root "-p$rootPasswordPlain" < setup-temp.sql 2>&1
    Remove-Item "setup-temp.sql" -ErrorAction SilentlyContinue
        
    if ($LASTEXITCODE -eq 0) {
      Write-Host "✓ Database setup completed!" -ForegroundColor Green
      Write-Host ""
      Write-Host "Next steps:" -ForegroundColor Cyan
      Write-Host "  php artisan config:clear"
      Write-Host "  php artisan migrate --seed"
    }
    else {
      Write-Host "✗ Failed to setup database" -ForegroundColor Red
      Write-Host $output -ForegroundColor Red
      Write-Host ""
      Write-Host "Try option 2 or 3 instead" -ForegroundColor Yellow
    }
  }
    
  "2" {
    Write-Host ""
    Write-Host "Manual Setup Instructions:" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "1. Open MySQL Workbench or phpMyAdmin"
    Write-Host "   - Laragon users: Click 'Database' button"
    Write-Host "   - Or visit: http://localhost/phpmyadmin"
    Write-Host ""
    Write-Host "2. Login with your root credentials"
    Write-Host ""
    Write-Host "3. Run this SQL (copy from below):" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "---------- COPY BELOW ----------" -ForegroundColor DarkGray
    Write-Host @"
CREATE DATABASE IF NOT EXISTS ``hrm-system`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'hrm_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
CREATE USER IF NOT EXISTS 'hrm_user'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
GRANT ALL PRIVILEGES ON ``hrm-system``.* TO 'hrm_user'@'localhost';
GRANT ALL PRIVILEGES ON ``hrm-system``.* TO 'hrm_user'@'127.0.0.1';
FLUSH PRIVILEGES;
"@ -ForegroundColor White
    Write-Host "---------- COPY ABOVE ----------" -ForegroundColor DarkGray
    Write-Host ""
    Write-Host "4. After running SQL, execute these commands:" -ForegroundColor Yellow
    Write-Host "   php artisan config:clear"
    Write-Host "   php artisan migrate --seed"
    Write-Host ""
        
    # Copy SQL to clipboard
    $sqlToCopy = @"
CREATE DATABASE IF NOT EXISTS ``hrm-system`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'hrm_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
CREATE USER IF NOT EXISTS 'hrm_user'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
GRANT ALL PRIVILEGES ON ``hrm-system``.* TO 'hrm_user'@'localhost';
GRANT ALL PRIVILEGES ON ``hrm-system``.* TO 'hrm_user'@'127.0.0.1';
FLUSH PRIVILEGES;
"@
    Set-Clipboard -Value $sqlToCopy
    Write-Host "✓ SQL copied to clipboard!" -ForegroundColor Green
  }
    
  "3" {
    Write-Host ""
    Write-Host "Setting up SQLite instead..." -ForegroundColor Yellow
        
    # Create SQLite database file
    New-Item -ItemType File -Path "database\database.sqlite" -Force | Out-Null
    Write-Host "✓ Created database\database.sqlite" -ForegroundColor Green
        
    # Update .env
    $envContent = Get-Content ".env" -Raw
    $envContent = $envContent -replace 'DB_CONNECTION=mysql', 'DB_CONNECTION=sqlite'
    $envContent = $envContent -replace '^DB_HOST=', '#DB_HOST='
    $envContent = $envContent -replace '^DB_PORT=', '#DB_PORT='
    $envContent = $envContent -replace '^DB_DATABASE=', '#DB_DATABASE='
    $envContent = $envContent -replace '^DB_USERNAME=', '#DB_USERNAME='
    $envContent = $envContent -replace '^DB_PASSWORD=', '#DB_PASSWORD='
    $envContent | Set-Content ".env"
        
    Write-Host "✓ Updated .env to use SQLite" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "  php artisan config:clear"
    Write-Host "  php artisan migrate --seed"
  }
    
  default {
    Write-Host "Invalid choice" -ForegroundColor Red
    exit 1
  }
}
