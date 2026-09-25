# ARENDUSPÄEVIK JA KOMMITID

See projekt kasutab **uut eraldi Git-repositooriumi**. Esimene commit importis juba loodud ÕpiEestis algversiooni (põhineb Newsportali MVC-ideel); järgmised commitid näitavad käesoleva töö käigus reaalselt tehtud muudatusi. See ei ole väide, et vana Newsportali varasemad kommitid või arenduspäevad oleksid siia üle kantud.

| Etapp | Commit'i sisu | Kontrollitav tulemus |
| --- | --- | --- |
| 1 | Import ÕpiEestis MVC starter derived from Newsportal | MVC, avalik/haldusvaade, MySQL skeem ja näidiskursus. |
| 2 | Adapt navigation and learning materials to Newsportal layout | Õppeainete menüü, TOP 3 materjalid, uudiste tüüpi loend ja ühine kujundus. |
| 3 | Add saved lessons and answer-by-answer quiz feedback | Isiklikud salvestused ning küsimusepõhine tagasiside. |
| 4 | Expand course content and add bookmark database migration | Neli uut tundi, puuduv inglise keele test, uus tabel ja üleminekuskript. |
| 5 | Expand independent regression checks for quiz, roles and forms | 25 CLI-kontrolli, ilma mõõdetud koodikatteta. |
| 6 | Unify learning pages with the black white and green portal theme | Ühtne roheline visuaal ja testi tulemuste sõnastuse parandus. |
| 7 | Make database upgrade preserve existing user-created lessons | Migratsioon lisab tabeli ega muuda olemasolevaid tunni-ID-sid. |
| 8 | Add technical documentation, user guide, test plan and project evaluation | README juhised ning PDF- ja Markdown-dokumendid. |

**Uue GitHubi repositooriumi loomine** on kirjeldatud README jaotises „GitHubi uus repositoorium”. Kommittide kuupäevad on paketis kohaliku arenduskeskkonna kuupäevad, mitte väited varasema Newsportali arenduse aja kohta.

## Viimane täiendus

- Lisatud administraatorile kommentaaride kustutamine õppematerjali arutelust.
- `KASUTUSDOKUMENTATSIOON.md` asendatud ekraanipiltidega `KASUTUSJUHEND.md` failiga.
- Lisatud projekti töölaud Todo / In Progress / Done jaotusega.
