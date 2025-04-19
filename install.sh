#!/bin/bash
BIN_DIR=/usr/local/bin
INSTALL_DIR=/usr/local/lib
SCRIPT_DIR=$(dirname "${BASH_SOURCE[0]}")

# Define colors
R='\033[0;31m'
G='\033[0;32m'
Y='\033[1;33m'
NC='\033[0m'

# Must be run as root (sudo)
if [ "$EUID" -ne 0 ]; then
  echo -e "${R}error:${NC} root needed. try this: ${Y}sudo ./install.sh${NC}"
  exit 1
fi

# Check each argument for --remove
for arg in "$@"; do
  if [ "$arg" = "--remove" ]; then
    echo "Removing executable..."
    rm -f "$BIN_DIR/vibe"
    echo "Removing interpreter library..."
    rm -rf "$INSTALL_DIR/vibe"
    echo "Uninstallation complete."
    exit 0
  fi
done

# Copy the vibe executable to BIN_DIR
echo -e "Installing vibe executable..."
cat > "$BIN_DIR/vibe" <<EOF
#!/usr/bin/env php
<?php
if ($argc < 2) { echo -e "Usage: ${Y}vibe script.vibe${NC}\n"; exit(1); }
chdir($INSTALL_DIR/vibe);
require './interpreter.php';
EOF
chmod +x "$BIN_DIR/vibe"

# Copy the interpreter directory to INSTALL_DIR
echo -e "Installing interpreter library..."
cp -r "$SCRIPT_DIR" "$INSTALL_DIR"
cd "$INSTALL_DIR/vibe"

./setup.sh

echo -e "Installation complete."
echo -e "You can now run vibe scripts with: ${Y}vibe script.vibe${NC}"
