# 

![Header](docs/header.png)

Ein Projekt von:

- [@JuKa2023](https://github.com/JuKa2023)
- [@SophiaIseli](https://github.com/SophiaIseli)

## Kurzer Beschrieb

Fragly ist eine webbasierte App, mit der Nutzerinnen und Nutzer persönliche Fragen in Form eines Steckbriefs beantworten und in privaten Gruppen miteinander teilen können. Ob bei Hochzeiten, Familienfeiern oder im Alltag – Fragly macht es leicht, Gemeinsamkeiten zu entdecken, Gespräche anzuregen und sich auf neue Weise kennenzulernen.

Jede Gruppe erhält einen einheitlichen Fragenkatalog, der wie ein Fragebogen ausgefüllt wird. Gruppenmitglieder können die Antworten der anderen einsehen und so interessante Verbindungen und Gesprächsthemen entdecken. Alles bleibt digital, einfach und persönlich.

Fragly unterstützt dabei, sich nicht alles merken zu müssen. Vor einem Familientreffen oder einem Fest kann man bequem die Eckdaten, Interessen oder Geschichten der anderen nachlesen und so gut vorbereitet ins Gespräch starten.

## Features
- Registrierung und Login mit persönlichem Benutzerkonto
- Erstellen und Beitreten von Gruppen über einen Einladungslink
- Einheitlicher Fragenkatalog für alle Gruppenmitglieder
- Beantworten der Fragen im Stil eines digitalen Steckbriefs
- Einsehen der Antworten anderer Gruppenmitglieder
- Übersichtliche Darstellung der Gruppen und Profile
- Individuelle Profilseite mit persönlichen Angaben
- Einfache und verständliche Benutzeroberfläche für alle Altersgruppen

## Verwendete Technologien und API

- Backend: PHP für Datenverarbeitung und API-Entwicklung

- Datenbank: MariaDB für effiziente Datenspeicherung und -verwaltung

- Frontend:
  - Vue.js für die Struktur der Webseite
  - Tailwind CSS für responsives und modernes Design
  - JavaScript für interaktive Elemente

## Datenbankstruktur

Das Projekt verwendet ein relationales Datenbankschema mit zwei Haupttabellen:


## Setup

Für die Einrichtung des Projekts empfehlen wir die Verwendung von Docker, da dies die Installation und Konfiguration aller benötigten Komponenten vereinfacht:

1. Installation von Docker und Docker Compose (falls noch nicht vorhanden)
2. Umgebungsdatei kopieren und anpassen:

   ```bash
   cp .env.example .env
   ```

3. Die `.env` Datei mit den gewünschten Konfigurationswerten anpassen
4. Docker-Container starten:

   ```bash
   docker-compose up -d
   ```

5. Die Anwendung ist unter `http://localhost:8080` erreichbar

Das Docker-Setup beinhaltet:

- PHP 8.2 mit Apache
- MySQL 8.0 Datenbank
- Persistente Datenspeicherung
- Umgebungsvariablen-Konfiguration
- Netzwerkisolation zwischen den Diensten

### Umgebungsvariablen

Die Anwendung verwendet folgende Umgebungsvariablen:

- `DB_HOST`: Datenbank-Host (Standard: mysql)
- `DB_NAME`: Datenbankname (Standard: im3)
- `DB_USER`: Datenbankbenutzer (Standard: root)
- `DB_PASSWORD`: Datenbankpasswort
- `DB_ROOT_PASSWORD`: Root-Passwort
- `APP_ENV`: Anwendungsumgebung (Entwicklung/Produktion)
- `APP_DEBUG`: Debug-Modus (true/false)

## Reflektion

Die Arbeit an Fragly war eine spannende und vielseitige Erfahrung. Besonders der konzeptionelle Teil des Projekts hat mir viel Freude bereitet, von der Ideenfindung bis hin zur Ausarbeitung der Nutzerführung. Die Vorstellung, dass Menschen in Gruppen durch persönliche Antworten ins Gespräch kommen, hat mich von Anfang an begeistert.

Zu Beginn lag ein grosser Fokus auf der Zielgruppenanalyse. Es war sehr interessant zu sehen, wie Menschen über 50 auf digitale Steckbrief-Fragen reagieren und welche Bedürfnisse und Hürden sie mitbringen. Die Interviews haben wichtige Einblicke gegeben, die wir direkt in die Gestaltung des Fragebogens und der Benutzeroberfläche einbringen konnten. Die Erstellung der Personas hat ebenfalls geholfen, unsere App menschlich greifbar zu machen.

Besonders kreativ fanden wir, die Idee mit der Steckbrief-Logik spielerisch weiterzudenken. Daraus entstand das Grundkonzept, das auch bei Anlässen wie Hochzeiten eingesetzt werden kann, um auf charmante Weise Gemeinsamkeiten zu entdecken. Das visuelle Gestalten des Mockups hat ebenfalls Spass gemacht, weil dabei genau überlegt werden musste, wie man die App auch für ältere Menschen zugänglich, verständlich und freundlich gestaltet.

Die Zusammenarbeit im Team hat gut funktioniert. Unsere unterschiedlichen Stärken haben sich gut ergänzt und wir konnten viel von einander profitieren. (Vor allem ich von Julie lol)

### Learnings
- Eine gute Zielgruppenanalyse kann ein Projekt von Grund auf prägen
- Interviews liefern nicht nur Fakten, sondern auch Empathie für die Nutzerinnen und Nutzer
- UX-Design muss immer wieder getestet und angepasst werden – einfache Ideen sind oft die besten
- Die klare Strukturierung von Inhalten (wie in einem Fragenkatalog) kann helfen, eine App zugänglicher zu machen
- Mockups sind nicht nur Deko, sondern ein wichtiges Kommunikationsmittel im Team

### Schwierigkeiten
- Es war teilweise herausfordernd, die App so zu denken, dass sie für alle Altersgruppen verständlich ist und ohne sie zu überladen
- Es fiel nicht immer leicht, abzuschätzen, welche Fragen wichtig, zu privat oder zu belanglos sind
- Bei der Konzeption mussten wir oft Dinge streichen oder vereinfachen, die wir eigentlich gerne eingebaut hätten
- Die visuelle Gestaltung auf Figma war anfangs etwas zeitintensiv, bis das Layout rund wirkte

### Benutzte Ressourcen

Während der Entwicklung stiessen wir auf technische Herausforderungen. In solchen Fällen griffen wir auf die Notizen aus dem Unterricht zurück. Wenn uns das Kursmaterial nicht weiterhalf, griffen wir auf [ChatGPT](https://chat.openai.com/) und [Vue.js Dokumentation](https://vuejs.org/guide/introduction.html) zurück, um Lösungen für Codeprobleme zu finden und uns bei Unklarheiten in der Programmierung zu unterstützen. Diese Vorgehensweise trug wesentlich zur Effizienz und Qualität des Entwicklungsprozesses bei. Natürlich gab es auch immer wieder Punkte, wo wir lieber auf menschliche Hilfe zurückgreifen wollten. In diesen Fällen erhielten wir Unterstützung durch unsere Dozenten oder Freunde, die in der Programmierwelt eingebettet sind.

Für Design-Inspirationen haben wir häufig die Seite CodePen [CodePen](https://codepen.io/) genutzt. Diese Plattform erfordert jedoch eine gewisse Menge an Fachjargon und Vorwissen, um die gewünschten Ergebnisse zu finden. Es ist wichtig, klar anzugeben, mit welchen Technologien man arbeitet und ob man bestimmte Frameworks verwendet oder nicht, um relevante und nützliche Beispiele zu finden. Für Pragen bezüglich des Designs mit Tailwind, griffen wir auf die offizielle Entwicklerseite zurück [TailwindCSS](https://tailwindcss.com/).

### Erweiterungsmöglichkeiten
Die aktuelle Version von Fragly konzentriert sich auf das Ausfüllen eines persönlichen Fragenkatalogs innerhalb von Gruppen. In Zukunft könnte die App durch folgende Funktionen erweitert werden:

- Gemeinsamkeiten entdecken auf einen Blick:
  Nutzerinnen und Nutzer könnten Interessen durch einfache Klicks auf Icons oder Stichworte angeben (zum Beispiel Natur, Kochen, Sport, Tiere, Reisen). Die App zeigt dann 
  automatisch an, wer innerhalb der Gruppe dieselben Interessen markiert hat. So entstehen direkte Gesprächsanlässe und Verbindungen.
- Antwort-Vergleiche und Filter:
  Man könnte eine Funktion einbauen, mit der man Antworten nach Themen oder Gemeinsamkeiten filtern kann. Zum Beispiel: "Zeig mir alle, die gerne wandern" oder "Wer hat 
  diese Frage ähnlich beantwortet wie ich?"
- Privatsphäre-Einstellungen pro Frage:
  Nutzerinnen und Nutzer könnten einstellen, ob bestimmte Antworten nur für ausgewählte Gruppenmitglieder sichtbar sein sollen oder ob sie privat bleiben.
- Antwort-Benachrichtigungen:
  Man könnte optional Benachrichtigungen erhalten, wenn ein neues Gruppenmitglied eine Antwort veröffentlicht hat oder wenn jemand eine ähnliche Antwort wie man selbst 
  gegeben hat.
- Visuelle Steckbriefe mit Symbolen oder Tags:
  Zusätzlich zu Textantworten könnten visuelle Elemente wie kleine Icons, Farben oder Interessen-Tags eingesetzt werden, um Profile noch schneller erfassbar zu machen.
- Exportfunktion für Erinnerungen:
  Antworten oder ganze Steckbriefe könnten als PDF gespeichert oder ausgedruckt werden – ideal zum Archivieren oder Verschenken bei Familienanlässen.
- Stimmungsfragen oder Tagesfragen:
  Optional könnten spontane, wechselnde Fragen eingebaut werden, um auch aktuelle Gedanken oder Gefühle sichtbar zu machen.
### Bugs

