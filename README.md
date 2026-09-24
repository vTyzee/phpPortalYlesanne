# ÕpiEestis – õppimise portaal

**ÕpiEestis** on eraldi PHP/MySQL MVC õppeprojekt, mis on loodud Newsportali struktuuri ja põhifunktsioonide (kategooriad, sisu, autentimine, haldus ja kommentaarid) eeskujul. Uus teema on *Õppimine Eestis*. ÕpiEestis **ei kasuta Newsportali andmebaasi ega muuda algset projekti**.

## Funktsioonid

- Avaleht, õppeained, õppematerjalide otsing ja taseme filtrid.
- Õppetunnid ja nendega seotud valikvastustega testid; tulemus salvestub sisseloginud kasutajale.
- Registreerimine (vaikeroll `user`), sisselogimine, väljalogimine ja õpitee kokkuvõte.
- Kommentaarid õppetundide juures (sisselogitud kasutajatele).
- Administraatori paneel: õppematerjalide lisamine, muutmine, kustutamine, avaldamise olek ja valikuline kaanepilt.
- Ühtne musta, valge ja rohelisega kujundus ka avalehel, sisse- ja väljalogimisel, õpilase kontol ja halduses. Kujundus on kohanduv telefonis.

**Piirangud:** Õppeainete lisamine ning testiküsimuste muutmine pärast lisamist toimub esialgu SQL-i kaudu. Halduspaneel lubab küsimusi lisada ja eemaldada. Testi tulemuse nägemiseks ei pea sisse logima, kuid külalise tulemust ei salvestata. Sisu on näidisõppematerjal; portaal ei ole ametlik riigiteenus.

## Paigaldamine Windowsis / XAMPP-is

1. Käivita XAMPP-is **Apache** ja **MySQL**.
2. Paki projekti kaust `opi_eestis` välja asukohta `C:\xampp\htdocs\opi_eestis\`. Kontrolli, et `C:\xampp\htdocs\opi_eestis\index.php` oleks olemas.
3. Ava `http://localhost/phpmyadmin/`, vali **Import**, vali `database/opi_eestis.sql` ning impordi. SQL loob **uue andmebaasi `opi_eestis`** koos näidistundide, õppeainete ja testidega. Ära impordi vana Newsportali andmebaasi siia.
4. Ava `http://localhost/opi_eestis/`.
5. Loo oma õpilase konto veebilehel nupuga **Loo konto**. Administraatori konto loomiseks ava Windowsi terminalis projekti juurkaust ja käivita `C:\xampp\php\php.exe tools\create_admin.php`. Sisesta nimi, e-post ja vähemalt kaheksast tähemärgist parool. Seejärel logi aadressil `http://localhost/opi_eestis/admin/` sisse.
6. Andmebaasiühenduse vaikeseaded on failis `inc/db.php`: `127.0.0.1`, `root`, tühi parool ning `opi_eestis`. Muuda vajadusel `OPI_DB_HOST`, `OPI_DB_USER`, `OPI_DB_PASSWORD`, `OPI_DB_NAME` keskkonnamuutujaid. Arvesta, et phpMyAdmini import eeldab piisavaid andmebaasi õigusi.

### Kasutamine

Vali õppeaine → ava õppetund → loe materjal → vajuta **Alusta testi**. Sisseloginuna näed oma tulemusi lehel **Minu õpitee**. Administraator saab vajutada **Haldus → Õppematerjalid**, et lisada, muuta või kustutada õppetunde. Tunni testiküsimusi saad hallata haldusnimekirja lingi **Test** kaudu.

## Projektistruktuur

```
opi_eestis/
├── index.php                 # Veebilehe sisenemispunkt
├── route/routing.php         # Avaliku lehe marsruudid
├── controller/Controller.php # Lehtede ja vormide käsitlemine
├── model/                   # Õppeained, tunnid, testid, kommentaarid, kontod
├── inc/                     # Andmebaas, sessioonid ja üldised abifunktsioonid
├── view/                    # Ühtse disainiga lehed ja komponendid
├── admin/                   # Eraldi admini sisenemispunkt ja kontroller
├── assets/style.css         # Kohanduv must-valge-roheline kujundus
├── database/opi_eestis.sql  # Uus andmebaas ja näidisandmed
├── tools/create_admin.php   # Administraatori loomine käsurealt
└── tests/smoke.php          # Väikesed sõltumatud ühikukontrollid
```

## Testimine

- Käivita `C:\xampp\php\php.exe tests\smoke.php` (Linuxis `php tests/smoke.php`).
- Käsitsi kontrolli: avaleht, õppeainete valik, õppetunni avamine, testi sooritamine külalisena ja kasutajana, registreerimine, sisse-/väljalogimine, kommentaarid, halduspaneeli CRUD ja pildi kuvamine.
- **>60% automatiseeritud testikate ei ole veel mõõdetud**. Smoke-testid ei ole testikatte tõend. Andmebaasi integratsioonitestid ning mõõdetud PHPUnit/Pest kattetulemus on järgmine tööetapp.

## Turvalisuse märkused

PDO ettevalmistatud päringud; paroolid salvestatakse `password_hash` abil; vormidel on CSRF-tokenid; kasutaja väljund kodeeritakse HTML-is; haldus kontrollib rolli serveris. Töö on **lokaalne õppeprojekt**, mitte turvaauditiga läbinud avalik teenus. Avalikku keskkonda paigaldamisel lisa HTTPS, veahaldus, päringute piiramine ja keskkonnaspetsiifiline konfiguratsioon.
