# Quick Start Guide - Docker Deployment

This is a quick reference for deploying POS Cashier with Docker.

## Prerequisites

- Docker and Docker Compose installed on VPS
- Git installed
- Domain/subdomain configured (optional)

## Quick Deploy Steps

### 1. First Time Setup on VPS

```bash
# Clone repository
git clone https://github.com/LogicSekai/pos-cashier.git
cd pos-cashier

# Configure environment
cp .env.docker.example .env
nano .env  # Edit configuration

# Run setup
chmod +x docker/scripts/setup.sh
./docker/scripts/setup.sh
```

### 2. Subsequent Deployments

```bash
cd pos-cashier
./docker/scripts/deploy.sh
```

### 3. Automated CI/CD Setup

Add these secrets to your GitHub repository:

| Secret Name | Description | Example |
|------------|-------------|---------|
| `DOCKER_USERNAME` | Docker Hub username | `myusername` |
| `DOCKER_PASSWORD` | Docker Hub password | `mypassword` |
| `VPS_HOST` | VPS IP or domain | `192.168.1.100` |
| `VPS_USERNAME` | SSH username | `root` |
| `VPS_SSH_KEY` | Private SSH key | `-----BEGIN RSA...` |
| `VPS_PORT` | SSH port | `22` |
| `VPS_PROJECT_PATH` | Project path on VPS | `/home/user/pos-cashier` |

After setup, every push to `master` or `main` branch will automatically deploy to VPS.

## Common Commands

```bash
# Start application
docker compose up -d

# Stop application
docker compose down

# View logs
docker compose logs -f app

# Run artisan command
docker compose exec app php artisan migrate

# Access container shell
docker compose exec app sh

# Rebuild containers
docker compose up -d --build
```

## Default Login

- **Superadmin**: superadmin@pos.com / password
- **Admin**: admin@pos.com / password

⚠️ **Change default passwords immediately in production!**

## Need Help?

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed documentation.
