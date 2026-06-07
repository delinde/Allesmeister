#!/bin/bash
# ================================================================
#  am_konfig.sh  –  Gemeinsame Einstellungen für den Allesmeister
# ================================================================
#  Einbinden mit:  source "$(dirname "$0")/../am_konfig.sh"
#              oder source /home/www/am.fl.de/html/sh/am_konfig.sh
# ================================================================

# --- Allesmeister -----------------------------------------------
AM_HTML="/home/www/am.fl.de/html"
AM_SH="$AM_HTML/sh"
AM_VORLAGEN="$AM_HTML/vorlagen"
AM_PROTOKOLL="$AM_HTML/protokoll"

# --- Zettelmeister ----------------------------------------------
ZM_HTML="/home/www/zm.fl.de/html"
ZM_DATEN="$ZM_HTML/daten"

# --- Katalogmeister (DB) ----------------------------------------
KM_HTML="/home/www/km.fl.de/html"
KM_DB_HOST="localhost"
KM_DB_USER="dm"
KM_DB_PW="lahnbiege"
KM_DB_NAME="OffenesProjektKm"

# --- Server -----------------------------------------------------
SERVER_IP="46.225.170.170"
PHP_FPM_SOCK="/run/php/php8.3-fpm.sock"
