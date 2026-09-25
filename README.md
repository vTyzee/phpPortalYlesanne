# ÕpiEestis – õppematerjalide ja testide portaal

**ÕpiEestis** on PHP/MySQL MVC õppeprojekt teemal **„Õppimine Eestis”**. Projekt on loodud Newsportali struktuuri põhjal: kategooriad on muudetud õppeaineteks, uudised õppematerjalideks ning uudiste haldus õppematerjalide halduseks.

## Funktsioonid

### Avalik kasutaja

- avalehel 3 viimast õppematerjali;
- õppeainete valik;
- kõikide õppematerjalide loend;
- otsing märksõna järgi;
- filtreerimine õppeaine ja taseme järgi;
- õppematerjali detailvaade;
- valikvastustega testid.

### Registreeritud kasutaja

- konto loomine ja sisselogimine;
- õppematerjalide salvestamine;
- leht **Minu õpitee**;
- testitulemuste salvestamine;
- kommentaaride lisamine õppematerjalidele.

### Administraator

- õppematerjalide lisamine, muutmine ja kustutamine;
- õppematerjali avaldamine või peitmine;
- kaanepildi lisamine;
- testiküsimuste lisamine ja kustutamine;
- kasutajate kommentaaride kustutamine.

## Tehnoloogiad

- PHP 8.1+
- MySQL / MariaDB
- PDO
- HTML5
- CSS3
- Apache / XAMPP
- MVC struktuur
- Git ja GitHub

## Käivitamine

1. Paki projekt kausta:

   ```text
   C:\xampp\htdocs\opi_eestis\
   ```

2. Käivita XAMPP-is **Apache** ja **MySQL**.
3. Ava `http://localhost/phpmyadmin/` ja impordi:

   ```text
   database/opi_eestis.sql
   ```

4. Ava veebileht:

   ```text
   http://localhost/opi_eestis/
   ```

## Administraatori konto

Administraatori saab luua käsurealt:

```powershell
C:\xampp\php\php.exe tools\create_admin.php
```

Administraator logib sisse sama vormi kaudu nagu tavakasutaja. Administraatori rolliga kasutajale kuvatakse halduspaneel.

## Projekti struktuur

```text
opi_eestis/
├── admin/                     administraatori kontroller ja marsruudid
├── assets/                    kujundus
├── controller/                avaliku osa kontroller
├── database/                  SQL-failid
├── docs/                      dokumentatsioon
├── inc/                       andmebaas, sessioon ja abifunktsioonid
├── model/                     andmemudelid
├── route/                     avalikud marsruudid
├── tests/                     automaatkontrollid
├── tools/                     administraatori loomise tööriist
├── view/                      kasutajaliidese vaated
├── index.php
└── README.md
```

## Testimine

Automaatkontrollide käivitamine:

```powershell
C:\xampp\php\php.exe tests\smoke.php
```

PHP-faili süntaksi kontroll:

```powershell
C:\xampp\php\php.exe -l index.php
```

Täpsem testimisplaan asub failis [docs/TESTIMINE.md](docs/TESTIMINE.md).

## Dokumentatsioon

- [Kasutusjuhend ekraanipiltidega](docs/KASUTUSJUHEND.md)
- [Tehniline ülesanne](docs/TEHNILINE_YLESANNE.md)
- [Testimisplaan](docs/TESTIMINE.md)
- [3 nõrkust ja 3 parendust](docs/ARENDUSHINNANG.md)
- [Arenduspäevik](docs/ARENDUSPAEVIK.md)
- [Projekti töölaud: Todo / In Progress / Done](docs/PROJECT_BOARD.md)

## GitHub

Repositoorium: `vTyzee/phpPortalYlesanne`

Pärast muudatuste tegemist:

```bash
git status
git add .
git commit -m "Kirjelda tehtud muudatust"
git push
```

## Turvalisus

- paroolid salvestatakse `password_hash()` abil;
- sisselogimisel kasutatakse `password_verify()`;
- rolli kontroll toimub serveris;
- muutmisvormidel kasutatakse CSRF-tokenit;
- andmebaasipäringutes kasutatakse PDO ettevalmistatud päringuid;
- kasutaja sisestatud tekst HTML-kodeeritakse enne kuvamist.
