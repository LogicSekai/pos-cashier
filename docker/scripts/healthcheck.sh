#!/bin/sh

# Docker health check script for POS Cashier application
# This script checks if the application is running properly

# Check if nginx is running
if ! pgrep -x "nginx" > /dev/null; then
    echo "Nginx is not running"
    exit 1
fi

# Check if php-fpm is running
if ! pgrep -x "php-fpm" > /dev/null; then
    echo "PHP-FPM is not running"
    exit 1
fi

# Check if the application responds
if ! curl -f http://localhost/api/health > /dev/null 2>&1; then
    # If health endpoint doesn't exist, just check if nginx responds
    if ! curl -f http://localhost > /dev/null 2>&1; then
        echo "Application is not responding"
        exit 1
    fi
fi

echo "Application is healthy"
exit 0
