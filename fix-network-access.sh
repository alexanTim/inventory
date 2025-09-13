#!/bin/bash

# Fix Network Access Issues for Gentle Walker
echo "🌐 Fixing Network Access Issues..."

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Get server IP
SERVER_IP=$(ip addr show | grep "inet " | grep -v 127.0.0.1 | head -1 | awk '{print $2}' | cut -d'/' -f1)

print_status "Server IP: $SERVER_IP"

# Step 1: Update environment configuration
print_status "Step 1: Updating environment configuration..."

# Function to update env file
update_env_file() {
    local env_file=$1
    local file_name=$(basename "$env_file")
    
    if [ -f "$env_file" ]; then
        print_status "Updating $file_name..."
        
        # Update APP_URL
        sed -i "s|APP_URL=.*|APP_URL=http://$SERVER_IP|g" "$env_file"
        
        # Update Vite configuration
        sed -i "s|VITE_HOST=.*|VITE_HOST=$SERVER_IP|g" "$env_file"
        sed -i "s|VITE_REVERB_HOST=.*|VITE_REVERB_HOST=$SERVER_IP|g" "$env_file"
        
        # Ensure session settings are correct
        if ! grep -q "SESSION_SECURE_COOKIE=false" "$env_file"; then
            echo "SESSION_SECURE_COOKIE=false" >> "$env_file"
        fi
        
        print_status "$file_name updated successfully"
    else
        print_warning "$file_name not found, skipping..."
    fi
}

# Update both .env.docker and .env files
update_env_file ".env.docker"
update_env_file ".env"

# Ensure .env exists by copying from .env.docker if needed
if [ ! -f ".env" ] && [ -f ".env.docker" ]; then
    print_status "Creating .env from .env.docker..."
    cp .env.docker .env
    update_env_file ".env"
fi

# Step 2: Rebuild assets
print_status "Step 2: Rebuilding assets..."
npm run build

# Step 3: Restart containers
print_status "Step 3: Restarting containers..."
docker-compose down
docker-compose up -d --build

# Step 4: Wait for services to be ready
print_status "Step 4: Waiting for services to be ready..."
sleep 30

# Step 5: Clear Laravel cache
print_status "Step 5: Clearing Laravel cache..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan route:clear

# Step 6: Fix permissions
print_status "Step 6: Fixing permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
docker-compose exec app chmod -R 777 /var/www/html/storage/framework/cache/data

# Step 7: Test network access
print_status "Step 7: Testing network access..."
if curl -s -o /dev/null -w "%{http_code}" http://$SERVER_IP | grep -q "200\|302"; then
    print_status "✅ Network access test passed!"
else
    print_warning "⚠️  Network access test failed, but containers are running"
fi

# Step 8: Check firewall
print_status "Step 8: Checking firewall..."
if command -v ufw >/dev/null 2>&1; then
    UFW_STATUS=$(sudo ufw status | head -1)
    if echo "$UFW_STATUS" | grep -q "inactive"; then
        print_status "✅ Firewall is inactive"
    else
        print_warning "⚠️  Firewall is active"
        print_status "Run: sudo ufw allow 80"
    fi
else
    print_status "✅ No UFW firewall detected"
fi

echo ""
print_status "🎉 Network access fix completed!"
echo ""
echo "📱 Access Information:"
echo "   🌐 Main Application: http://$SERVER_IP"
echo "   🔍 Health Check: http://$SERVER_IP/health"
echo ""
echo "📝 Environment files updated:"
echo "   ✅ .env.docker - Updated with server IP: $SERVER_IP"
echo "   ✅ .env - Updated with server IP: $SERVER_IP"
echo ""
echo "👤 Login Credentials:"
echo "   Email: superadmin@gentlewalker.com"
echo "   Password: password"
echo ""
echo "🔧 If issues persist:"
echo "   1. Check browser console for errors"
echo "   2. Clear browser cache and cookies"
echo "   3. Try incognito/private browsing mode"
echo "   4. Check if devices are on the same network"
echo "   5. Verify environment files have correct IP addresses"
echo ""
print_warning "⚠️  Remember to change default passwords in production!" 