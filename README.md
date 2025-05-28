# 

![Header](docs/header.png)

Ein Projekt von:

- [@JuKa2023](https://github.com/JuKa2023)
- [@SophiaIseli](https://github.com/SophiaIseli)

Kurzer beschreib

## Features


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

Nachfolgend einige reflektierende Gedanken und Erkenntnisse, die sich aus den Erfahrungen des Projekts ergeben haben:

### Learnings


### Schwierigkeiten


### Benutzte Ressourcen

Während der Entwicklung stiessen wir auf technische Herausforderungen. In solchen Fällen griffen wir auf die Notizen aus dem Unterricht zurück. Wenn uns das Kursmaterial nicht weiterhalf, griffen wir auf [ChatGPT](https://chat.openai.com/) und [Vue.js Dokumentation](https://vuejs.org/guide/introduction.html) zurück, um Lösungen für Codeprobleme zu finden und uns bei Unklarheiten in der Programmierung zu unterstützen. Diese Vorgehensweise trug wesentlich zur Effizienz und Qualität des Entwicklungsprozesses bei. Natürlich gab es auch immer wieder Punkte, wo wir lieber auf menschliche Hilfe zurückgreifen wollten. In diesen Fällen erhielten wir Unterstützung durch unsere Dozenten oder Freunde, die in der Programmierwelt eingebettet sind.

Für Design-Inspirationen haben wir häufig die Seite CodePen [CodePen](https://codepen.io/) genutzt. Diese Plattform erfordert jedoch eine gewisse Menge an Fachjargon und Vorwissen, um die gewünschten Ergebnisse zu finden. Es ist wichtig, klar anzugeben, mit welchen Technologien man arbeitet und ob man bestimmte Frameworks verwendet oder nicht, um relevante und nützliche Beispiele zu finden. Für Pragen bezüglich des Designs mit Tailwind, griffen wir auf die offizielle Entwicklerseite zurück [TailwindCSS](https://tailwindcss.com/).

### Erweiterungsmöglichkeiten

### Bugs

