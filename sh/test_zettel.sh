#!/bin/bash
# ================================================================
#  test_zettel.sh  –  Testlauf: Erläuterungszettel in Zm einfügen
# ================================================================
#  Aufruf:  bash /home/www/am.fl.de/html/sh/test_zettel.sh
#
#  Zweck:   Zeigt das Grundmuster für alle späteren Zubringer:
#           1. Konfig + Hilfsfunktionen laden
#           2. Zettel-JSON zusammenbauen
#           3. In eine Zm-Welt einfügen
# ================================================================

source /home/www/am.fl.de/html/sh/am_konfig.sh
source /home/www/am.fl.de/html/sh/am_hilfe.sh

ZIEL_WELT="$ZM_DATEN/start.json"

am_log "test_zettel.sh gestartet"

zettel=$(am_zettel_json \
    --titel      "Allesmeister" \
    --inhalt     "<p><strong>Allesmeister (Am)</strong> ist der Zubringer für den Zettelmeister.</p><p>Er liest Daten aus anderen Anwendungen (z.B. Katalogmeister) und legt daraus Zettel an.</p><p>Heimat: <a href=\"https://am.fl.de\">am.fl.de</a></p><p>Quellcode: <a href=\"https://github.com/delinde/Allesmeister\">github.com/delinde/Allesmeister</a></p>" \
    --farbe      "#D4EDFF" \
    --winkel     "10" \
    --breite     "340" \
    --hoehe      "240" \
    --stichworte "am,allesmeister,zubringer" \
    --link       "https://am.fl.de" \
    --link-ziel  "tab")

am_zettel_hinzufuegen "$ZIEL_WELT" "$zettel"

am_log "test_zettel.sh beendet"
