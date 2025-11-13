# Docker Deployment Guide

This guide explains how to deploy the POS Cashier application using Docker on a VPS.

## Prerequisites

- VPS with Ubuntu 20.04+ or similar Linux distribution
- Docker and Docker Compose installed
- Git installed
- Domain name (optional, but recommended)

## Initial VPS Setup

### 1. Install Docker

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Add user to docker group
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker compose
sudo chmod +x /usr/local/bin/docker compose

# Verify installation
docker --version
docker compose --version
```

### 2. Clone Repository

```bash
# Clone the repository
git clone https://github.com/LogicSekai/pos-cashier.git
cd pos-cashier
```

### 3. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit configuration
nano .env
```

Update the following variables in `.env`:

```env
APP_NAME="POS Cashier"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=pos_cashier
DB_USERNAME=pos_user
DB_PASSWORD=your-secure-password
DB_ROOT_PASSWORD=your-root-password

# For production, set a strong password
DB_PASSWORD=your_secure_password_here
```

### 4. Run Initial Setup

```bash
# Make setup script executable (if not already)
chmod +x docker/scripts/setup.sh

# Run setup script
./docker/scripts/setup.sh
```

This will:
- Generate application key
- Build Docker containers
- Set up database
- Run migrations
- Seed initial data
- Optimize the application

## GitHub Actions CI/CD Setup

### 1. Configure GitHub Secrets

Go to your GitHub repository settings and add the following secrets:

**Docker Hub Credentials:**
- `DOCKER_USERNAME` - Your Docker Hub username
- `DOCKER_PASSWORD` - Your Docker Hub password or access token

**VPS Credentials:**
- `VPS_HOST` - Your VPS IP address or domain
- `VPS_USERNAME` - SSH username (usually `root` or your user)
- `VPS_SSH_KEY` - Your private SSH key for VPS access
- `VPS_PORT` - SSH port (default: 22)
- `VPS_PROJECT_PATH` - Path to project on VPS (e.g., `/home/user/pos-cashier`)

### 2. Generate SSH Key (if needed)

On your local machine or VPS:

```bash
# Generate SSH key pair
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"

# Copy public key to VPS
ssh-copy-id user@your-vps-ip

# Display private key to add to GitHub secrets
cat ~/.ssh/id_rsa
```

### 3. Automatic Deployment

Once configured, the application will automatically deploy when you push to the `master` or `main` branch.

The workflow will:
1. Build Docker image
2. Push to Docker Hub
3. SSH into VPS
4. Pull latest code and image
5. Restart containers
6. Run migrations
7. Clear and optimize caches

## Manual Deployment

For manual deployments, use the deployment script:

```bash
# SSH into your VPS
ssh user@your-vps-ip

# Navigate to project directory
cd /path/to/pos-cashier

# Run deployment script
./docker/scripts/deploy.sh
```

## Docker Commands

### Start Application

```bash
docker compose up -d
```

### Stop Application

```bash
docker compose down
```

### View Logs

```bash
# All services
docker compose logs -f

# Specific service
docker compose logs -f app
```

### Execute Commands in Container

```bash
# Artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear

# Access container shell
docker compose exec app sh
```

### Rebuild Containers

```bash
docker compose up -d --build
```

## Using with MySQL Database

The default `docker compose.yml` is configured for MySQL. The setup includes:

- MySQL 8.0 container
- Automatic database creation
- Persistent data volume

MySQL data is stored in the `db-data` volume and will persist across container restarts.

## Using with SQLite (Alternative)

If you prefer SQLite for development or small deployments:

1. Update `.env`:
```env
DB_CONNECTION=sqlite
```

2. Use the development compose file:
```bash
docker compose -f docker compose.dev.yml up -d
```

## SSL/HTTPS Setup with Nginx Proxy

For production with HTTPS, use nginx-proxy and letsencrypt:

```bash
# Create docker network
docker network create nginx-proxy

# Run nginx-proxy
docker run -d -p 80:80 -p 443:443 \
  --name nginx-proxy \
  --net nginx-proxy \
  -v /var/run/docker.sock:/tmp/docker.sock:ro \
  -v nginx-certs:/etc/nginx/certs \
  -v nginx-vhost:/etc/nginx/vhost.d \
  -v nginx-html:/usr/share/nginx/html \
  nginxproxy/nginx-proxy

# Run letsencrypt companion
docker run -d \
  --name nginx-proxy-letsencrypt \
  --net nginx-proxy \
  -v /var/run/docker.sock:/var/run/docker.sock:ro \
  --volumes-from nginx-proxy \
  jrcs/letsencrypt-nginx-proxy-companion
```

Then update `docker compose.yml` to use the proxy:

```yaml
services:
  app:
    environment:
      - VIRTUAL_HOST=your-domain.com
      - LETSENCRYPT_HOST=your-domain.com
      - LETSENCRYPT_EMAIL=your-email@example.com
    networks:
      - nginx-proxy
      - pos-network

networks:
  nginx-proxy:
    external: true
  pos-network:
    driver: bridge
```

## Backup

### Database Backup

```bash
# MySQL backup
docker compose exec db mysqldump -u root -p$DB_ROOT_PASSWORD pos_cashier > backup.sql

# Restore
docker compose exec -T db mysql -u root -p$DB_ROOT_PASSWORD pos_cashier < backup.sql
```

### Full Backup

```bash
# Backup storage folder
tar -czf storage-backup.tar.gz storage/

# Backup database volume
docker run --rm -v pos-cashier_db-data:/data -v $(pwd):/backup alpine tar czf /backup/db-backup.tar.gz /data
```

## Monitoring

### Check Container Status

```bash
docker compose ps
```

### Monitor Resource Usage

```bash
docker stats
```

### View Application Logs

```bash
docker compose logs -f app
```

## Troubleshooting

### Permission Issues

```bash
docker compose exec app chown -R www-data:www-data /var/www/html/storage
docker compose exec app chmod -R 755 /var/www/html/storage
```

### Clear All Caches

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```

### Database Connection Issues

```bash
# Check if database is running
docker compose ps db

# Check database logs
docker compose logs db

# Test connection
docker compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

### Rebuild Everything

```bash
docker compose down -v
docker compose up -d --build
./docker/scripts/setup.sh
```

## Security Recommendations

1. **Change default passwords** immediately after setup
2. **Use strong passwords** for database and application
3. **Enable firewall** on VPS:
   ```bash
   sudo ufw allow 22/tcp
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw enable
   ```
4. **Keep system updated**:
   ```bash
   sudo apt update && sudo apt upgrade -y
   ```
5. **Use HTTPS** in production with SSL certificates
6. **Regular backups** of database and storage
7. **Monitor logs** for suspicious activity

## Support

For issues or questions:
- Create an issue on GitHub
- Check existing documentation
- Review Docker logs for errors

## License

This project is licensed under the MIT License.
