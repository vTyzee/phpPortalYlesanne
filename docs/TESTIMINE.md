# TESTIMISPLAAN JA TESTIMISE TULEMUSED

**Projekt:** ÕpiEestis | **Keskkond:** Windows, XAMPP, PHP 8.1+, Apache, MySQL/MariaDB

## 1. Automatiseeritud kontrollid

Käivita terminalis projekti juurkaustast:

```powershell
C:\xampp\php\php.exe tests\smoke.php
```

Skript kontrollib ilma andmebaasita testipunktide arvutamist, vastusepõhist tagasisidet, HTML-teksti kodeerimist, rollide abifunktsioone, CSRF-tokeni kontrolli ja URL-i koostamist. Käesoleva paketi loomisel läbis **25/25 kontrolli**; samuti ei esinenud kohaliku PHP CLI süntaksikontrollis vigu. See **ei ole** MySQL-integratsioonitesti tulemus.

**Tähtis:** 25 läbitud kontrolli **ei tõenda üle 60% koodikatet**. Koodikatte mõõtmiseks paigalda XAMPP-i sobiva PHP-versiooni Xdebug või PCOV ning PHPUnit; kirjuta DB-d kasutavatele klassidele ja kontrolleritele automatiseeritud integratsioonitestid. Kuni mõõdetud raportini märgi nõue „>60%” täitmata/ootel.

## 2. Käsitsi vastuvõtutestid sinu XAMPP-is

Iga testi puhul kirjuta üles: kuupäev, testija, tulemus OK / VIGA / POLE TESTITUD ning vajadusel ekraanipilt või paranduse kommiti ID. Järgmisi samme ei ole koostamiskeskkonnas automaatselt läbi mängitud.

| ID | Tegevus | Oodatav tulemus |
| --- | --- | --- |
| T01 | Paki ZIP lahti ning ava `http://localhost/opi_eestis/`. | Avaleht avaneb, must-valge-roheline menüü on nähtav. |
| T02 | Impordi `opi_eestis.sql` tühja keskkonda. | Tekib eraldi `opi_eestis` DB ja kõik tabelid; Newsportali DB jääb muutmata. |
| T03 | Ava avalehe üks kolmest materjalist. | Õppetunni tekst ja link avanevad. |
| T04 | Vali üks õppeaine, kasuta otsingut ja tasemefiltrit. | Loend vastab valikutele. |
| T05 | Registreeri uus kasutaja. | `users.role = 'user'`, admini halduslinki ei ilmu. |
| T06 | Logi sisse vale parooliga ja siis õigega. | Vale parool ei ava kontot; õige parool avab õpitee. |
| T07 | Lisa sisseloginuna kommentaar. | Kommentaar ilmub õppetunni alla õigete kasutajaandmetega. |
| T08 | Tee test külalisena (õiged ja valed vastused). | Tulemus ja iga küsimuse õige vastus kuvatakse, tulemust ei salvestata kontole. |
| T09 | Tee test õpilasena ja ava „Minu õpitee”. | Punktisumma ja testiajalugu salvestatakse õige kasutaja alla. |
| T10 | Salvesta ja eemalda õppematerjal. | Materjal lisandub õpiteele ja eemaldub sealt, teise konto loendis seda pole. |
| T11 | Ava admini URL tavalise kasutajaga. | Haldus ei ole ligipääsetav. |
| T12 | Loo admin CLI abil ning logi sisse. | Halduspaneel avaneb ja näitab materjalide haldust. |
| T13 | Lisa õppematerjal, kaanepilt, muuda seda ja peida. | Salvestatud materjal ja pilt kuvatakse; peidetud materjali külaline ei näe. |
| T14 | Lisa ja kustuta testiküsimus. | Küsimus ilmub testis ning kustutamisel kaob. |
| T15 | Kustuta kommentaar adminina õppematerjali arutelust. | Kommentaar kaob ning tavakasutajal kustutamisnuppu ei ole. |
| T16 | Kustuta testmaterjal adminina. | Õppematerjal ning seotud kommentaarid/testiandmed kaovad. |
| T17 | Proovi vigast CSRF-tokenit kommentaari/haldustoimingu POST-is. | Toiming ei lähe läbi; kuvatakse veateade. |
| T18 | Ava projekt telefonis või väikese brauseriaknaga. | Menüüd ja materjalid on loetavad ilma horisontaalse ülevooluta. |
| T19 | Ava SQL-ita installatsioon. | Ilmub arusaadav andmebaasi veateade, mitte toorelt nähtav SQL/parool. |

## 3. Testimispäevik (täida pärast päris kontrolli)

| Kuupäev | Testija | Testid | Tulemus | Paranduse commit / märkus |
| --- | --- | --- | --- | --- |
| — | — | T01–T19 | POLE TESTITUD | Täida enda Windows/XAMPP keskkonnas. |

## 4. Tulevased automaattestid ja koodikate

Planeeritav töö: PHPUnit-i konfiguratsioon; eraldi testandmebaas; Auth::register/login rollid; Lesson CRUD ja avaldamine; Bookmark isolatsioon eri kontode vahel; Quiz vastuse- ja salvestusvood; CSRF ja URL-i õigused. Koodikatte raportisse kirjuta tööriist, versioon, käsurida, kuupäev ning mõõdetud protsent - enne seda ei tohi märkida >60% nõuet täidetuks.
