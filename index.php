<?php
// Allesmeister – Dokumentationsseite
$git_log = shell_exec('git -C ' . __DIR__ . ' log --oneline -6 2>/dev/null') ?? '';
$datum   = date('Y-m-d');
?><!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Allesmeister</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --blau:    #2563eb;
    --gruen:   #16a34a;
    --grau-1:  #f8fafc;
    --grau-2:  #e2e8f0;
    --grau-3:  #94a3b8;
    --grau-4:  #475569;
    --text:    #1e293b;
    --code-bg: #f1f5f9;
    --radius:  6px;
    --mono:    'Courier New', Courier, monospace;
  }

  body {
    font-family: system-ui, -apple-system, sans-serif;
    font-size: 15px; line-height: 1.65; color: var(--text);
    background: #fff; padding: 0 0 4rem;
  }

  header {
    background: var(--blau); color: #fff; padding: 2rem 2rem 1.5rem;
  }
  header h1 { font-size: 1.75rem; font-weight: 700; letter-spacing: -.02em; }
  header p  { font-size: .95rem; opacity: .85; margin-top: .35rem; }
  header nav { margin-top: 1rem; display: flex; gap: 1rem; flex-wrap: wrap; }
  header nav a {
    color: #fff; opacity: .8; text-decoration: none; font-size: .85rem;
    border-bottom: 1px solid rgba(255,255,255,.3); padding-bottom: 1px;
  }
  header nav a:hover { opacity: 1; }

  main { max-width: 860px; margin: 0 auto; padding: 2rem 1.5rem 0; }

  section { margin-top: 2.5rem; }
  section h2 {
    font-size: 1.1rem; font-weight: 700; color: var(--blau);
    border-bottom: 2px solid var(--grau-2); padding-bottom: .4rem; margin-bottom: 1rem;
  }
  section h3 { font-size: .95rem; font-weight: 600; margin: 1.2rem 0 .4rem; color: var(--grau-4); }
  p { margin-bottom: .75rem; }

  .kacheln {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: .75rem; margin-top: .75rem;
  }
  .kachel {
    border: 1px solid var(--grau-2); border-radius: var(--radius);
    padding: .75rem 1rem; background: var(--grau-1);
  }
  .kachel .name { font-weight: 700; font-size: .9rem; }
  .kachel .kurz { font-size: .75rem; color: var(--grau-3); font-family: var(--mono); }
  .kachel .info { font-size: .82rem; color: var(--grau-4); margin-top: .3rem; }
  .kachel a     { color: var(--blau); text-decoration: none; font-size: .8rem; }
  .kachel a:hover { text-decoration: underline; }

  .api-schema {
    background: var(--code-bg); border: 1px solid var(--grau-2);
    border-radius: var(--radius); padding: 1rem 1.25rem;
    font-family: var(--mono); font-size: .82rem; line-height: 1.9; overflow-x: auto;
  }
  .api-schema .kom   { color: var(--grau-3); }
  .api-schema .pfeil { color: var(--gruen); font-weight: 700; }
  .api-schema .url   { color: var(--blau); }

  table { width: 100%; border-collapse: collapse; font-size: .875rem; margin-top: .5rem; }
  th { text-align: left; padding: 6px 10px; background: var(--grau-1);
       border-bottom: 2px solid var(--grau-2); color: var(--grau-4); font-weight: 600; }
  td { padding: 5px 10px; border-bottom: 1px solid var(--grau-2); }
  tr:last-child td { border-bottom: none; }
  td code { background: var(--code-bg); padding: 1px 5px; border-radius: 3px;
            font-family: var(--mono); font-size: .82rem; }

  pre {
    background: var(--code-bg); border: 1px solid var(--grau-2);
    border-radius: var(--radius); padding: .85rem 1rem;
    font-family: var(--mono); font-size: .82rem; line-height: 1.7;
    overflow-x: auto; margin: .5rem 0;
  }
  code { font-family: var(--mono); font-size: .85em; }

  .schritte { list-style: none; margin-top: .5rem; }
  .schritte li {
    display: flex; gap: .75rem; align-items: flex-start;
    padding: .5rem 0; border-bottom: 1px solid var(--grau-2); font-size: .875rem;
  }
  .schritte li:last-child { border-bottom: none; }
  .schritte .check { color: var(--gruen); font-weight: 700; flex-shrink: 0; width: 1.2rem; }
  .schritte .datum { color: var(--grau-3); font-size: .78rem; font-family: var(--mono);
                     flex-shrink: 0; width: 5.5rem; padding-top: .1rem; }

  .git-log {
    background: #0f172a; color: #e2e8f0; border-radius: var(--radius);
    padding: .85rem 1rem; font-family: var(--mono); font-size: .8rem;
    line-height: 1.7; overflow-x: auto;
  }

  footer {
    max-width: 860px; margin: 3rem auto 0; padding: 1rem 1.5rem;
    border-top: 1px solid var(--grau-2); font-size: .8rem; color: var(--grau-3);
    display: flex; justify-content: space-between; flex-wrap: wrap; gap: .5rem;
  }
  footer a { color: var(--grau-3); }
