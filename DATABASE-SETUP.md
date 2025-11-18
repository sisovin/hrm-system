# MySQL Database Setup for HRM System

## Problem

Cannot connect to MySQL with current root credentials in `.env`.

## Solution Options

### Option 1: Use MySQL Workbench or phpMyAdmin (RECOMMENDED - Easiest)

1. **Open MySQL Workbench** or **phpMyAdmin** (usually comes with Laragon)
   - For Laragon: Click "Database" button in Laragon UI
   - Or visit: <http://localhost/phpmyadmin> (if Laragon web server is running)

2. **Login with your current root credentials**
   - If you don't remember the password, you may need to reset it using Laragon's MySQL manager

3. **Run these SQL commands** in the SQL tab:

   ```sql
   CREATE DATABASE IF NOT EXISTS `hrm-system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   
   CREATE USER IF NOT EXISTS 'hrm_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
   CREATE USER IF NOT EXISTS 'hrm_user'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
   
   GRANT ALL PRIVILEGES ON `hrm-system`.* TO 'hrm_user'@'localhost';
   GRANT ALL PRIVILEGES ON `hrm-system`.* TO 'hrm_user'@'127.0.0.1';
   
   FLUSH PRIVILEGES;
   ```

4. **Update your .env file** (already done - see below)

5. **Clear Laravel config cache**:

   ```powershell
   php artisan config:clear
   php artisan cache:clear
   ```

6. **Run migrations**:

   ```powershell
   php artisan migrate --seed
   ```

---

### Option 2: Use MySQL Command Line (if you know root password)

1. Open PowerShell and run:

   ```powershell
   mysql -u root -p < scripts/setup-db.sql
   ```

   (Enter your root password when prompted)

2. Then run steps 4-6 from Option 1 above.

---

### Option 3: Reset MySQL Root Password (if you forgot it)

**For MySQL 8.4 on Windows:**

1. **Stop MySQL service**:

   ```powershell
   Stop-Service MySQL91
   ```

2. **Start MySQL in safe mode** (skip grant tables):

   ```powershell
   & "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysqld.exe" --skip-grant-tables --shared-memory
   ```

3. **In a NEW PowerShell window**, connect and reset password:

   ```powershell
   mysql -u root
   ```

   Then in MySQL prompt:

   ```sql
   FLUSH PRIVILEGES;
   ALTER USER 'root'@'localhost' IDENTIFIED BY 'newpassword';
   EXIT;
   ```

4. **Stop the safe mode MySQL** (Ctrl+C in first window)

5. **Restart MySQL service normally**:

   ```powershell
   Start-Service MySQL91
   ```

6. **Test new password**:

   ```powershell
   mysql -u root -p
   ```

7. **Then follow Option 1 steps 3-6**

---

### Option 4: Use SQLite Instead (Temporary Workaround)

If you want to proceed quickly without fixing MySQL:

1. **Update `.env`**:

   ```env
   DB_CONNECTION=sqlite
   # Comment out MySQL settings
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=hrm-system
   # DB_USERNAME=hrm_user
   # DB_PASSWORD=HRm4n@gep@ss
   ```

2. **Create SQLite database file**:

   ```powershell
   New-Item -ItemType File -Path database\database.sqlite -Force
   ```

3. **Run migrations**:

   ```powershell
   php artisan migrate --seed
   ```

---

## After Database Setup

Once database is created and credentials work, run:

```powershell
# Clear config cache
php artisan config:clear
php artisan cache:clear

# Run migrations and seeders
php artisan migrate --seed

# Start development server
php artisan serve --host=127.0.0.1 --port=8001
```

---

## Next Steps After Migration

1. **Install Filament** (admin panel):

   ```powershell
   composer require filament/filament
   php artisan filament:install --panels
   ```

2. **Create admin user**:

   ```powershell
   php artisan make:filament-user
   ```

3. **Install Spatie Permissions**:

   ```powershell
   composer require spatie/laravel-permission
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   php artisan migrate
   ```

4. **Visit admin panel**: <http://127.0.0.1:8001/admin>

---

## Troubleshooting

**Error: "Access denied for user 'root'@'localhost'"**

- Your root password in `.env` is incorrect
- Try Options 1-3 above

**Error: "SQLSTATE[HY000] [2002] Connection refused"**

- MySQL service is not running
- Check with: `Get-Service MySQL91`
- Start with: `Start-Service MySQL91`

**Error: "Base table or view not found"**

- Migrations haven't run
- Run: `php artisan migrate --seed`
