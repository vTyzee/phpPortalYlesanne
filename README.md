# ÕpiEestis

**ÕpiEestis** on PHP ja MySQL-i abil loodud õppematerjalide ning teadmiste kontrollimise veebirakendus. Projekt põhineb Newsportali MVC-ülesehitusel: uudiste kategooriatest on saanud õppeained ning uudiste asemel kuvatakse õppetunde. ÕpiEestis kasutab eraldi andmebaasi `opi_eestis`.

## Võimalused

- Avaleht kolme õppematerjaliga, õppeainete menüü ning õppetundide loend.
- Õppematerjalide otsing ning filtreerimine õppeaine ja taseme järgi.
- Õppetunni detailvaade, arutelu ja valikvastustega enesekontrollitest koos vastuste tagasisidega.
- Kasutajakonto, salvestatud õppematerjalid ja testitulemuste ajalugu lehel **Minu õpitee**.
- Administraatori vaade õppematerjalide ja testiküsimuste haldamiseks.
- Ühtne must-valge-roheline kujundus.

Näidisandmebaasis on neli õppeainet, 12 õppematerjali ja 36 testiküsimust.

## Tehnoloogiad

PHP 8.1+, MySQL/MariaDB, PDO, HTML, CSS, Apache ja MVC-ülesehitus.

## Käivitamine XAMPP-is

1. Paiguta projekt kausta `C:\xampp\htdocs\opi_eestis\` nii, et seal asub `index.php`.
2. Käivita XAMPP-is Apache ja MySQL.
3. Ava `http://localhost/phpmyadmin/` ning impordi uue paigalduse jaoks fail `database/opi_eestis.sql`.
4. Ava brauseris `http://localhost/opi_eestis/`.
5. Loo tavakasutaja lehel **Registreeru**. Administraatori konto loomiseks käivita projekti kaustas:

   ```powershell
   C:\xampp\php\php.exe tools\create_admin.php
   ```

   Sisesta käsureal administraatori kasutajanimi, e-posti aadress ja parool.

Olemasoleva andmebaasi uuendamisel tee enne varukoopia ning kasuta vajadusel faili `database/upgrade_v2.sql`, mitte ära impordi algandmebaasi uuesti.

## Testimine

Käivita projektikaustas:

```powershell
C:\xampp\php\php.exe tests\smoke.php
```

Brauseris kontrollimiseks ava avaleht, vali õppeaine, otsi õppematerjal, lahenda test ning proovi registreerimist, sisselogimist ja administraatori vaadet. Üksikasjalik kontrollnimekiri on failis [TESTIMINE.md](docs/TESTIMINE.md).

## Dokumentatsioon

- [Kasutusdokumentatsioon](docs/KASUTUSDOKUMENTATSIOON.md) · [PDF](docs/pdf/KASUTUSDOKUMENTATSIOON.pdf)
- [Tehniline ülesanne](docs/TEHNILINE_YLESANNE.md) · [PDF](docs/pdf/TEHNILINE_YLESANNE.pdf)
- [Testimisplaan](docs/TESTIMINE.md) · [PDF](docs/pdf/TESTIMINE.pdf)
- [Projekti hindamine ja parendused](docs/ARENDUSHINNANG.md) · [PDF](docs/pdf/ARENDUSHINNANG.pdf)
- [Arenduspäevik](docs/ARENDUSPAEVIK.md)

**Repositoorium:** https://github.com/vTyzee/phpPortalYlesanne
