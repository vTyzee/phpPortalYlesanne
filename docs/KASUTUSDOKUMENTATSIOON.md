# KASUTUSDOKUMENTATSIOON

**ÕpiEestis** | Versioon 1.0 | Lokaalne õppeprojekt

## 1. Kellele on portaal mõeldud?

ÕpiEestis koondab eestikeelsed õppematerjalid õppeainete kaupa. Saad lugeda tunde ja lahendada lühiteste. Tegemist ei ole Eesti riigi ametliku veebiteenusega.

## 2. Esmakäivitus (projekti paigaldaja)

1. Paigalda XAMPP (PHP 8.1+, Apache ja MySQL/MariaDB). Kontrolli PHP mooduleid `pdo_mysql`, `mbstring` ja `fileinfo`.
2. Paki projekt kausta `C:\xampp\htdocs\opi_eestis\`. Veendu, et `index.php` on selles kaustas, mitte järgmises samanimelises kaustas.
3. Käivita Apache ja MySQL. Ava `http://localhost/phpmyadmin/`, vali **Import** ja fail `database/opi_eestis.sql`, seejärel impordi andmed. Vana Newsportali SQL-i siia ei lisata.
4. Ava `http://localhost/opi_eestis/`. Kui versioon v1 on juba paigaldatud, tee andmebaasi varukoopia ning kasuta selle asemel **ühekordselt** `database/upgrade_v2.sql`.

## 3. Külalise tegevused

**Avaleht:** näitab kolme viimast õppematerjali ja õppeainete loendit. Õppeaine valimiseks kasuta ülamenüüd või parempoolset nimekirja. Ava pealkiri või „Loe edasi”.

**Õppematerjalid:** ava „Kõik õppematerjalid”, kasuta otsingu sõna, õppeaine valikut ja raskusastet. Õppetunni lehel saad lugeda näiteid ning vajutada „Alusta testi”.

**Test:** vali igale küsimusele vastus ja vajuta „Kontrolli vastuseid”. Kuvatakse punktisumma ning iga küsimuse õige vastus. Külalise testitulemus ei salvestu.

## 4. Õpilase tegevused

**Konto loomine:** vali „Registreeru”, sisesta kasutajanimi, korrektne e-posti aadress ja vähemalt kaheksa märgiga parool kaks korda. Pärast registreerimist logi sisse. Uus konto on alati tavaline `user`, mitte administraator.

**Sisselogimine:** vali „Logi sisse” ning sisesta e-post ja parool. Kui andmed on valed, kuvatakse veateade. Väljalogimiseks vajuta ülamenüüs „Välju”.

**Kommentaarid:** ava õppematerjal, kirjuta lehe allosas kommentaar ning vajuta „Saada kommentaar”. Kommenteerimiseks on vaja sisse logida.

**Salvestatud õppematerjalid:** ava õppetund ja vajuta „☆ Salvesta õppematerjal”. Sama nupp eemaldab õppematerjali sinu nimekirjast. Sinu salvestatud õppematerjalid on lehel „Minu õpitee” (kuvatakse kuni 20 viimast salvestust).

**Minu õpitee:** siit näed, mitu testi oled proovinud, mitu eri õppetundi on saavutanud vähemalt 70% tulemuse, viimaseid testikatseid ja salvestatud materjale. Külalisena ei saa seda lehte avada.

## 5. Administraatori tegevused

Administraator luuakse üksnes kohalikus terminalis käsuga:

```powershell
C:\xampp\php\php.exe tools\create_admin.php
```

Ära lisa administraatori paroole dokumentatsiooni ega GitHubi. Admin logib sisse sama vormiga nagu õpilane, kuid talle kuvatakse ülamenüüs „Halduspaneel”.

**Õppematerjali lisamine:** Halduspaneel → „Õppematerjalid” → „Lisa õppematerjal”. Sisesta pealkiri, lühikirjeldus, õppetekst, õppeaine, tase ja kestus; soovi korral vali kaanepilt JPG/PNG/GIF/WebP kuni 2 MB. Märgi „Avalda õppematerjal”, kui see peab kohe nähtav olema; vajuta „Salvesta õppematerjal”.

**Muutmine:** vajuta materjali real „Muuda”, tee muudatused ja salvesta. Pildi muutmata jätmiseks ära vali uut pilti. **Kustutamine:** vajuta „Kustuta” ja kinnita; kustutatakse ka seotud testid, kommentaarid, tulemused ja salvestused. Seetõttu ole kustutamisel ettevaatlik.

**Testiküsimused:** vali õppematerjali real „Test”; lisa küsimus, kolm varianti ja märgi üks õige vastus. Olemasoleva küsimuse saad kustutada ja luua selle uuesti; eraldi muutmisvormi selles versioonis pole.

## 6. Levinud vead

| Probleem | Lahendus |
| --- | --- |
| `Unknown database 'opi_eestis'` | Käivita MySQL ja impordi `database/opi_eestis.sql`; kontrolli faili `inc/db.php`. |
| Apache näitab kaustade loendit | Veendu, et `C:\xampp\htdocs\opi_eestis\index.php` asub õigel tasemel. |
| URL annab 404, kuigi fail on olemas | Kontrolli Apache `mod_rewrite` moodulit ja `.htaccess` lubamist. |
| Admini funktsioon ei avane | Logi sisse päris admini rolliga kontoga; registreeritud õpilane ei ole admin. |
| Õppematerjali salvestamine annab SQL-vea | Vanal ÕpiEestis paigaldusel impordi üks kord `database/upgrade_v2.sql`. |
| Uus pilt ei ilmu | Kontrolli failitüüpi, suurust (kuni 2 MB) ja tunnile omistatud avaldamise olekut. |

## 7. Andmed ja ohutus

Õppeprojekt on mõeldud lokaalseks kasutamiseks. Kasuta testkontosid; ära sisesta päris õpilaste isikuandmeid ega avalda andmebaasi eksporti GitHubis. Varunda andmebaas enne suuremaid muudatusi.
