-- Setup script for HRM database
-- Run this with: mysql -u root -p < scripts/setup-db.sql

-- Create database
CREATE DATABASE IF NOT EXISTS `hrm-system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user for application (if not exists)
CREATE USER IF NOT EXISTS 'hrm_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';
CREATE USER IF NOT EXISTS 'hrm_user'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'HRm4n@gep@ss';

-- Grant privileges
GRANT ALL PRIVILEGES ON `hrm-system`.* TO 'hrm_user'@'localhost';
GRANT ALL PRIVILEGES ON `hrm-system`.* TO 'hrm_user'@'127.0.0.1';

-- Flush privileges
FLUSH PRIVILEGES;

-- Show databases
SHOW DATABASES LIKE 'hrm%';
