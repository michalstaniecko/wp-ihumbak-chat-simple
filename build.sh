#!/bin/bash

##############################################################################
# iHumbak Chat Plugin - Build Script
# 
# This script prepares the plugin for local testing or production deployment
#
# Usage:
#   ./build.sh              # Build plugin ZIP
#   ./build.sh --install    # Build and install to local WordPress
#   ./build.sh --clean      # Clean build artifacts
##############################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Plugin details
PLUGIN_SLUG="ihumbak-chat"
# Extract version from main plugin file
PLUGIN_VERSION=$(grep -i "^ \* Version:" ihumbak-chat.php | awk '{print $3}')
BUILD_DIR="build"
DIST_DIR="dist"

# Print colored message
print_message() {
    local color=$1
    shift
    echo -e "${color}$@${NC}"
}

print_header() {
    echo ""
    print_message "$BLUE" "=========================================="
    print_message "$BLUE" "$@"
    print_message "$BLUE" "=========================================="
    echo ""
}

print_success() {
    print_message "$GREEN" "✓ $@"
}

print_error() {
    print_message "$RED" "✗ $@"
}

print_warning() {
    print_message "$YELLOW" "⚠ $@"
}

# Clean build directories
clean() {
    print_header "Cleaning build artifacts..."
    
    if [ -d "$BUILD_DIR" ]; then
        rm -rf "$BUILD_DIR"
        print_success "Removed $BUILD_DIR directory"
    fi
    
    if [ -d "$DIST_DIR" ]; then
        rm -rf "$DIST_DIR"
        print_success "Removed $DIST_DIR directory"
    fi
    
    # Remove any ZIP files
    if ls ${PLUGIN_SLUG}*.zip 1> /dev/null 2>&1; then
        rm ${PLUGIN_SLUG}*.zip
        print_success "Removed old ZIP files"
    fi
    
    print_success "Cleanup complete!"
}

# Validate PHP syntax
validate_php() {
    print_header "Validating PHP syntax..."
    
    local errors=0
    
    while IFS= read -r -d '' file; do
        if ! php -l "$file" > /dev/null 2>&1; then
            print_error "Syntax error in: $file"
            php -l "$file"
            errors=$((errors + 1))
        fi
    done < <(find . -name "*.php" -not -path "./vendor/*" -not -path "./node_modules/*" -not -path "./$BUILD_DIR/*" -not -path "./$DIST_DIR/*" -print0)
    
    if [ $errors -eq 0 ]; then
        print_success "All PHP files are valid!"
        return 0
    else
        print_error "Found $errors PHP syntax error(s)"
        return 1
    fi
}

# Create build directory structure
prepare_build() {
    print_header "Preparing build directory..."
    
    # Create directories
    mkdir -p "$BUILD_DIR/$PLUGIN_SLUG"
    mkdir -p "$DIST_DIR"
    
    # Copy plugin files
    print_message "$BLUE" "Copying plugin files..."
    
    # Main files
    cp ihumbak-chat.php "$BUILD_DIR/$PLUGIN_SLUG/"
    cp README.md "$BUILD_DIR/$PLUGIN_SLUG/"
    cp LICENSE "$BUILD_DIR/$PLUGIN_SLUG/"
    cp CHANGELOG.md "$BUILD_DIR/$PLUGIN_SLUG/"
    
    # Copy directories
    cp -r includes "$BUILD_DIR/$PLUGIN_SLUG/"
    cp -r admin "$BUILD_DIR/$PLUGIN_SLUG/"
    cp -r public "$BUILD_DIR/$PLUGIN_SLUG/"
    cp -r assets "$BUILD_DIR/$PLUGIN_SLUG/"
    cp -r languages "$BUILD_DIR/$PLUGIN_SLUG/"
    
    print_success "Files copied to build directory"
}

# Create ZIP file
create_zip() {
    print_header "Creating plugin ZIP file..."
    
    local zip_name="${PLUGIN_SLUG}-${PLUGIN_VERSION}.zip"
    
    cd "$BUILD_DIR"
    
    if command -v zip &> /dev/null; then
        zip -r "../$DIST_DIR/$zip_name" "$PLUGIN_SLUG" -x "*.git*" "*.DS_Store" > /dev/null
        print_success "Created: $DIST_DIR/$zip_name"
    else
        print_error "ZIP command not found. Please install zip utility."
        cd ..
        return 1
    fi
    
    cd ..
    
    # Get file size
    local size=$(du -h "$DIST_DIR/$zip_name" | cut -f1)
    print_message "$GREEN" "Package size: $size"
}