</style>
</head>
<body>

<header>
  <h1>Allesmeister</h1>
  <p>Zubringer und Dokumentation für den Zettelmeister · fl.de</p>
  <nav>
    <a href="#ueberblick">Überblick</a>
    <a href="#api">API-Muster</a>
    <a href="#km-api">km-API</a>
    <a href="#skripte">Skripte</a>
    <a href="#protokoll">Protokoll</a>
    <a href="https://github.com/delinde/Allesmeister">GitHub ↗</a>
  </nav>
</header>

<main>

  <section id="ueberblick">
    <h2>Überblick – Die Meister-Familie</h2>
    <p>
      Der <strong>Allesmeister (Am)</strong> sorgt dafür, dass andere Anwendungen
      (Katalogmeister, Farbmeister, …) ihre Inhalte als Zettel im
      <strong>Zettelmeister (Zm)</strong> darstellen und bedienen können.
      Dazu liefert jede Anwendung eine kleine <strong>HTML-API</strong> –
      Am dokumentiert das Muster und stellt Hilfsskripte bereit.
    </p>
    <div class="kacheln">
      <div class="kachel">
        <div class="name">Zettelmeister</div>
        <div class="kurz">zm · zm.fl.de</div>
        <div class="info">3D-Zettel-App · PHP + JS · JSON-Welten</div>
        <a href="https://zm.fl.de">zm.fl.de ↗</a> ·
        <a href="https://github.com/delinde/Zettelmeister2">GitHub ↗</a>
      </div>
      <div class="kachel" style="border-color:#2563eb">
        <div class="name">Allesmeister</div>
        <div class="kurz">am · am.fl.de</div>
        <div class="info">Zubringer · Doku · Shell-Skripte</div>
        <a href="https://am.fl.de">am.fl.de ↗</a> ·
        <a href="https://github.com/delinde/Allesmeister">GitHub ↗</a>
      </div>
      <div class="kachel">
        <div class="name">Katalogmeister</div>
        <div class="kurz">km · km.fl.de</div>
        <div class="info">MySQL-Katalog · 1655 Flugautos</div>
        <a href="https://km.fl.de">km.fl.de ↗</a> ·
        <a href="https://github.com/delinde/Katalogmeister">GitHub ↗</a>
      </div>
      <div class="kachel">
        <div class="name">opnn</div>
        <div class="kurz">opnn · opnn.fl.de</div>
        <div class="info">OpenProject-Spiegel · Node.js · Port 3334</div>
        <a href="https://opnn.fl.de">opnn.fl.de ↗</a>
      </div>
      <div class="kachel">
        <div class="name">Farbmeister</div>
        <div class="kurz">fm · farbmeister.de</div>
        <div class="info">Farb-Verwaltung (geplant)</div>
        <a href="https://farbmeister.de">farbmeister.de ↗</a>
      </div>
    </div>
  </section>

  <section id="api">
    <h2>Das API-Muster</h2>
    <p>
      Jede Anwendung stellt einen <code>/api/div</code>-Endpunkt bereit,
      der benannte HTML-Abschnitte (<em>Divs</em>) zurückliefert.
      Der Zm holt diese über seinen PHP-Proxy und zeigt sie im Zettel an.
    </p>
    <div class="api-schema">
<span class="kom"># Zm-Zettel ruft auf:</span>
<span class="url">https://zm.fl.de/index.php?aktion=km&amp;div=5-ergebnis&amp;route=search&amp;q=Autogyro</span>
  <span class="pfeil">↓  PHP-Proxy (zm/index.php) – fügt zmkey hinzu</span>
<span class="url">https://km.fl.de/api/div.php?div=5-ergebnis&amp;route=search&amp;q=Autogyro&amp;zmkey=…</span>
  <span class="pfeil">↓  km-API (PHP)</span>
