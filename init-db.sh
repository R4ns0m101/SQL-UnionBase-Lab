#!/bin/bash
set -e

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until mysql -u root -p"${MYSQL_ROOT_PASSWORD}" -e "SELECT 1" >/dev/null 2>&1; do
  echo "MySQL is unavailable - sleeping"
  sleep 2
done

echo "MySQL is ready - creating users..."

# Create users for each lab
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" <<-EOSQL
    -- Lab 1 User
    CREATE USER IF NOT EXISTS 'webapp'@'%' IDENTIFIED BY 'webapp123';
    GRANT ALL PRIVILEGES ON lab1_sqli.* TO 'webapp'@'%';
    
    -- Lab 2 User
    CREATE USER IF NOT EXISTS 'webapp2'@'%' IDENTIFIED BY 'webapp2secure';
    GRANT ALL PRIVILEGES ON lab2_sqli.* TO 'webapp2'@'%';
    
    -- Lab 3 User
    CREATE USER IF NOT EXISTS 'webapp3'@'%' IDENTIFIED BY 'webapp3complex!';
    GRANT ALL PRIVILEGES ON lab3_ecommerce.* TO 'webapp3'@'%';
    GRANT ALL PRIVILEGES ON lab3_internal.* TO 'webapp3'@'%';
    
    FLUSH PRIVILEGES;
    
    SELECT User, Host FROM mysql.user WHERE User LIKE 'webapp%';
EOSQL

echo "Database users created successfully!"
