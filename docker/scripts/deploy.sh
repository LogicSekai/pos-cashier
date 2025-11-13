#!/bin/bash

# Deploy script for POS Cashier application
# This script handles the deployment process to VPS

set -e

echo "🚀 Starting deployment..."

# Pull latest changes
echo "📥 Pulling latest code..."
git pull origin master || git pull origin main

# Pull latest Docker image (if using Docker registry)
if [ -n "$DOCKER_USERNAME" ]; then
    echo "🐳 Pulling Docker image..."
    docker pull $DOCKER_USERNAME/pos-cashier:latest
fi

# Stop existing containers
echo "🛑 Stopping existing containers..."
docker-compose down

# Build and start containers
echo "🏗️  Building and starting containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database..."
sleep 10

# Run migrations
echo "🔄 Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Run tenant migrations
echo "🔄 Running tenant migrations..."
docker-compose exec -T app php artisan tenants:migrate --force

# Clear caches
echo "🧹 Clearing caches..."
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan view:clear
docker-compose exec -T app php artisan route:clear

# Optimize application
echo "⚡ Optimizing application..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

# Clean up
echo "🧹 Cleaning up old images..."
docker image prune -f

echo "✅ Deployment completed successfully!"
echo "📊 Container status:"
docker-compose ps