<span class="kom">&lt;table&gt; … 50 Treffer für „Autogyro" … &lt;/table&gt;</span>
    </div>

    <h3>Zm-Proxy – erweiterbar</h3>
    <pre>$_apiZiele = [
    'opnn' =&gt; 'https://opnn.fl.de/api/div/',   // Node.js (div im Pfad)
    'km'   =&gt; 'https://km.fl.de/api/div.php',  // PHP (div als ?div=)
    // 'fm' =&gt; 'https://farbmeister.de/api/div.php',
];</pre>

    <h3>Div-Nummern</h3>
    <table>
      <tr><th>Div</th><th>Inhalt</th><th>Auth</th></tr>
      <tr><td><code>2-wegweiser</code></td><td>Navigationsspalte</td><td>zmkey</td></tr>
      <tr><td><code>3-anmelden</code></td><td>Anmeldeformular (opnn)</td><td>–</td></tr>
      <tr><td><code>4-suche</code></td><td>Suchformular</td><td>–</td></tr>
      <tr><td><code>5-ergebnis</code></td><td>Daten-Ausgabe</td><td>zmkey</td></tr>
      <tr><td><code>6-bericht</code></td><td>Meldungen / Fehler</td><td>–</td></tr>
    </table>
  </section>

  <section id="km-api">
    <h2>km-API · <code>km.fl.de/api/div.php</code></h2>
    <p>PHP-API für den Katalogmeister. DB: <code>OffenesProjektKm</code>,
       Tabelle <code>Flugautos</code> (1655 Einträge).</p>
    <table>
      <tr><th>Aufruf</th><th>Liefert</th></tr>
      <tr><td><code>?div=4-suche&amp;q=Begriff</code></td><td>Suchformular</td></tr>
      <tr><td><code>?div=5-ergebnis&amp;route=search&amp;q=Begriff</code></td><td>Treffer-Tabelle (max. 50)</td></tr>
      <tr><td><code>?div=5-ergebnis&amp;route=liste&amp;seite=1</code></td><td>Alle Einträge (25 pro Seite)</td></tr>
      <tr><td><code>?div=2-wegweiser</code></td><td>Navigationsspalte</td></tr>
      <tr><td><code>?div=6-bericht&amp;msg=Text&amp;typ=error</code></td><td>Meldungskasten</td></tr>
    </table>

    <h3>km.js – Zm-Shortcuts</h3>
    <pre>kmSucheNeu()      // Suchzettel (Formular + Ergebnisse)
kmListeNeu()      // Alle Einträge, seitenweise
kmWegweiserNeu()  // Navigationsspalte</pre>
    <p>Zettel-JSON-Feld <code>opDiv</code>:</p>
    <pre>{ "quelle": "km", "rezept": "suche", "params": {} }</pre>
  </section>

  <section id="skripte">
    <h2>Shell-Skripte · <code>am.fl.de/sh/</code></h2>
    <table>
      <tr><th>Skript</th><th>Zweck</th></tr>
      <tr><td><code>am_konfig.sh</code></td>
          <td>Pfade zu Am, Zm, Km; DB-Zugangsdaten; Km-API-Pfad</td></tr>
      <tr><td><code>am_hilfe.sh</code></td>
          <td><code>am_log</code> · <code>am_zettel_json</code> ·
              <code>am_zettel_hinzufuegen</code> · <code>am_welt_liste</code></td></tr>
      <tr><td><code>test_zettel.sh</code></td>
          <td>Grundmuster: Erläuterungszettel in Zm einfügen</td></tr>
      <tr><td><code>sh/km/km_zm.sh</code></td>
          <td>Km-Zubringer: legt Km-Wegweiser, Km-Suche, Km-Liste in der Zm-Welt an</td></tr>
    </table>

    <h3>Einbinden</h3>
    <pre>source /home/www/am.fl.de/html/sh/am_konfig.sh
source /home/www/am.fl.de/html/sh/am_hilfe.sh

zettel=$(am_zettel_json --titel "Titel" --inhalt "&lt;p&gt;Text&lt;/p&gt;" --farbe "#d4ffea")
am_zettel_hinzufuegen "$ZM_DATEN/1/willkommen.json" "$zettel"</pre>

    <h3>am_zettel_json – Parameter</h3>
    <table>
      <tr><th>Parameter</th><th>Voreinstellung</th><th>Bedeutung</th></tr>
      <tr><td><code>--titel</code></td><td>Neuer Zettel</td><td>Titel</td></tr>
      <tr><td><code>--inhalt</code></td><td><code>&lt;p&gt;&lt;/p&gt;</code></td><td>HTML-Inhalt</td></tr>
      <tr><td><code>--farbe</code></td><td>#FFFFEE</td><td>Hintergrundfarbe (Hex)</td></tr>
      <tr><td><code>--winkel</code></td><td>0</td><td>Drehung in Grad</td></tr>
      <tr><td><code>--breite / --hoehe</code></td><td>300 / 200</td><td>Größe in px</td></tr>
      <tr><td><code>--stichworte</code></td><td>–</td><td>Komma-getrennte Schlüsselwörter</td></tr>
      <tr><td><code>--link</code></td><td>–</td><td>Klickziel-URL</td></tr>
      <tr><td><code>--op-div</code></td><td>–</td><td>JSON für API-Zettel, z.B. <code>'{"quelle":"km","rezept":"suche","params":{}}'</code></td></tr>
    </table>

    <h3>am_zettel_entfernen</h3>
    <pre>am_zettel_entfernen "$ZM_DATEN/1/willkommen.json" "km-zubringer"
