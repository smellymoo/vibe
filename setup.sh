#!/bin/bash

# Define colors
R='\033[0;31m'
G='\033[0;32m'
Y='\033[1;33m'
NC='\033[0m'

if [ "$EUID" -ne 0 ]; then
  echo -e "${R}error:${NC} root needed. try this: ${Y}sudo ./setup.sh${NC}"
  exit 1
fi

if [ -d "./vendor" ]; then
  echo -e "${G}composer found. Nothing to do.${NC}"
else
  echo -e "${G}installing composer...${NC}"
  php -r "copy('https://getcomposer.org/installer', './composer-setup.php');"
  php ./composer-setup.php
  ./composer.phar install
fi

chmod +x ./interpreter.php

echo -e "run vibe scripts with: ${Y}vibe script.vibe${NC}"
