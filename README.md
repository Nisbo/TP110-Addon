# TP110-Addon

This is my first IPS Addon/Script

Based on 
the "TPLink HS100/HS110 WiFi Smart Plug API" by RobertShippey (GNU General Public License v3.0)
- https://github.com/RobertShippey/hs100-php-api

and the information from the Reverse Engineering the TP-Link HS110 by Lubomir Stroetmann, Consultant and Tobias Esser, Consultant
- https://www.softscheck.com/en/reverse-engineering-tp-link-hs110/

# Installation (German)
### Im Objektbaum an gewünschter Stelle eine neue Kategorie hinzufügen (optional, alternativ eine vorhandene Kategorie verwenden)

Objekt hinzufügen --> Kategorie hinzufügen --> OK --> Namen eintragen (z.B. **TP110**) --> Ort/Position im Objektbaum auswählen --> OK

### und 4 Variablen im Objektbaum  erstellen (3 x Float und 1 x Boolean)

Objekt hinzufügen --> Variable hinzufügen --> OK --> Type **Float** --> Logging (CheckBox markieren wenn geloggt werden soll) --> "weiter >>" --> Namen eintragen (z.B. **tp110Voltage**) --> Ort (eben erstellte Kategorie im Objektbaum auswählen) --> OK

Objekt hinzufügen --> Variable hinzufügen --> OK --> Type **Float** --> Logging (CheckBox markieren wenn geloggt werden soll) --> "weiter >>" --> Namen eintragen (z.B. **tp110Current**) --> Ort (eben erstellte Kategorie im Objektbaum auswählen) --> OK

Objekt hinzufügen --> Variable hinzufügen --> OK --> Type **Float** --> Logging (CheckBox markieren wenn geloggt werden soll) --> "weiter >>" --> Namen eintragen (z.B. **tp110Power**) --> Ort (eben erstellte Kategorie im Objektbaum auswählen) --> OK

Objekt hinzufügen --> Variable hinzufügen --> OK --> Type **Boolean** --> Logging (CheckBox markieren wenn geloggt werden soll) --> "weiter >>" --> Namen eintragen (z.B. **tp110Switch**) --> Ort (eben erstellte Kategorie im Objektbaum auswählen) --> OK

### Script im Objektbaum erstellen

Objekt hinzufügen --> Script hinzufügen --> OK --> Namen eintragen (z.B. **ScriptTP110**) --> Ort (eben erstellte Kategorie im Objektbaum auswählen) --> OK --> in der sich öffnenden Seite den Inhalt der Datei **module.php** einfügen --> den Config Bereich der module.php an euer System anpassen (Host / ObjektIDs) --> Speichern

Jetzt kann noch ein Ereignis hinzugefügt werden welches das Script regelmäßig aufruft
Auf der selben Seite wo wir eben den Code eingefügt haben oben auf **Ereignis hinzufügen** klicken --> **Zyklisches Ereignis** auswählen --> "weiter >>" --> Zeitmuster **Sekündlich** --> Zeit **15** (was dann alle 15 Sekunden wären) --> "weiter >>" --> OK

# Mir ist bewusst das dies noch keine Goldrandlösung ist und man in IPS vieles noch anders/besser machen könnte, für den Anfang soll es erst einmal reichen, ich werde Änderungen nachschieben

