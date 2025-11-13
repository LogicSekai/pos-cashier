#!/bin/bash

# Initial setup script for POS Cashier on VPS
# Run this script once when setting up the application for the first time

set -e

echo "🚀 Starting initial setup for POS Cashier..."

# Check if .env file exists
if [ ! -f .env ]; then
    echo "📄 Creating .env file from example..."
    cp .env.example .env
    echo "⚠️  Please edit .env file with your configuration before continuing."
    echo "Press Enter when ready..."
    read
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    docker-compose run --rm app php artisan key:generate
fi

# Build and start containers
echo "🏗️  Building Docker containers..."
docker-compose up -d --build

# Wait for database
echo "⏳ Waiting for database to be ready..."
sleep 15

# Create database if it doesn't exist (for MySQL)
if grep -q "DB_CONNECTION=mysql" .env; then
    echo "🗄️  Setting up MySQL database..."
    docker-compose exec -T db mysql -uroot -p$DB_ROOT_PASSWORD -e "CREATE DATABASE IF NOT EXISTS $DB_DATABASE;"
fi

# Run migrations
echo "🔄 Running central database migrations..."
docker-compose exec -T app php artisan migrate --force

# Seed superadmin
echo "👤 Seeding superadmin user..."
docker-compose exec -T app php artisan db:seed --class=SuperadminSeeder --force

# Set proper permissions
echo "🔐 Setting permissions..."
docker-compose exec -T app chown -R www-data:www-data /var/www/html/storage
docker-compose exec -T app chmod -R 755 /var/www/html/storage

# Optimize application
echo "⚡ Optimizing application..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

echo ""
echo "✅ Setup completed successfully!"
echo ""
echo "📊 Container status:"
docker-compose ps
echo ""
echo "🔐 Default credentials:"
echo "  Superadmin: superadmin@pos.com / password"
echo "  Admin: admin@pos.com / password"
echo ""
echo "🌐 Application URL: http://your-domain.com"
echo "🔧 Superadmin URL: http://your-domain.com/superadmin/login"
echo ""
echo "⚠️  Remember to change default passwords in production!"
