# TEHNILINE ÜLESANNE

**Projekt:** ÕpiEestis - õppematerjalide ja testide portaal  
**Teema:** Õppimine Eestis  
**Versioon:** 1.0 (õppeprojekt, lokaalseks käivitamiseks)  
**Lähteprojekt:** Newsportal (PHP MVC). ÕpiEestis on uus eraldiseisev lahendus.

## 1. Projekti eesmärk ja probleemi kirjeldus

Luua õppijale lihtne veebikeskkond, kus Eesti kontekstis kasutatavaid õppematerjale saab leida õppeaine ja taseme järgi, lugeda ning teadmisi valikvastustega testida. Algse Newsportali kategooriate, uudisartiklite, kommentaaride ja haldusfunktsioonide põhimõte kohandatakse uueks teemaks. Projekt on õppeotstarbeline, mitte Eesti riigi ametlik portaal.

## 2. Sihtrühmad ja rollid

- **Külaline:** saab sirvida õppeaineid, lugeda avaldatud materjale, otsida ja teha testi; tulemust ei salvestata.
- **Õpilane (`user`):** külalise õigused + kommenteerimine, materjali salvestamine, testitulemuste ja salvestatud materjalide vaatamine.
- **Administraator (`admin`):** õpilase õigused + õppematerjalide ja testiküsimuste haldus. Kontot saab luua üksnes käsurea administraatori loomise tööriistaga.

## 3. Funktsionaalsed nõuded ja vastuvõtukriteeriumid

| ID | Nõue | Vastuvõtukriteerium |
| --- | --- | --- |
| F01 | Avalehel kuvatakse kolm viimast avaldatud õppematerjali. | Pealkiri, õppeaine, lühikirjeldus ja link avavad õppetunni. |
| F02 | Õppeained on eraldi kategooriad. | Õppeaine valikul kuvatakse vaid selle avaldatud tunnid. |
| F03 | Õppematerjalide otsing ja tasemefilter. | Pealkirja/sisu otsing ning valitud raskusaste vähendavad loendit. |
| F04 | Õppetunni detailvaade. | Pealkiri, tekst, õppeaine, tase, kestus, vabatahtlik pilt ja arutelu on nähtavad. |
| F05 | Registreerimine ja sisselogimine. | Uuele kontole antakse alati roll `user`; vale parool ei logi sisse. |
| F06 | Kommentaarid ja salvestatud materjalid. | Sisseloginud õppija saab lisada kommentaari ja salvestada/eemaldada enda materjale; administraator saab sobimatu kommentaari kustutada. |
| F07 | Valikvastustega teadmiste kontroll. | Test arvutab punktid, näitab iga küsimuse õiget vastust ja salvestab sisseloginud õppija tulemuse. |
| F08 | Minu õpitee. | Konto lehel kuvatakse tulemuste kokkuvõte, viimased katsed ja kuni 20 salvestatud õppematerjali. |
| F09 | Haldus: õppematerjalide CRUD. | Admin lisab, muudab, avaldab/peidab ja kustutab õppematerjali; user ei saa haldusesse. |
| F10 | Haldus: testiküsimused. | Admin lisab kolme vastusega küsimuse või kustutab küsimuse. Olemasoleva küsimuse muutmine ei ole veel eraldi vormina olemas. |

## 4. Mittefunktsionaalsed nõuded

- **Tehnoloogia:** PHP 8.1+, MySQL/MariaDB, PDO, Apache mod_rewrite; HTML/CSS, ilma kohustusliku JS-raamistikuta.
- **Kujundus:** Newsportalile sarnane jaotustega avaleht/loend/artikkel; kõigis vaadetes must-valge-roheline kujundus; kitsal ekraanil kohanduv paigutus.
- **Turvalisuse alused:** parooliräsi, serveripoolne rollikontroll, vormidel CSRF-kontroll, HTML-väljundi kodeerimine, PDO parameetrid, kuni 2 MB valideeritud pildifail.
- **Käideldavus:** arusaadav veateade puuduva andmebaasi ja mitteolemasoleva lehe korral. Lokaalse Apache/MySQL-i käivitamine on paigalduse osa.
- **Testimine:** olemas on CLI regressioonitestid; andmebaasiga integratsioon vajab käsitsi kontrollimist; >60% automaatset koodikatet pole veel tõendatud.

## 5. Arhitektuur ja töövood

```text
Brauser → Apache/.htaccess → index.php / admin/index.php
         → route/routing.php / admin/AdminController.php
         → controller/Controller.php → model/*.php → inc/db.php → MySQL
         → view/*.php → assets/style.css
```

Avaliku lehe sisenemispunkt ja administraatori sisenemispunkt on eraldi. Mõlemad kasutavad samu kasutaja-, õppematerjali- ja testi mudeleid, ning ühise `view/layout.php` kaudu sama visuaalset kujundust. Seega on Newsportali arhitektuuriline lähtekoht nähtav, kuid sisumudel ja funktsioonid on kohandatud uue teemaga.

## 6. Andmemudel

| Tabel | Ülesanne | Seosed |
| --- | --- | --- |
| `users` | Kontod, e-post, parooliräsi, roll. | Kasutaja võib luua kommentaare, testikatseid ja järjehoidjaid. |
| `subjects` | Õppeained ja menüü kategooriad. | 1:N `lessons`. |
| `lessons` | Õppetunni sisu, tase, kestus, pilt, avaldamise olek. | N:1 `subjects`, võimalik N:1 autor `users`. |
| `quiz_questions` | Küsimused. | N:1 `lessons`. |
| `quiz_options` | Kolm valikut ja õige vastuse märgis. | N:1 `quiz_questions`. |
| `quiz_attempts` | Õpilase testi punktid ja aeg. | N:1 `users` ja N:1 `lessons`. |
| `lesson_comments` | Õpilase arutelu õppetunnis. | N:1 `users` ja N:1 `lessons`. |
| `lesson_bookmarks` | Isiklikud salvestatud õppematerjalid. | `(user_id,lesson_id)` liitprimaarvõti; viited `users`, `lessons`. |

Mõlemad SQL-failid asuvad kaustas `database/`. `opi_eestis.sql` loob **uue** andmebaasi; `upgrade_v2.sql` lisab varasemasse ÕpiEestis andmebaasi salvestatud materjalide tabeli, muutmata olemasolevaid õppematerjale. Vana Newsportali andmebaas jääb eraldi.

## 7. Minimaalne valmiskriteerium

Projekt on kohalikus XAMPP-is kasutatav, kui SQL import õnnestub, avaleht ja 12 näidistundi avanevad, kasutaja roll jääb `user`, admini CRUD toimib, pildid kuvatakse, testitulemused salvestuvad ja õppematerjali salvestamine toimib pärast sisse logimist. Need punktid vajavad kasutaja arvutis käsitsi testimist; neid ei loeta siin automaatselt läbituks.

## 8. Piirangud ja järgmine versioon

Õppeainete admini CRUD, testiküsimuse muutmise vorm, keerukamad ülesandetüübid, põhjalik sisutoimetus ja >60% mõõdetud automaattestide koodikate on järgmise versiooni tööd. Projekt ei kogu päris õpilaste andmeid õppekatseks; väldi päris paroolide ja isikuandmete lisamist näidisandmetesse.
