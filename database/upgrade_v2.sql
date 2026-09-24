-- Ainult juba paigaldatud ÕpiEestis v1 jaoks. Uuel paigaldusel kasuta opi_eestis.sql.
-- Käivita üks kord phpMyAdminis. Algse Newsportali andmebaasi ei muudeta.
USE `opi_eestis`;

CREATE TABLE IF NOT EXISTS lesson_bookmarks (
 user_id INT NOT NULL,
 lesson_id INT NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(user_id,lesson_id),
 CONSTRAINT fk_bookmark_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_bookmark_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Neli lisatundi: igale olemasolevale õppeainele üks uus õppematerjal.
INSERT IGNORE INTO lessons (id,subject_id,title,summary,body,level,duration_min,published) VALUES
(9,1,'Eesti keele käänded igapäevaselt','Õpi märkama, kuidas kohakäänded muudavad lihtsa lause tähendust.','Kohakäänded aitavad vastata küsimustele kus?, kuhu? ja kust?.\n\nNäited:\nMa olen koolis. (kus?)\nMa lähen kooli. (kuhu?)\nMa tulen koolist. (kust?)\n\nSamamoodi: kodus, koju, kodust. Pane tähele, et mõned sõnad muutuvad ebareeglipäraselt.\n\nHarjuta: kirjuta kolm lauset sõnaga „raamatukogu”, vastates küsimustele kus?, kuhu? ja kust?.','Algaja',12,1),
(10,2,'Murrud ja nende liitmine','Harjuta samanimeliste ning erinimeliste murdude liitmist.','Murd näitab, mitmeks võrdseks osaks tervik on jagatud. Näiteks 1/4 tähendab ühte neljandikku.\n\nSamanimelised murrud: 1/4 + 2/4 = 3/4.\nErinimelised murrud: 1/2 + 1/4 = 2/4 + 1/4 = 3/4.\n\nEnne liitmist leia ühine nimetaja. Taanda tulemus võimaluse korral.\n\nHarjuta: arvuta 1/3 + 1/6 ja 2/5 + 1/5.','Algaja',14,1),
(11,3,'Present simple: daily routines','Practise the present simple with short sentences about everyday life.','Use the present simple for habits and routines.\n\nI study every day.\nShe studies every day.\n\nFor he, she and it, the verb usually takes -s or -es. In questions, use do or does: Do you study? Does she study?\n\nIn negative sentences: I do not study on Sundays. She does not study on Sundays.\n\nPractice: write three sentences about your routine and one question using does.','Algaja',12,1),
(12,4,'Turvaline internetiotsing','Õpi hindama veebiallikate usaldusväärsust ja otsima teavet tõhusamalt.','Veebis oleva info kontrollimiseks vaata, kes on autor, millal materjal avaldati ja kas väiteid toetavad kontrollitavad allikad.\n\nVõrdle vähemalt kahte sõltumatut allikat. Ära sisesta tundlikke andmeid tundmatutesse vormidesse. Otsingus aitavad täpsemad märksõnad ja jutumärgid tervikfraasi leidmisel.\n\nHarjuta: leia ühe teema kohta kaks allikat ning kirjuta, miks neid usaldad või mitte.','Algaja',10,1);

-- Iga uue tunni jaoks kolm küsimust; olemasolev English directions sai samuti testi.
INSERT IGNORE INTO quiz_questions (id,lesson_id,question) VALUES
(22,9,'Milline lause vastab küsimusele „kus”?'),
(23,9,'Milline sõna vastab küsimusele „kust”?'),
(24,9,'Kuidas öelda, et liigud koju?'),
(25,10,'Kui palju on 1/4 + 2/4?'),
(26,10,'Milline on murdude 1/2 ja 1/4 ühine nimetaja?'),
(27,10,'Kui palju on 1/3 + 1/6?'),
(28,11,'Which sentence uses the correct present simple form?'),
(29,11,'Which word begins a present simple question with „she”?'),
(30,11,'Which sentence is a correct negative?'),
(31,12,'Mis aitab veebiallika usaldusväärsust kontrollida?'),
(32,12,'Mida tähendab otsingus fraasi panemine jutumärkidesse?'),
(33,12,'Mida ei tohi tundmatusse veebivormi sisestada?'),
(34,6,'How do you politely ask for directions?'),
(35,6,'What does „Turn left” mean?'),
(36,6,'Which sentence means „mine otse”?');

INSERT IGNORE INTO quiz_options (question_id,option_text,is_correct) VALUES
(22,'Ma olen koolis.',1),(22,'Ma lähen kooli.',0),(22,'Ma tulen koolist.',0),
(23,'Kodus',0),(23,'Koju',0),(23,'Kodust',1),
(24,'Ma lähen koju.',1),(24,'Ma olen kodus.',0),(24,'Ma tulen kodust.',0),
(25,'3/4',1),(25,'3/8',0),(25,'2/4',0),
(26,'4',1),(26,'2',0),(26,'8 on ainus võimalik nimetaja',0),
(27,'1/2',1),(27,'2/9',0),(27,'2/6',0),
(28,'She studies every day.',1),(28,'She study every day.',0),(28,'She studying every day.',0),
(29,'Does',1),(29,'Do',0),(29,'Did always',0),
(30,'She does not study on Sundays.',1),(30,'She do not study on Sundays.',0),(30,'She not study on Sundays.',0),
(31,'Autor, kuupäev ja kontrollitavad allikad',1),(31,'Ainult ilus kujundus',0),(31,'Väga suur pealkiri',0),
(32,'Otsitakse täpset sõnaühendit',1),(32,'Otsitakse ainult pilte',0),(32,'Kustutatakse otsinguajalugu',0),
(33,'Paroole ja muid tundlikke andmeid',1),(33,'Õppeaine nime',0),(33,'Avalikke õpikute pealkirju',0),
(34,'Excuse me, where is the library?',1),(34,'Go straight ahead.',0),(34,'I am fine.',0),
(35,'Pööra vasakule',1),(35,'Pööra paremale',0),(35,'Mine otse',0),
(36,'Go straight ahead.',1),(36,'Turn left.',0),(36,'Turn right.',0);
