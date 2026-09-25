# TESTIMISPLAAN JA TESTIMISE TULEMUSED

**Projekt:** ÕpiEestis  
**Keskkond:** Windows, XAMPP, PHP 8.1+, Apache, MySQL/MariaDB

## 1. PHP smoke-kontrollid

Käivita projekti juurkaustast:

```powershell
C:\xampp\php\php.exe tests\smoke.php
```

`tests/smoke.php` kontrollib ilma brauseri ja andmebaasita muu hulgas testipunktide arvutamist, vastusepõhist tagasisidet, HTML-teksti kodeerimist, rollide abifunktsioone, CSRF-tokeni kontrolli ja URL-i koostamist.

## 2. Selenium E2E / UI testid

E2E-testid asuvad kaustas `tests/e2e/` ja juhivad päris Edge'i või Chrome'i brauserit Selenium WebDriveriga. Need testivad rakendust kasutaja vaatenurgast läbi HTTP, PHP-koodi ja MySQL-andmebaasi kuni brauseris kuvatava tulemuseni.

Käivita:

```powershell
powershell -ExecutionPolicy Bypass -File tests\e2e\run_e2e.ps1
```

Eeldused: Apache ja MySQL töötavad, `opi_eestis` andmebaas on imporditud ning leht avaneb aadressil `http://localhost/opi_eestis/`.

Automatiseeritud UI stsenaariumid:

| ID | Seleniumi stsenaarium | Oodatav tulemus |
| --- | --- | --- |
| E01 | Ava avaleht ja kontrolli peamenüüd. | ÕpiEestis, TOP 3 ning peamised navigatsioonilingid kuvatakse. |
| E02 | Otsi „Present simple” ning filtreeri Matemaatika järgi. | Otsing leiab õige materjali; ainefilter kuvab Matemaatika materjalid. |
| E03 | Ava õppetund, käivita test, vali igas küsimuses vastus ja esita vorm. | Kuvatakse punktisumma ja vastuste ülevaade. |
| E04 | Ava registreerimise ja sisselogimise vaated. | Kõik vajalikud väljad ja nupud on nähtavad. |
| E05 | Logi adminina sisse ja ava õppematerjalide haldus. | Halduspaneel, materjalide tabel ning Muuda/Test/Kustuta tegevused on nähtavad. |
| E06 | Lisa adminina unikaalne testkommentaar ja kustuta see. | Kommentaar ilmub ning pärast Kustuta toimingut kaob. |

E05 ja E06 vajavad admini testkontot. Määra tunnused ainult lokaalse sessiooni keskkonnamuutujates:

```powershell
$env:E2E_ADMIN_EMAIL="admin@example.ee"
$env:E2E_ADMIN_PASSWORD="PAROOL"
powershell -ExecutionPolicy Bypass -File tests\e2e\run_e2e.ps1
```

Kui admini muutujaid pole, märgib Selenium need kaks testi `skipped`; avalikud E01–E04 testid töötavad edasi. Parooli ei lisata GitHubi.

Brauser on vaikimisi nähtav, seega saab teste õpetajale reaalajas demonstreerida. Ebaõnnestunud testi ekraanipilt salvestatakse lokaalselt kausta `tests/e2e/screenshots/`, mis on `.gitignore` failis.

## 3. Käsitsi vastuvõtutestid

| ID | Tegevus | Oodatav tulemus |
| --- | --- | --- |
| T01 | Paki projekt lahti ning ava `http://localhost/opi_eestis/`. | Avaleht avaneb, must-valge-roheline menüü on nähtav. |
| T02 | Impordi `opi_eestis.sql` tühja keskkonda. | Tekib `opi_eestis` andmebaas ja vajalikud tabelid. |
| T03 | Ava avalehe üks materjal. | Õppetunni tekst ja info avanevad. |
| T04 | Kasuta otsingut, õppeaine- ja tasemefiltrit. | Loend vastab valikutele. |
| T05 | Registreeri uus kasutaja. | Kasutaja roll on `user` ja halduspaneeli linki ei kuvata. |
| T06 | Logi sisse vale ja seejärel õige parooliga. | Vale parool ei ava kontot; õige parool avab õpitee. |
| T07 | Lisa sisseloginuna kommentaar. | Kommentaar ilmub õppetunni arutelusse. |
| T08 | Tee test külalisena. | Tulemus ja õiged vastused kuvatakse, kontole tulemust ei salvestata. |
| T09 | Tee test sisselogitud kasutajana ja ava „Minu õpitee”. | Tulemus salvestub kasutaja testiajalukku. |
| T10 | Salvesta ja eemalda õppematerjal. | Materjal lisandub õpiteele ja eemaldub sealt. |
| T11 | Ava admini URL tavakasutajana. | Haldus ei ole tavakasutajale ligipääsetav. |
| T12 | Logi adminina sisse. | Halduspaneel avaneb. |
| T13 | Lisa, muuda, peida ja kustuta õppematerjal. | CRUD-toimingud töötavad ning avaldamise staatus mõjutab avalikku vaadet. |
| T14 | Lisa ja kustuta testiküsimus. | Küsimus ilmub testis ning kustutamisel kaob. |
| T15 | Kustuta kommentaar adminina. | Kommentaar kaob; tavakasutajal kustutamisnuppu ei ole. |
| T16 | Proovi vigast CSRF-tokenit. | Muutmistoiming blokeeritakse. |
| T17 | Ava projekt väikese brauseriaknaga. | Sisu jääb kasutatavaks ja loetavaks. |

## 4. Koodikate

Smoke-testid ja Seleniumi E2E-testid **ei ole sama asi mis koodikate**. Üle 60% koodikatte nõude tõendamiseks tuleb PHP-koodi coverage eraldi mõõta näiteks PHPUnit + Xdebug/PCOV abil ning säilitada mõõdetud raport. Enne raporti loomist ei märgita `>60%` nõuet täidetuks.
