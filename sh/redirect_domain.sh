#!/bin/bash
# ================================================================
#  redirect_domain.sh  –  Domain per HTTPS auf ein Ziel umleiten
#  Holt Let's-Encrypt-Zertifikat und richtet nginx-Redirect ein
#
#  Aufruf:  sudo bash redirect_domain.sh <quelle> <ziel>
#  Beispiel: sudo bash redirect_domain.sh zettelmeister.de zm.fl.de
# ================================================================
set -e

if [[ $EUID -ne 0 ]]; then
    exec sudo "$0" "$@"
fi

QUELLE="${1}"
ZIEL="${2}"

if [[ -z "$QUELLE" || -z "$ZIEL" ]]; then
    echo "Aufruf: $0 <quelle-domain> <ziel-domain>"
    echo "Beispiel: $0 zettelmeister.de zm.fl.de"
    exit 1
fi

ACME_ROOT="/var/www/html"
CONF_AVAIL="/etc/nginx/sites-available/${QUELLE}"
CONF_ENABLED="/etc/nginx/sites-enabled/${QUELLE}"
CERT_DIR="/etc/letsencrypt/live/${QUELLE}"

# 1. HTTP-only Config für ACME-Challenge
echo "→ Schreibe HTTP-Config für ${QUELLE} ..."
cat > "$CONF_AVAIL" <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name ${QUELLE};

    location /.well-known/acme-challenge/ {
        root ${ACME_ROOT};
    }

    location / {
        return 301 https://${ZIEL}\$request_uri;
    }
}
EOF

# 2. Site enablen
if [[ ! -L "$CONF_ENABLED" ]]; then
    ln -s "$CONF_AVAIL" "$CONF_ENABLED"
fi

nginx -t && systemctl reload nginx
echo "→ nginx geladen (HTTP)."

# 3. Zertifikat holen
echo "→ Certbot für ${QUELLE} ..."
certbot certonly --webroot -w "$ACME_ROOT" -d "$QUELLE" \
    --non-interactive --agree-tos --register-unsafely-without-email

# 4. Vollständige Config mit HTTPS-Redirect
echo "→ Schreibe vollständige nginx-Config (HTTPS-Redirect) ..."
cat > "$CONF_AVAIL" <<EOF
# HTTP -> Redirect zu https://${ZIEL}
server {
    listen 80;
    listen [::]:80;
    server_name ${QUELLE};

    location /.well-known/acme-challenge/ {
        root ${ACME_ROOT};
    }

    location / {
        return 301 https://${ZIEL}\$request_uri;
    }
}

# HTTPS -> Redirect zu https://${ZIEL}
server {
    listen 443 ssl;
    listen [::]:443 ssl;
    server_name ${QUELLE};

    ssl_certificate ${CERT_DIR}/fullchain.pem;
    ssl_certificate_key ${CERT_DIR}/privkey.pem;

    return 301 https://${ZIEL}\$request_uri;
}
EOF

nginx -t && systemctl reload nginx

echo ""
echo "Fertig! https://${QUELLE} leitet jetzt weiter zu https://${ZIEL}"