# Install to local WordPress
install_to_wordpress() {
    print_header "Installing to local WordPress..."
    
    # Try to find WordPress installation
    local wp_paths=(
        "$HOME/Local Sites/*/app/public/wp-content/plugins"
        "/Applications/MAMP/htdocs/*/wp-content/plugins"
        "/var/www/html/wp-content/plugins"
        "$HOME/Sites/*/wp-content/plugins"
    )
    
    local wp_found=false
    local wp_path=""
    
    # Ask user for WordPress path
    echo ""
    print_message "$YELLOW" "Enter the path to your WordPress plugins directory:"
    print_message "$BLUE" "Example: /path/to/wordpress/wp-content/plugins"
    read -p "Path: " wp_path
    
    if [ -z "$wp_path" ]; then
        print_error "No path provided. Installation cancelled."
        return 1
    fi
    
    # Expand ~ to home directory
    wp_path="${wp_path/#\~/$HOME}"
    
    if [ ! -d "$wp_path" ]; then
        print_error "Directory not found: $wp_path"
        return 1
    fi
    
    # Remove old installation if exists
    if [ -d "$wp_path/$PLUGIN_SLUG" ]; then
        print_warning "Removing old installation..."
        rm -rf "$wp_path/$PLUGIN_SLUG"
    fi
    
    # Copy plugin
    print_message "$BLUE" "Copying plugin to WordPress..."
    cp -r "$BUILD_DIR/$PLUGIN_SLUG" "$wp_path/"
    
    print_success "Plugin installed to: $wp_path/$PLUGIN_SLUG"
    echo ""
    print_message "$GREEN" "=========================================="
    print_message "$GREEN" "Installation complete!"
    print_message "$GREEN" "=========================================="
    echo ""
    print_message "$YELLOW" "Next steps:"
    echo "  1. Go to WordPress Admin → Plugins"
    echo "  2. Activate 'iHumbak Chat'"
    echo "  3. Go to iHumbak Chat → Settings"
    echo "  4. Configure your settings"
    echo ""
}

# Build the plugin
build() {
    print_header "Building iHumbak Chat Plugin v${PLUGIN_VERSION}"
    
    # Validate PHP
    if ! validate_php; then
        print_error "Build failed due to PHP syntax errors"
        exit 1
    fi
    
    # Prepare build
    prepare_build
    
    # Create ZIP
    if ! create_zip; then
        print_error "Failed to create ZIP file"
        exit 1
    fi
    
    print_message "$GREEN" "=========================================="
    print_message "$GREEN" "Build completed successfully!"
    print_message "$GREEN" "=========================================="
    echo ""
    print_message "$BLUE" "Plugin package: $DIST_DIR/${PLUGIN_SLUG}-${PLUGIN_VERSION}.zip"
    echo ""
}

# Show help
show_help() {
    echo ""
    echo "iHumbak Chat Plugin - Build Script"
    echo ""
    echo "Usage:"
    echo "  ./build.sh              Build plugin ZIP file"
    echo "  ./build.sh --install    Build and install to local WordPress"
    echo "  ./build.sh --clean      Clean build artifacts"
    echo "  ./build.sh --help       Show this help message"
    echo ""
    echo "Examples:"
    echo "  ./build.sh                    # Create production ZIP"
    echo "  ./build.sh --install          # Install to local WordPress"
    echo "  ./build.sh --clean            # Clean up build files"
    echo ""
}

# Main script
main() {
    # Check if we're in the right directory
    if [ ! -f "ihumbak-chat.php" ]; then
        print_error "Error: ihumbak-chat.php not found!"
        print_error "Please run this script from the plugin root directory."
        exit 1
    fi
    
    # Parse arguments
    case "${1:-}" in
        --clean)
            clean
            ;;
        --install)
            clean
            build
            install_to_wordpress
            ;;
        --help|-h)
            show_help
            ;;
        "")
            clean
            build
            ;;
        *)
            print_error "Unknown option: $1"
            show_help
            exit 1
            ;;
    esac
}

# Run main function
main "$@"
