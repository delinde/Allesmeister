#!/bin/bash
# ================================================================
#  km_zm.sh  –  Katalogmeister → Zettelmeister  (Zubringer)
# ================================================================
#  Legt Standard-Zettel für den Katalogmeister in der Zm-Welt an:
#    - Km-Wegweiser   (Navigationsspalte)
#    - Km-Suche       (Suchformular + Ergebnisse)
#    - Km-Liste       (Alle Einträge, seitenweise)
#
#  Aufruf:  bash /home/www/am.fl.de/html/sh/km/km_zm.sh [WELT]
#  WELT:    Zm-Welt-Datei (Standard: $KM_ZM_WELT)
#
#  Bereits vorhandene km-Zubringer-Zettel werden vorher entfernt
#  (erkennbar am Stichwort "km-zubringer").
# ================================================================

source /home/www/am.fl.de/html/sh/am_konfig.sh
source /home/www/am.fl.de/html/sh/am_hilfe.sh

WELT="${1:-$KM_ZM_WELT}"
TAG="km-zubringer"   # Erkennungs-Stichwort für alle km-Zubringer-Zettel

am_log "km_zm.sh gestartet  →  Welt: $WELT"

# ── 1. Alte km-Zubringer-Zettel entfernen ───────────────────────
am_zettel_entfernen "$WELT" "$TAG"

# ── 2. Km-Wegweiser ─────────────────────────────────────────────
zettel=$(am_zettel_json \
    --titel      "Km-Wegweiser" \
    --farbe      "#d4ffea" \
    --winkel     "-12" \
    --breite     "240" \
    --hoehe      "360" \
    --stichworte "km,katalogmeister,wegweiser,$TAG" \
    --op-div     '{"quelle":"km","rezept":"wegweiser","params":{}}')
am_zettel_hinzufuegen "$WELT" "$zettel"

# ── 3. Km-Suche ─────────────────────────────────────────────────
zettel=$(am_zettel_json \
    --titel      "Km-Suche" \
    --farbe      "#d4ffea" \
    --winkel     "5" \
    --breite     "440" \
    --hoehe      "480" \
    --stichworte "km,katalogmeister,suche,$TAG" \
    --op-div     '{"quelle":"km","rezept":"suche","params":{}}')
am_zettel_hinzufuegen "$WELT" "$zettel"

# ── 4. Km-Liste ─────────────────────────────────────────────────
zettel=$(am_zettel_json \
    --titel      "Km-Liste" \
    --farbe      "#d4ffea" \
    --winkel     "18" \
    --breite     "480" \
    --hoehe      "520" \
    --stichworte "km,katalogmeister,liste,$TAG" \
    --op-div     '{"quelle":"km","rezept":"liste","params":{"seite":1}}')
am_zettel_hinzufuegen "$WELT" "$zettel"

am_log "km_zm.sh beendet  →  3 Zettel angelegt"
