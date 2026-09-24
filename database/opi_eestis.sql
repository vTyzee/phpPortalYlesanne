-- ÕpiEestis: sõltumatu andmebaas, ei muuda Newsportali andmeid.
-- Impordi phpMyAdminis (SQL loob andmebaasi ise).
CREATE DATABASE IF NOT EXISTS `opi_eestis` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `opi_eestis`;

CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(70) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('user','admin') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS subjects (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(90) NOT NULL,
 slug VARCHAR(90) NOT NULL UNIQUE,
 description VARCHAR(255) NOT NULL,
 icon VARCHAR(10) NOT NULL,
 tone ENUM('green','lilac','blue','peach') NOT NULL DEFAULT 'green',
 sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS lessons (
 id INT AUTO_INCREMENT PRIMARY KEY,
 subject_id INT NOT NULL,
 author_id INT NULL,
 title VARCHAR(255) NOT NULL,
 summary VARCHAR(320) NOT NULL,
 body TEXT NOT NULL,
 level ENUM('Algaja','Kesktase','Edasijõudnu') NOT NULL DEFAULT 'Algaja',
 duration_min SMALLINT UNSIGNED NOT NULL DEFAULT 10,
 cover_image MEDIUMBLOB NULL,
 cover_mime VARCHAR(30) NULL,
 published TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_lessons_subject FOREIGN KEY (subject_id) REFERENCES subjects(id),
 CONSTRAINT fk_lessons_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
 INDEX idx_lesson_subject (subject_id,published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quiz_questions (
 id INT AUTO_INCREMENT PRIMARY KEY,
 lesson_id INT NOT NULL,
 question VARCHAR(500) NOT NULL,
 CONSTRAINT fk_question_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quiz_options (
 id INT AUTO_INCREMENT PRIMARY KEY,
 question_id INT NOT NULL,
 option_text VARCHAR(300) NOT NULL,
 is_correct TINYINT(1) NOT NULL DEFAULT 0,
 UNIQUE KEY uq_option_text (question_id,option_text(150)),
 CONSTRAINT fk_option_question FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quiz_attempts (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 lesson_id INT NOT NULL,
 correct_count SMALLINT UNSIGNED NOT NULL,
 total_count SMALLINT UNSIGNED NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_attempt_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_attempt_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
 INDEX idx_attempt_user (user_id,lesson_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS lesson_comments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 lesson_id INT NOT NULL,
 user_id INT NOT NULL,
 body VARCHAR(1200) NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_comment_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
 CONSTRAINT fk_comment_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO subjects (id,name,slug,description,icon,tone,sort_order) VALUES
(1,'Eesti keel','eesti-keel','Sõnavara, grammatika ja igapäevased väljendid.','Aa','green',1),
(2,'Matemaatika','matemaatika','Selged selgitused ja praktilised ülesanded.','∑','lilac',2),
(3,'Inglise keel','inglise-keel','Kasulikud väljendid ja keeleharjutused.','En','blue',3),
(4,'Informaatika','informaatika','Digioskused ja programmeerimise alused.','</>','peach',4);

INSERT IGNORE INTO lessons (id,subject_id,title,summary,body,level,duration_min,published) VALUES
(1,1,'Tervitused ja viisakusväljendid','Õpi, kuidas eesti keeles tervitada, tänada ja viisakalt vestlust alustada.','Eesti keeles öeldakse hommikul „Tere hommikust!“, päeva jooksul „Tere!“ ja õhtul „Tere õhtust!“.\n\nViisakad väljendid aitavad suhtlust alustada: „Palun“ tähendab please ning „Aitäh“ tähendab thank you.\n\nNäited:\nTere! Kuidas läheb?\nHästi, aitäh! Aga sinul?\nKa minul läheb hästi.\n\nHarjuta: tervita täna kedagi eesti keeles ja küsi, kuidas tal läheb.', 'Algaja',8,1),
(2,1,'Minu päev: lihtsad laused','Harjuta igapäevastest tegevustest rääkimist lühikeste eestikeelsete lausetega.','Lihtne lause vastab küsimusele: kes teeb mida?\n\nNäited:\nMa ärkan kell seitse.\nMa lähen kooli.\nPärast kooli teen kodutöid.\nÕhtul loen raamatut.\n\nSõnad: hommik, päev, õhtu, kool, kodu.\n\nHarjuta: kirjuta neli lauset oma tavalisest päevast.', 'Algaja',10,1),
(3,2,'Protsendi leidmine','Saa aru protsendist ning arvuta lihtsalt, kui palju moodustab üks osa tervikust.','Protsent tähendab sajandikku. Näiteks 25% tähendab 25 osa sajast ehk 0,25.\n\nEt leida 20% arvust 80, korruta 80 arvuga 0,20. Saad 16.\n\nNäide hinnasoodustusest: kui 50-eurone toode on 10% soodsam, siis soodustus on 5 eurot ja uus hind 45 eurot.\n\nHarjuta: leia 15% arvust 200 ja kontrolli oma vastust testiga.', 'Algaja',12,1),
(4,2,'Lineaarvõrrandi lahendamine','Õpi lahendama võrrandeid, kus tundmatu esineb esimeses astmes.','Lineaarvõrrand on näiteks 2x + 3 = 11.\n\n1. Lahuta mõlemalt poolelt 3: 2x = 8.\n2. Jaga mõlemad pooled 2-ga: x = 4.\n3. Kontroll: 2 · 4 + 3 = 11.\n\nVõrrandi mõlemal poolel tuleb teha sama tehe, et võrdsus säiliks.\n\nHarjuta: lahenda võrrand 3x - 6 = 15.', 'Kesktase',14,1),
(5,3,'Everyday English: greetings','Learn essential greetings and short phrases for everyday conversations in English.','Common greetings are: Hello! Hi! Good morning! Good evening!\n\nTo ask about someone, say: How are you?\nA simple answer is: I am fine, thank you. How about you?\n\nFor polite requests use please, and for gratitude use thank you.\n\nPractice: write a short dialogue with a greeting, a question and a polite goodbye.', 'Algaja',8,1),
(6,3,'Asking for directions','Learn how to ask for directions and understand simple answers while travelling.','To ask for help, say: Excuse me, where is the library?\n\nUseful directions:\nGo straight ahead.\nTurn left at the next street.\nTurn right after the traffic lights.\nIt is next to the station.\n\nPractice: describe the route from your home to a nearby shop in three English sentences.', 'Kesktase',10,1),
(7,4,'Turvaline parool ja digihügieen','Õpi looma tugevaid paroole ning hoidma oma kontosid ja seadmeid turvalisena.','Tugev parool on pikk ja ainulaadne. Ära kasuta sama parooli mitmes teenuses.\n\nKasuta võimalusel paroolihaldurit ja lülita sisse kaheastmeline autentimine.\n\nÄra ava kahtlaseid linke ega jaga oma paroole sõpradega. Enne sisse logimist kontrolli veebilehe aadressi.\n\nHarjuta: mõtle, millistel oma kontodel võiksid turvalisust parandada.', 'Algaja',9,1),
(8,4,'HTML-i põhitõed','Tutvu veebilehe struktuuri ja kõige olulisemate HTML-elementidega.','HTML kirjeldab veebilehe sisu ja struktuuri.\n\nPealkiri märgitakse elemendiga h1 ja lõik elemendiga p. Lingiks kasutatakse elementi a.\n\nHTML ei ole programmeerimiskeel: see on märgistuskeel. Välimuse kujundamiseks kasutatakse enamasti CSS-i.\n\nHarjuta: loo lihtne HTML-dokument, kus on üks pealkiri, kaks lõiku ja üks link.', 'Algaja',12,1);

INSERT IGNORE INTO quiz_questions (id,lesson_id,question) VALUES
(1,1,'Kuidas ütled eesti keeles „thank you“?'),(2,1,'Milline väljend sobib hommikuseks tervituseks?'),(3,1,'Mida võiksid küsida pärast „Tere!“ ütlemist?'),
(4,2,'Milline lause räägib õhtusest tegevusest?'),(5,2,'Mis sõna tähendab eesti keeles „school“?'),(6,2,'Kuidas ütled „I go home“?'),
(7,3,'Kui palju on 20% arvust 80?'),(8,3,'Mida tähendab 25%?'),(9,3,'Kui 50 € hinnast vähendatakse 10%, mis on uus hind?'),
(10,4,'Lahenda: 2x + 3 = 11.'),(11,4,'Mis on järgmine samm pärast 3 lahutamist võrrandist 2x + 3 = 11?'),(12,4,'Kuidas kontrollid võrrandi lahendit?'),
(13,5,'Which phrase is a greeting?'),(14,5,'How do you answer “How are you?”'),(15,5,'Which phrase expresses gratitude?'),
(16,7,'Milline parool on turvalisem?'),(17,7,'Mida teeb kaheastmeline autentimine?'),(18,7,'Mida teha kahtlase sisselogimislingiga?'),
(19,8,'Mida kirjeldab HTML?'),(20,8,'Millist elementi kasutatakse lehe pealkirja jaoks?'),(21,8,'Mida kasutatakse lehe välimuse kujundamiseks?');

INSERT IGNORE INTO quiz_options (question_id,option_text,is_correct) VALUES
(1,'Palun',0),(1,'Aitäh',1),(1,'Tere',0),
(2,'Tere hommikust!',1),(2,'Head ööd!',0),(2,'Nägemist!',0),
(3,'Kuidas läheb?',1),(3,'Mis kell on?',0),(3,'Head aega!',0),
(4,'Ma ärkan kell seitse.',0),(4,'Õhtul loen raamatut.',1),(4,'Ma lähen kooli.',0),
(5,'Kodu',0),(5,'Kool',1),(5,'Päev',0),
(6,'Ma lähen koju.',1),(6,'Ma loen raamatut.',0),(6,'Ma ärkan.',0),
(7,'8',0),(7,'16',1),(7,'20',0),
(8,'25 osa sajast',1),(8,'25 osa kümnest',0),(8,'25 korda sada',0),
(9,'40 €',0),(9,'45 €',1),(9,'55 €',0),
(10,'x = 4',1),(10,'x = 5',0),(10,'x = 7',0),
(11,'2x = 8',1),(11,'2x = 14',0),(11,'x = 8',0),
(12,'Asendan x-i leitud arvuga algvõrrandis.',1),(12,'Muudan ainult vasakut poolt.',0),(12,'Jätan vastuse kontrollimata.',0),
(13,'Good morning!',1),(13,'Turn left.',0),(13,'Next street.',0),
(14,'I am fine, thank you.',1),(14,'Next to the station.',0),(14,'At seven o’clock.',0),
(15,'Thank you!',1),(15,'Go straight.',0),(15,'How about you?',0),
(16,'Pikk ja ainulaadne parool',1),(16,'Sama parool kõigis teenustes',0),(16,'Oma nimi ja sünniaasta',0),
(17,'Lisab sisselogimisel teise kontrolli',1),(17,'Teeb parooli avalikuks',0),(17,'Eemaldab vajaduse ettevaatuseks',0),
(18,'Kontrollin aadressi ja ei sisesta sinna parooli.',1),(18,'Sisestan kiiresti parooli.',0),(18,'Saadan lingi sõbrale.',0),
(19,'Veebilehe sisu ja struktuuri',1),(19,'Ainult värve',0),(19,'Ainult andmebaasi',0),
(20,'h1',1),(20,'p',0),(20,'a',0),
(21,'CSS-i',1),(21,'SQL-i',0),(21,'PHPMyAdmini',0);