# Entfernt alle Zettel deren stichworte-Feld "km-zubringer" enthält</pre>

    <h3>km_zm.sh – Km-Zubringer</h3>
    <pre># Standard: legt 3 Zettel in $KM_ZM_WELT an
bash /home/www/am.fl.de/html/sh/km/km_zm.sh

# Andere Zm-Welt als Ziel:
bash /home/www/am.fl.de/html/sh/km/km_zm.sh /pfad/zur/welt.json</pre>
    <p>Legt drei Zettel an (Stichwort <code>km-zubringer</code>, Farbe <code>#d4ffea</code>):</p>
    <table>
      <tr><th>Zettel</th><th>Rezept</th><th>Inhalt</th></tr>
      <tr><td>Km-Wegweiser</td><td><code>wegweiser</code></td><td>Navigationsspalte (240 × 360)</td></tr>
      <tr><td>Km-Suche</td><td><code>suche</code></td><td>Suchformular + Ergebnisse (440 × 480)</td></tr>
      <tr><td>Km-Liste</td><td><code>liste</code></td><td>Alle Einträge seitenweise (480 × 520)</td></tr>
    </table>
    <p>Bei jedem Aufruf werden alte <code>km-zubringer</code>-Zettel zuerst entfernt,
       dann frische angelegt.</p>

    <h3>Zertifikate &amp; nginx</h3>
    <pre>echo "DOMAIN.de" | sudo bash /usr/local/bin/zert.sh
# Voraussetzung: /home/www/DOMAIN/html muss existieren</pre>
  </section>

  <section id="protokoll">
    <h2>Einrichtungs-Protokoll</h2>
    <ul class="schritte">
      <li><span class="datum">2026-06-07</span><span class="check">✓</span>
          <span>DL: Domain <code>am.fl.de</code> → 46.225.170.170 · Ordner · GitHub-Repo
            <a href="https://github.com/delinde/Allesmeister">delinde/Allesmeister</a></span></li>
      <li><span class="datum">2026-06-07</span><span class="check">✓</span>
          <span>CC: HTTPS via <code>zert.sh</code> · nginx · Git init + push (SSH)</span></li>
      <li><span class="datum">2026-06-07</span><span class="check">✓</span>
          <span>CC: Verzeichnisstruktur <code>sh/ js/ vorlagen/ protokoll/</code></span></li>
      <li><span class="datum">2026-06-07</span><span class="check">✓</span>
          <span>CC: <code>am_konfig.sh</code> · <code>am_hilfe.sh</code> ·
            <code>test_zettel.sh</code> · <code>jq</code> installiert</span></li>
      <li><span class="datum">2026-06-07</span><span class="check">✓</span>
          <span>CC: Erläuterungszettel Am + Km in <code>zm/daten/1/willkommen.json</code></span></li>
      <li><span class="datum">2026-06-08</span><span class="check">✓</span>
          <span>CC: <code>km.fl.de/api/div.php</code> – km-API (Suche, Liste, Wegweiser)</span></li>
      <li><span class="datum">2026-06-08</span><span class="check">✓</span>
          <span>CC: <code>zm/km.js</code> v1 · Dispatcher in index.js · Km-Suche-Zettel in Zm</span></li>
      <li><span class="datum">2026-06-08</span><span class="check">✓</span>
          <span>CC: Zm-Proxy erweitert (<code>$_apiZiele</code>) ·
            GitHub: <a href="https://github.com/delinde/Katalogmeister">delinde/Katalogmeister</a></span></li>
      <li><span class="datum">2026-06-08</span><span class="check">✓</span>
          <span>CC: <a href="https://am.fl.de">am.fl.de</a> – Dokumentationsseite</span></li>
      <li><span class="datum">2026-06-08</span><span class="check">✓</span>
          <span>CC: <code>sh/km/km_zm.sh</code> – Km-Zubringer (Wegweiser · Suche · Liste) ·
            <code>am_hilfe.sh</code>: <code>--op-div</code> + <code>am_zettel_entfernen</code></span></li>
    </ul>

    <h3>Git-Log (Allesmeister)</h3>
    <div class="git-log"><?= nl2br(htmlspecialchars(trim($git_log))) ?></div>
  </section>

</main>

<footer>
  <span>Allesmeister · fl.de · <?= $datum ?></span>
  <span>
    <a href="https://github.com/delinde/Allesmeister">GitHub</a> ·
    <a href="https://zm.fl.de">Zettelmeister</a> ·
    <a href="https://km.fl.de">Katalogmeister</a>
  </span>
</footer>

</body>
</html>
