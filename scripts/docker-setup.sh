#!/bin/bash
# =============================================================================
# SPIRE 26 - Docker Environment Setup Script
# =============================================================================

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}"
echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║           SPIRE 26 - Docker Environment Setup                ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# -----------------------------------------------------------------------------
# Check prerequisites
# -----------------------------------------------------------------------------
echo -e "${YELLOW}📋 Checking prerequisites...${NC}"

if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed. Please install Docker first.${NC}"
    exit 1
fi

if ! command -v docker compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose is not installed. Please install Docker Compose first.${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Docker and Docker Compose are installed${NC}"

# -----------------------------------------------------------------------------
# Generate SSL certificates with mkcert
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}🔐 Setting up SSL certificates...${NC}"

CERTS_DIR="$PROJECT_DIR/docker/nginx/certs"
mkdir -p "$CERTS_DIR"

if [ ! -f "$CERTS_DIR/spire.local.pem" ]; then
    if command -v mkcert &> /dev/null; then
        echo "Generating SSL certificates with mkcert..."
        cd "$CERTS_DIR"
        mkcert -install 2>/dev/null || true
        mkcert spire.local localhost 127.0.0.1 ::1
        mv spire.local+3.pem spire.local.pem
        mv spire.local+3-key.pem spire.local-key.pem
        echo -e "${GREEN}✅ SSL certificates generated${NC}"
    else
        echo -e "${YELLOW}⚠️  mkcert not found. Generating self-signed certificate...${NC}"
        openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
            -keyout "$CERTS_DIR/spire.local-key.pem" \
            -out "$CERTS_DIR/spire.local.pem" \
            -subj "/C=BR/ST=SP/L=SaoPaulo/O=SPIRE/CN=spire.local" \
            2>/dev/null
        echo -e "${GREEN}✅ Self-signed certificate generated${NC}"
        echo -e "${YELLOW}   Tip: Install mkcert for trusted certificates: sudo apt install mkcert${NC}"
    fi
else
    echo -e "${GREEN}✅ SSL certificates already exist${NC}"
fi

# -----------------------------------------------------------------------------
# Add spire.local to /etc/hosts
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}🌐 Checking /etc/hosts...${NC}"

if ! grep -q "spire.local" /etc/hosts; then
    echo -e "${YELLOW}Adding spire.local to /etc/hosts (requires sudo)...${NC}"
    echo "127.0.0.1   spire.local" | sudo tee -a /etc/hosts > /dev/null
    echo -e "${GREEN}✅ Added spire.local to /etc/hosts${NC}"
else
    echo -e "${GREEN}✅ spire.local already in /etc/hosts${NC}"
fi

# -----------------------------------------------------------------------------
# Create .env.docker if not exists
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}📄 Checking environment file...${NC}"

if [ ! -f "$PROJECT_DIR/.env.docker" ]; then
    cat > "$PROJECT_DIR/.env.docker" << 'EOF'
# =============================================================================
# SPIRE 26 - Docker Environment Configuration
# =============================================================================

APP_NAME="SPIRE 26"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=https://spire.local

# Database (Docker MariaDB)
DB_CONNECTION=mariadb
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=spire
DB_USERNAME=spire
DB_PASSWORD=secret

# Redis (Docker)
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail (Mailpit for local testing)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@spire.local"
MAIL_FROM_NAME="${APP_NAME}"

# Vite (Docker)
VITE_DEV_SERVER_URL=http://localhost:5173

# Broadcasting (for future real-time features)
BROADCAST_CONNECTION=log

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=debug
EOF
    echo -e "${GREEN}✅ Created .env.docker${NC}"
else
    echo -e "${GREEN}✅ .env.docker already exists${NC}"
fi

# -----------------------------------------------------------------------------
# Build and start containers
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}🐳 Building Docker containers...${NC}"
cd "$PROJECT_DIR"
docker compose build --no-cache

echo -e "\n${YELLOW}🚀 Starting containers...${NC}"
docker compose up -d

# Wait for services to be ready
echo -e "\n${YELLOW}⏳ Waiting for services to be ready...${NC}"
sleep 10

# -----------------------------------------------------------------------------
# Laravel setup inside container
# -----------------------------------------------------------------------------
echo -e "\n${YELLOW}🔧 Setting up Laravel...${NC}"

# Copy .env.docker to .env inside container if needed
docker exec spire-php bash -c "
    if [ ! -f .env ]; then
        cp .env.docker .env 2>/dev/null || cp .env.example .env
    fi
"

# Install dependencies
echo "Installing Composer dependencies..."
docker exec spire-php composer install --no-interaction --prefer-dist

# Generate key if needed
docker exec spire-php bash -c "
    if ! grep -q 'APP_KEY=base64' .env; then
        php artisan key:generate
    fi
"

# Run migrations
echo "Running migrations..."
docker exec spire-php php artisan migrate --force

# Storage link
docker exec spire-php php artisan storage:link 2>/dev/null || true

# Set permissions
docker exec spire-php bash -c "
    chown -R www-data:www-data storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache
"

# -----------------------------------------------------------------------------
# Done!
# -----------------------------------------------------------------------------
echo -e "\n${GREEN}"
echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║                    🎉 Setup Complete!                         ║"
echo "╠═══════════════════════════════════════════════════════════════╣"
echo "║                                                               ║"
echo "║  🌐 Application:    https://spire.local                       ║"
echo "║  📧 Mailpit:        http://localhost:8025                     ║"
echo "║  🗄️  Database:       localhost:3306                           ║"
echo "║  📦 Redis:          localhost:6379                            ║"
echo "║                                                               ║"
echo "╠═══════════════════════════════════════════════════════════════╣"
echo "║  Useful Commands:                                             ║"
echo "║  ─────────────────────────────────────────────────────────────║"
echo "║  docker compose logs -f         # View all logs              ║"
echo "║  docker exec -it spire-php bash # Enter PHP container        ║"
echo "║  docker compose down            # Stop all containers        ║"
echo "║  docker compose up -d --profile dev  # With Vite dev server  ║"
echo "║                                                               ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo -e "${NC}"
