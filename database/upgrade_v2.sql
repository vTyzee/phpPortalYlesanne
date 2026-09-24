-- Ainult juba paigaldatud ÕpiEestis v1 jaoks. Uuel paigaldusel kasuta opi_eestis.sql.
-- Käivita üks kord phpMyAdminis. Algse Newsportali andmebaasi ei muudeta.
-- See migratsioon lisab ainult uue tabeli, et olemasolevaid õppematerjale ja nende ID-sid mitte üle kirjutada.
-- Neli lisatundi ja nende testid tulevad värske andmebaasi opi_eestis.sql importimisel.
USE `opi_eestis`;

CREATE TABLE IF NOT EXISTS lesson_bookmarks (
 user_id INT NOT NULL,
 lesson_id INT NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(user_id,lesson_id),
 CONSTRAINT fk_bookmark_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_bookmark_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

