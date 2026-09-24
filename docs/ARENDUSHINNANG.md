# PROJEKTI HINNANG: 3 NÕRKUST JA 3 PARENDUST

**ÕpiEestis** | Arendusülesanne pärast esimese toimiva versiooni loomist

Projekti eesmärk oli **luua Newsportali põhjal oma teemaga veebileht**, mitte kirjutada kõik nullist. Seetõttu ei ole MVC-struktuuri ja tuttavate leheosade taaskasutamine puudus: see on ülesande lähtekoht. Allpool on ÕpiEestis enda esimese versiooni konkreetsed puudused ning nende põhjal tehtud parendused.

## Kolm nõrkust enne parendusi

1. **Õppematerjale oli vähe:** algversioonis oli 4 õppeaines 8 näidistundi ja ühes tunnis puudus test; õppija valik oli piiratud.
2. **Õpilane ei saanud endale materjale salvestada:** olemas olid testitulemused, kuid huvitava tunni juurde tagasipöördumiseks tuli see uuesti otsida.
3. **Testi tagasiside oli liiga üldine:** pärast vastamist nägi õppija punktisummat, kuid mitte seda, millistes küsimustes ta eksis ja mis olid õiged vastused.

## Kolm elluviidud parendust

1. **Rohkem õppesisu:** lisatud üks uus tund igasse õppeainesse ning puuduv test inglise keele suunajuhiste tunnile. Uues näidisbaasis on 12 tundi ja 36 küsimust. Lisatunnid ja testid on värske paigalduse failis `database/opi_eestis.sql`; migratsioon `database/upgrade_v2.sql` lisab olemasolevasse andmebaasi ainult salvestuste tabeli.
2. **„Salvesta õppematerjal” + „Minu õpitee”:** sisse loginud õpilane saab tunni salvestada/eemaldada ning leida salvestatud tunnid enda kontolt. Salvestused on kasutajapõhised tabelis `lesson_bookmarks`, iga õpilase nimekiri on eraldi.
3. **Vastusepõhine testitagasiside:** pärast testi kuvatakse iga küsimuse juures, kas vastus oli õige, ja õige vastuse tekst. Enne vastamist vastusevõtit ei avaldata.

## Mida saab järgmises etapis veel parandada?

- Lisada õppematerjalidele tõelised õppekavad, rohkem õpikuga kooskõlas olevat sisu ja sisutoimetaja ülevaatus.
- Lisada muid ülesandetüüpe (lünkade täitmine, vabatekst, interaktiivsed ülesanded) ja testiküsimuste muutmise vorm.
- Luua MySQL-i integratsioonitestid ning mõõta PHPUnit + Xdebug/PCOV abil koodikate, sest >60% pole praegu tõendatud.

**Märkus:** need kolm parendust on lähtekoodis teostatud ja andmebaasis ette valmistatud. Päris XAMPP-is brauseri ja MySQL-iga kontrollimine tuleb teha paigaldaja arvutis vastavalt testimisplaanile.
