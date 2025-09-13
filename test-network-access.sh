#!/bin/bash

# Network Access Test Script for Gentle Walker
echo "🌐 Testing Network Access for Gentle Walker..."

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get server IP
SERVER_IP=$(ip addr show | grep "inet " | grep -v 127.0.0.1 | head -1 | awk '{print $2}' | cut -d'/' -f1)

echo -e "${GREEN}Server IP:${NC} $SERVER_IP"
echo -e "${GREEN}Application URL:${NC} http://$SERVER_IP:8000"
echo ""

# Test 1: Check if containers are running
echo "🔍 Test 1: Checking if containers are running..."
if docker-compose ps | grep -q "Up"; then
    echo -e "${GREEN}✅ Containers are running${NC}"
else
    echo -e "${RED}❌ Containers are not running${NC}"
    echo "Run: docker-compose up -d"
    exit 1
fi

# Test 2: Check if port 8000 is listening
echo ""
echo "🔍 Test 2: Checking if port 8000 is listening..."
if netstat -tulpn 2>/dev/null | grep -q ":8000"; then
    echo -e "${GREEN}✅ Port 8000 is listening${NC}"
else
    echo -e "${RED}❌ Port 8000 is not listening${NC}"
    echo "Check if nginx container is running properly"
fi

# Test 3: Test local access
echo ""
echo "🔍 Test 3: Testing local access..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200\|302"; then
    echo -e "${GREEN}✅ Local access works${NC}"
else
    echo -e "${RED}❌ Local access failed${NC}"
fi

# Test 4: Test network access
echo ""
echo "🔍 Test 4: Testing network access..."
if curl -s -o /dev/null -w "%{http_code}" http://$SERVER_IP:8000 | grep -q "200\|302"; then
    echo -e "${GREEN}✅ Network access works${NC}"
else
    echo -e "${RED}❌ Network access failed${NC}"
fi

# Test 5: Check firewall status
echo ""
echo "🔍 Test 5: Checking firewall status..."
if command -v ufw >/dev/null 2>&1; then
    UFW_STATUS=$(sudo ufw status | head -1)
    echo -e "${YELLOW}Firewall Status:${NC} $UFW_STATUS"
    
    if echo "$UFW_STATUS" | grep -q "inactive"; then
        echo -e "${GREEN}✅ Firewall is inactive - no blocking${NC}"
    else
        echo -e "${YELLOW}⚠️  Firewall is active - may block access${NC}"
        echo "If access is blocked, run: sudo ufw allow 8000"
    fi
else
    echo -e "${GREEN}✅ No UFW firewall detected${NC}"
fi

# Test 6: Health check
echo ""
echo "🔍 Test 6: Testing health endpoint..."
if curl -s http://$SERVER_IP:8000/health | grep -q "healthy"; then
    echo -e "${GREEN}✅ Health check passed${NC}"
else
    echo -e "${RED}❌ Health check failed${NC}"
fi

echo ""
echo "📱 Network Access Summary:"
echo "=========================="
echo -e "${GREEN}🌐 Application URL:${NC} http://$SERVER_IP:8000"
echo -e "${GREEN}🔍 Health Check:${NC} http://$SERVER_IP:8000/health"
echo ""
echo "📋 Access Instructions:"
echo "1. Open a web browser on any device on your network"
echo "2. Enter: http://$SERVER_IP:8000"
echo "3. Login with: superadmin@gentlewalker.com / password"
echo ""
echo "🔧 If access doesn't work:"
echo "1. Check if devices are on the same network"
echo "2. Try: sudo ufw allow 8000"
echo "3. Restart containers: docker-compose restart"
echo "4. Check logs: docker-compose logs web" 