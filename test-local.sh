#!/bin/bash

##############################################################################
# iHumbak Chat Plugin - Quick Test Script
# 
# Quickly symlink the plugin to your local WordPress for development/testing
#
# Usage:
#   ./test-local.sh
##############################################################################

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

PLUGIN_SLUG="ihumbak-chat"

echo -e "${BLUE}=========================================="
echo "iHumbak Chat - Local Test Setup"
echo -e "==========================================${NC}"
echo ""

# Check if we're in the right directory
if [ ! -f "ihumbak-chat.php" ]; then
    echo -e "${RED}Error: ihumbak-chat.php not found!${NC}"
    echo "Please run this script from the plugin root directory."
    exit 1
fi

# Get current directory
CURRENT_DIR=$(pwd)

echo -e "${YELLOW}Enter the path to your WordPress plugins directory:${NC}"
echo -e "${BLUE}Example: /Users/yourname/Local Sites/mysite/app/public/wp-content/plugins${NC}"
echo -e "${BLUE}Or: ~/Sites/wordpress/wp-content/plugins${NC}"
echo ""
read -p "Path: " WP_PLUGINS_PATH

# Expand ~ to home directory
WP_PLUGINS_PATH="${WP_PLUGINS_PATH/#\~/$HOME}"

# Validate path
if [ -z "$WP_PLUGINS_PATH" ]; then
    echo -e "${RED}No path provided. Cancelled.${NC}"
    exit 1
fi

if [ ! -d "$WP_PLUGINS_PATH" ]; then
    echo -e "${RED}Directory not found: $WP_PLUGINS_PATH${NC}"
    exit 1
fi

# Check if plugin already exists
if [ -d "$WP_PLUGINS_PATH/$PLUGIN_SLUG" ] || [ -L "$WP_PLUGINS_PATH/$PLUGIN_SLUG" ]; then
    echo -e "${YELLOW}Plugin directory already exists. Remove it? (y/n)${NC}"
    read -p "Remove: " REMOVE
    if [ "$REMOVE" = "y" ]; then
        rm -rf "$WP_PLUGINS_PATH/$PLUGIN_SLUG"
        echo -e "${GREEN}✓ Removed old plugin${NC}"
    else
        echo -e "${RED}Cancelled.${NC}"
        exit 1
    fi
fi

# Create symlink
echo ""
echo -e "${BLUE}Creating symlink...${NC}"
ln -s "$CURRENT_DIR" "$WP_PLUGINS_PATH/$PLUGIN_SLUG"

echo ""
echo -e "${GREEN}=========================================="
echo "✓ Setup complete!"
echo -e "==========================================${NC}"
echo ""
echo -e "${BLUE}Plugin symlinked to:${NC}"
echo "$WP_PLUGINS_PATH/$PLUGIN_SLUG"
echo ""
echo -e "${YELLOW}Next steps:${NC}"
echo "  1. Go to WordPress Admin → Plugins"
echo "  2. Activate 'iHumbak Chat'"
echo "  3. Go to iHumbak Chat → Settings"
echo "  4. Configure and test!"
echo ""
echo -e "${BLUE}Note: Any changes to files will immediately reflect in WordPress.${NC}"
echo ""
