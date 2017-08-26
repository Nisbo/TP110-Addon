# TP110-Addon

This is my first IPS Addon/Script

Based on 
the "TPLink HS100/HS110 WiFi Smart Plug API" by RobertShippey (GNU General Public License v3.0)
- https://github.com/RobertShippey/hs100-php-api

and the information from the Reverse Engineering the TP-Link HS110 by Lubomir Stroetmann, Consultant and Tobias Esser, Consultant
- https://www.softscheck.com/en/reverse-engineering-tp-link-hs110/

# Installation (German)
Im Objektbaum an gewünschter Stelle eine neue Kategorie hinzufügen (optional, alternativ eine vorhandene Kategorie verwenden)

Objekt hinzufügen --> Kategorie hinzufügen --> OK --> Namen eintragen (z.B. TP110) --> Ort/Position im Objektbaum auswählen --> OK

und 4 Variablen erstellen (3 x Float und 1 x Boolean)

Objekt hinzufügen --> Variable hinzufügen --> OK --> Type **Float** --> Logging (CheckBox markieren wenn geloggt werden soll) --> "weiter >>" --> Namen eintragen (z.B. **tp110Voltage**) --> eben erstellte Kategorie im Objektbaum auswählen --> OK

Objekt hinzufügen --> Variable hinzufügen --> OK --> Type **Float** --> Logging (CheckBox markieren wenn geloggt werden soll) --> "weiter >>" --> Namen eintragen (z.B. **tp110Current**) --> eben erstellte Kategorie im Objektbaum auswählen --> OK

Objekt hinzufügen --> Variable hinzufügen --> OK --> Type **Float** --> Logging (CheckBox markieren wenn geloggt werden soll) --> "weiter >>" --> Namen eintragen (z.B. **tp110Power**) --> eben erstellte Kategorie im Objektbaum auswählen --> OK

Objekt hinzufügen --> Variable hinzufügen --> OK --> Type **Boolean** --> Logging (CheckBox markieren wenn geloggt werden soll) --> "weiter >>" --> Namen eintragen (z.B. **tp110Switch**) --> eben erstellte Kategorie im Objektbaum auswählen --> OK
