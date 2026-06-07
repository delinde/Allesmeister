#!/bin/bash
# ================================================================
#  am_hilfe.sh  –  Hilfsfunktionen für den Allesmeister
# ================================================================
#  Einbinden mit:
#    source /home/www/am.fl.de/html/sh/am_konfig.sh
#    source /home/www/am.fl.de/html/sh/am_hilfe.sh
# ================================================================


# ----------------------------------------------------------------
# am_log  –  Meldung ins Protokoll schreiben (und auf Bildschirm)
# Aufruf:  am_log "Meldung"
# ----------------------------------------------------------------
am_log() {
    local meldung="$1"
    local zeitstempel
    zeitstempel=$(date '+%Y-%m-%d %H:%M:%S')
    local zeile="[$zeitstempel] $meldung"
    echo "$zeile"
    echo "$zeile" >> "$AM_PROTOKOLL/am.log"
}


# ----------------------------------------------------------------
# am_zettel_json  –  Einen Zettel als JSON-Objekt ausgeben
#
# Aufruf:
#   am_zettel_json \
#     --titel   "Mein Titel"     \
#     --inhalt  "<p>Text</p>"    \
#     --farbe   "#FFFFCC"        \
#     --winkel  "15"             \
#     --breite  "300"            \
#     --hoehe   "200"            \
#     --stichworte "km,wort"     \
#     --link    "https://..."
#
# Alle Parameter sind optional – Voreinstellungen greifen.
# ----------------------------------------------------------------
am_zettel_json() {
    local titel="Neuer Zettel"
    local inhalt="<p></p>"
    local farbe="#FFFFEE"
    local winkel="0"
    local y="0"
    local breite="300"
    local hoehe="200"
    local stichworte=""
    local link=""
    local link_ziel="tab"

    # Parameter einlesen
    while [[ $# -gt 0 ]]; do
        case "$1" in
            --titel)      titel="$2";      shift 2 ;;
            --inhalt)     inhalt="$2";     shift 2 ;;
            --farbe)      farbe="$2";      shift 2 ;;
            --winkel)     winkel="$2";     shift 2 ;;
            --y)          y="$2";          shift 2 ;;
            --breite)     breite="$2";     shift 2 ;;
            --hoehe)      hoehe="$2";      shift 2 ;;
            --stichworte) stichworte="$2"; shift 2 ;;
            --link)       link="$2";       shift 2 ;;
            --link-ziel)  link_ziel="$2";  shift 2 ;;
            *) echo "am_zettel_json: unbekannter Parameter: $1" >&2; shift ;;
        esac
    done

    jq -n \
        --arg titel      "$titel"      \
        --arg inhalt     "$inhalt"     \
        --arg farbe      "$farbe"      \
        --argjson winkel "$winkel"     \
        --argjson y      "$y"          \
        --argjson breite "$breite"     \
        --argjson hoehe  "$hoehe"      \
        --arg stichworte "$stichworte" \
        --arg link       "$link"       \
        --arg link_ziel  "$link_ziel"  \
    '{
        winkel:       $winkel,
        y:            $y,
        breite:       $breite,
        hoehe:        $hoehe,
        titel:        $titel,
        inhalt:       $inhalt,
        farbe:        $farbe,
        rundung:      5,
        rand:         { breite: 0, farbe: "#888888", stil: "solid" },
        bild:         "",
        stichworte:   $stichworte,
        termin:       "",
        videos:       [],
        deckkraft:    1,
        scheibenkleber: false,
        scheibenEcke: "lo",
        link:         $link,
        linkZiel:     $link_ziel,
        linkZielId:   "",
        weltRegler:   false,
        fixiert:      false,
        schlank:      false,
        faehnchen:    ""
    }'
}


# ----------------------------------------------------------------
# am_zettel_hinzufuegen  –  Zettel-JSON in eine Zm-Welt einfügen
#
# Aufruf:
#   am_zettel_hinzufuegen  "/pfad/zur/welt.json"  "$zettel_json"
#
# Die Welt-Datei wird direkt überschrieben.
# Vorher wird eine Sicherungskopie angelegt (.bak).
# ----------------------------------------------------------------
am_zettel_hinzufuegen() {
    local welt_datei="$1"
    local zettel_json="$2"

    if [[ ! -f "$welt_datei" ]]; then
        am_log "FEHLER: Welt-Datei nicht gefunden: $welt_datei"
        return 1
    fi

    # Sicherungskopie
    cp "$welt_datei" "${welt_datei}.bak"

    # Zettel anhängen
    local neu
    neu=$(jq --argjson z "$zettel_json" '.zettel += [$z]' "$welt_datei")

    if [[ $? -ne 0 ]]; then
        am_log "FEHLER: jq konnte Zettel nicht einfügen in $welt_datei"
        return 1
    fi

    echo "$neu" > "$welt_datei"
    am_log "Zettel eingefügt in: $welt_datei"
}


# ----------------------------------------------------------------
# am_welt_liste  –  Alle vorhandenen Zm-Welten auflisten
# ----------------------------------------------------------------
am_welt_liste() {
    ls "$ZM_DATEN"/*.json 2>/dev/null | xargs -I{} basename {} .json
}
