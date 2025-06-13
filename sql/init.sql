SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE nutzer (
user_id int(11) NOT NULL AUTO_INCREMENT,
nutzer varchar(100) DEFAULT NULL,
avatar_url varchar(255) DEFAULT NULL,
email varchar(100) DEFAULT NULL,
password varchar(100) DEFAULT NULL,
erstellt date DEFAULT NULL,
PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE frage (
frage_id int(11) NOT NULL AUTO_INCREMENT,
frage varchar(255) DEFAULT NULL,
antwort_vorschlag text DEFAULT NULL,
reihenfolge int(11) DEFAULT NULL,
erstellt date DEFAULT NULL,
input_type varchar(50) DEFAULT 'text',
PRIMARY KEY (frage_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO frage (frage_id, frage, antwort_vorschlag, reihenfolge, erstellt, input_type) VALUES
(1, 'Wie heisst du?', 'Max Mustermann', 1, '2025-05-16', 'text'),
(2, 'Wann bist du geboren?', '19. Januar 1980', 2, '2025-05-16', 'date'),
(3, 'Hast du Geschwister? Wenn ja, wie viele und wie heissen diese?', 'Ich habe 4 Geschwister, Mark, Seb, Franz und Lisa', 3, '2025-05-16', 'textarea'),
(4, 'Wo wohnst du?', 'Bern', 4, '2025-05-16', 'text'),
(5, 'Wie ist dein Beziehungsstatus?', 'Verheiratet', 5, '2025-05-16', 'text'),
(6, 'Was machst du beruflich oder was studierst/lernst du?', 'Schreiner', 6, '2025-05-16', 'text'),
(7, 'Wo arbeitest du?', 'Fluri Holz AG', 7, '2025-05-16', 'text'),
(8, 'Was sind deine Hobbys?', 'Fussball, Kochen, Wandern', 8, '2025-05-16', 'textarea'),
(9, 'Was ist dein Lieblingsessen?', 'Bohnenintopf nach Grossmamis Art', 9, '2025-05-16', 'text'),
(10, 'Was hörst du gerne für Musik?', 'Am liebsten höre ich Radio, vor allem den Sender Energy', 10, '2025-05-16', 'textarea'),
(11, 'Was ist dein Lieblingsort (z. B. Stadt, Land, Naturort)?', 'Am liebsten bin ich in den Bergen am Sport machen (Wandern, Trailrunning oder Skifahren)', 11, '2025-05-16', 'text'),
(12, 'Was wolltest du schon immer mal jemanden aus der anderen Generation fragen?', 'Könnt ihr Tippen während dem Gehen?', 12, '2025-05-16', 'textarea'),
(13, 'Was möchtest du der anderen Generation über dich erzählen?', 'Wir waren auch mal jung, auch wenns schon lange her ist. Ich verstehe, dass ihr denkt wir sind Uralt.', 13, '2025-05-16', 'textarea'),
(14, 'Hast du Kinder / Enkel / Haustiere? Wie heissen diese?', 'Meinen Hund Lucky und mein Fisch Bubbles', 14, '2025-05-16', 'textarea'),
(15, 'Gibt es etwas, das dich gerade besonders freut oder beschäftigt?', 'Ja, ich freue mich gerade sehr auf die Geburt meines ersten Kindes im Sommer. Gleichzeitig beschäftigt es mich, wie schnell die Zeit vergeht. Es fühlt sich an, als hätte ich gerade erst meine Lehre abgeschlossen.', 15, '2025-05-16', 'textarea');

CREATE TABLE gruppe (
gruppe_id int(11) NOT NULL AUTO_INCREMENT,
name varchar(100) DEFAULT NULL,
link varchar(255) DEFAULT NULL,
ersteller int(11) DEFAULT NULL,
kuerzel varchar(50) DEFAULT NULL,
erstellt date DEFAULT NULL,
loeschdatum date DEFAULT NULL,
PRIMARY KEY (gruppe_id),
KEY ersteller (ersteller),
CONSTRAINT gruppe_ibfk_1 FOREIGN KEY (ersteller) REFERENCES nutzer (user_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE nutzer_frage (
frage_id int(11) NOT NULL,
user_id int(11) NOT NULL,
antwort varchar(255) DEFAULT NULL,
erstellt datetime NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (frage_id,user_id),
CONSTRAINT nutzer_frage_ibfk_1 FOREIGN KEY (frage_id) REFERENCES frage (frage_id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT nutzer_frage_ibfk_2 FOREIGN KEY (user_id) REFERENCES nutzer (user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE nutzer_gruppe (
id int(11) NOT NULL AUTO_INCREMENT,
user_id int(11) DEFAULT NULL,
gruppe_id int(11) DEFAULT NULL,
erstellt datetime NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (id),
KEY user_id (user_id),
KEY gruppe_id (gruppe_id),
CONSTRAINT nutzer_gruppe_ibfk_1 FOREIGN KEY (user_id) REFERENCES nutzer (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT nutzer_gruppe_ibfk_2 FOREIGN KEY (gruppe_id) REFERENCES gruppe (gruppe_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

SET GLOBAL event_scheduler = ON;
CREATE EVENT DeleteExpired
  ON SCHEDULE EVERY 1 MINUTE
  DO
    DELETE FROM gruppe
    WHERE loeschdatum IS NOT NULL AND loeschdatum < NOW();
ALTER EVENT DeleteExpired ENABLE;


COMMIT;