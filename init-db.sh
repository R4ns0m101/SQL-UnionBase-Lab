#!/bin/bash
set -e

echo "Creating database users..."

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
EOSQL

echo "Database users created successfully!"
