# ÕpiEestis - õppematerjalide ja testide portaal

**ÕpiEestis** on eraldi PHP/MySQL MVC õppeprojekt teemal **„Õppimine Eestis”**. See on arendatud algse Newsportali ülesehituse põhjal: kategooriad → õppeained, uudised → õppematerjalid, uudise detailvaade → õppetund, kommentaarid → arutelu ning uudiste haldus → õppematerjalide haldus. Algset Newsportali repositooriumi ega `newsportal` andmebaasi ei muudeta. Projekt ei ole ametlik Eesti riigi teenus.

![PHP](https://img.shields.io/badge/PHP-8.1%2B-16864b) ![MySQL](https://img.shields.io/badge/MySQL%2FMariaDB-PDO-16864b) ![MVC](https://img.shields.io/badge/Arhitektuur-MVC-16864b)

## Mis on valmis?

- **Avalik vaade:** avalehel 3 viimast õppematerjali, õppeainete menüü, materjalide loend, otsing, tasemefilter, õppetund ning kommentaarid.
- **Õpilane:** registreerimine rolliga `user`, sisselogimine, õppematerjali salvestamine, testi sooritamine, tulemuste ja salvestatud materjalide vaatamine lehel „Minu õpitee”.
- **Administraator:** sisselogimine, õppematerjali lisamine/muutmine/kustutamine, avaldamise määramine, kuni 2 MB kaanepilt ning kolme vastusevariandiga testiküsimuse lisamine/kustutamine.
- **Õppe sisu:** 4 õppeainet, 12 näidisõppetundi ja 36 valikvastustega näidisküsimust. Testi lõpus kuvatakse tulemus ja iga küsimuse õige vastus.
- **Kujundus:** Newsportaliga sarnane õppeainete menüü, kolme materjaliga avaleht, artikliloend ja detaillehed. Must-valge-roheline värvilahendus on sama avalikul lehel, sisselogimisel ja halduses.

**Piirangud:** Õppeainete lisamine ja testiküsimuste muutmine nõuavad veel SQL-i või küsimuse kustutamist ning uuesti loomist. See on lokaalne õppeprojekt; enne avalikku internetti paigaldamist tuleb teha turvalisuse ja andmekaitse ülevaatus. Automaattestide **>60% koodikatet ei ole mõõdetud**.

**Venekeelne samm-sammuline juhend uuele arvutile ja GitHubile:** [GITHUB_START_RU.md](GITHUB_START_RU.md).

## 1. Käivitamine uues Windowsi arvutis

Vaja läheb XAMPP-i (Apache, MySQL/MariaDB ja PHP 8.1+; PHP moodulid `pdo_mysql`, `mbstring`, `fileinfo`), veebibrauserit ja soovitatavalt VS Code'i. Apache'i `mod_rewrite` ja `.htaccess` peavad toimima.

1. Paki projekti kaust välja nii, et olemas on `C:\xampp\htdocs\opi_eestis\index.php` (mitte topelt `opi_eestis\opi_eestis`). Projekti ZIP-is säilib ka `.git` ajalugu.
2. Käivita XAMPP Control Panel'is **Apache** ja **MySQL**.
3. Ava `http://localhost/phpmyadmin/` → **Import** → vali `database/opi_eestis.sql` → **Go**. Skript loob uue `opi_eestis` andmebaasi ja näidisandmed. **Ära impordi Newsportali `newsportal.sql` faili siia.**
4. Ava `http://localhost/opi_eestis/` ning vali mõni õppematerjal; testi jaoks saad kasutada külalise vaadet.
5. Registreeri õpilane menüüs „Registreeru”. Tavakasutaja roll määratakse alati serveris `user`.
6. Administraatori loomiseks ava projekti kaustas terminal ja käivita:

   ```powershell
   C:\xampp\php\php.exe tools\create_admin.php
   ```

   Sisesta administraatori nimi, e-post ja vähemalt 8 märgiga parool. **Ära lisa administraatori paroole GitHubi.** Logi sisse menüüs „Logi sisse”; administraatorile kuvatakse „Halduspaneel”.
7. Lokaalse andmebaasi vaikeühendus on failis `inc/db.php`: host `127.0.0.1`, andmebaas `opi_eestis`, kasutaja `root`, tühi parool. Teistsuguse seadistuse jaoks kasuta `OPI_DB_HOST`, `OPI_DB_NAME`, `OPI_DB_USER`, `OPI_DB_PASSWORD` keskkonnamuutujaid.

**Kui varasem ÕpiEestis v1 on juba paigaldatud**, ära impordi tervet andmebaasi selle peale. Tee esmalt varukoopia ning impordi **üks kord** `database/upgrade_v2.sql`, mis lisab salvestatud materjalide tabeli. Neli uut näidistundi ja testid on värskes `opi_eestis.sql`-is; olemasolevaid õppematerjale ei kirjutata migratsiooniga üle.

## 2. GitHubi uus repositoorium

Projektipakis olev `.git` sisaldab selle **uue repositooriumi 8 tegelikku kohalikku arenduskommitti**. Algse Newsportali vana GitHubi ajalugu ei ole kopeeritud ega väljamõeldud. ZIP-ist võid jätkata kohe kohaliku ajalooga.

1. Loo GitHubis **uus tühi** repositoorium nimega `opi-eestis`. Ära lisa GitHubi kasutajaliideses README-d, `.gitignore`-i ega litsentsifaili, sest need on kohalikus projektis juba olemas või pole vajalikud.
2. Ava VS Code'is täpselt kaust `C:\xampp\htdocs\opi_eestis`; terminalis käivita:

   ```powershell
   git status
   git log --oneline
   git remote -v
   git remote add origin https://github.com/SINU_KASUTAJANIMI/opi-eestis.git
   git branch -M main
   git push -u origin main
   ```

   Asenda `SINU_KASUTAJANIMI` enda GitHubi kasutajanimega. Kui `origin` on juba olemas, kontrolli URL-i ja muuda vajadusel `git remote set-url origin ...` käsuga.
3. Kui `git status` ütleb **not a git repository**, ei pakkunud Windowsi lahtipakkija peidetud `.git` kausta lahti. Kasuta varukoopiat `OpiEestis_git_ajalugu.bundle` (juuresolev allalaadimine) ja käivita terminalis selle faili asukohas:

   ```powershell
   git clone OpiEestis_git_ajalugu.bundle opi_eestis
   ```

   Seejärel tõsta kloonitud `opi_eestis` kaust `htdocs` alla ja lisa remote nagu ülal. Ära kasuta siin `git init` ega `git push --force` - olemasolev ajalugu säilib just `.git` kausta või bundle'iga.

### Soovitatud edasised kommitid

Tee pärast XAMPP-i käsitsi kontrollimist väike eraldi parandus iga leitud vea jaoks: `git add .`, `git commit -m "Fix ..."`, `git push`. Ära kommiti päris kasutajate andmeid ega lokaalset andmebaasi. Käesolev ajalugu ja selle etapid on kirjeldatud failis [ARENDUSPAEVIK.md](docs/ARENDUSPAEVIK.md).

## 3. Failide ülesehitus

```text
opi_eestis/
├── index.php                     avaliku lehe sisenemispunkt
├── .htaccess                     Apache'i URL-ide suunamine
├── route/routing.php             lehekülgede marsruudid
├── controller/Controller.php     avaliku lehe kontroller
├── model/                       Subject, Lesson, Quiz, Comments, Auth, Bookmark
├── inc/                         andmebaasiühendus, sessioon, abi- ja turbefunktsioonid
├── view/                        avalik, konto ja haldusvaated ühises kujunduses
├── admin/                       eraldi administraatori marsruudid ja kontroller
├── assets/style.css             must-valge-roheline kohanduv kujundus
├── database/opi_eestis.sql      puhas uus paigaldus ja näidisandmed
├── database/upgrade_v2.sql      ühekordne üleminek eelmisest ÕpiEestis versioonist
├── tools/create_admin.php       administraatori loomine CLI-ga
├── tests/smoke.php              andmebaasita regressioonikontrollid
└── docs/                        dokumentatsioon ja PDF-versioonid
```

## 4. Kontrollimine

```powershell
C:\xampp\php\php.exe tests\smoke.php
C:\xampp\php\php.exe -l index.php
```

Manuaalne testiplaan asub failis [TESTIMINE.md](docs/TESTIMINE.md). Siinses koostamiskeskkonnas läbisid 25 sõltumatut PHP-kontrolli ja PHP-failide süntaksikontroll, **kuid päris XAMPP-i/MySQL-i andmebaasi-integratsiooni ei saanud siin käivitada**. Testide hulk ei ole sama mis koodikate; >60% nõude täitmiseks on vaja eraldi koodikatte mõõtmist näiteks PHPUnit + Xdebug/PCOV abil.

## Dokumentatsioon

- [Tehniline ülesanne](docs/TEHNILINE_YLESANNE.md) · [PDF](docs/pdf/TEHNILINE_YLESANNE.pdf)
- [Kasutusdokumentatsioon](docs/KASUTUSDOKUMENTATSIOON.md) · [PDF](docs/pdf/KASUTUSDOKUMENTATSIOON.pdf)
- [Testimisplaan](docs/TESTIMINE.md) · [PDF](docs/pdf/TESTIMINE.pdf)
- [3 nõrkust ja 3 parendust: enne/pärast](docs/ARENDUSHINNANG.md) · [PDF](docs/pdf/ARENDUSHINNANG.pdf)
- [Arenduspäevik ja git-ajalugu](docs/ARENDUSPAEVIK.md)

## Turvalisus ja autorlus

Paroolid räsitakse `password_hash()` abil; rolli kontrollitakse serveris; vormide muutmistoimingutel on CSRF-token; andmebaasipäringutel kasutatakse PDO ettevalmistatud päringuid ning kuvatavad kasutajatekstid HTML-kodeeritakse. Need on lähtekoodi tasemel kasutatud meetmed, **mitte väide täieliku turvaauditiga valmistoote kohta**. Projekt on mõeldud õppetööks, mitte ametliku riigiportaalina esitlemiseks.
