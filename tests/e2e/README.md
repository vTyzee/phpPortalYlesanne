# Selenium E2E / UI testid

Need testid avavad päris brauseri ja kontrollivad ÕpiEestis kasutajaliidest algusest lõpuni.

## Mida kontrollitakse

1. avaleht ja peamenüü;
2. õppematerjalide otsing ning õppeaine filter;
3. õppetunni avamine ja testi läbimine kuni tulemuse kuvamiseni;
4. registreerimise ja sisselogimise UI;
5. administraatori sisselogimine ning haldusvaade (kui admini testandmed on määratud);
6. administraatori kommentaari lisamine ja kustutamine (kui admini testandmed on määratud).

## Eeldused

- XAMPP Apache ja MySQL töötavad;
- `opi_eestis` andmebaas on imporditud;
- projekt avaneb aadressil `http://localhost/opi_eestis/`;
- Python 3 on paigaldatud;
- Microsoft Edge või Google Chrome on paigaldatud.

Selenium 4 kasutab **Selenium Managerit**, seega tavaliselt ei ole vaja `msedgedriver.exe` või `chromedriver.exe` käsitsi alla laadida ega PATH-i lisada.

## Käivitamine Windowsis

Projekti juurkaustas:

```powershell
powershell -ExecutionPolicy Bypass -File tests\e2e\run_e2e.ps1
```

Esimesel käivitamisel paigaldab skript Pythoni `selenium` paketi. Brauser avaneb nähtavalt, et testi kulgu oleks võimalik õpetajale demonstreerida.

### Admini E2E-testid

Admini sisselogimise ja kommentaari modereerimise testide jaoks määra enne käivitamist ajutiselt keskkonnamuutujad:

```powershell
$env:E2E_ADMIN_EMAIL="admin@example.ee"
$env:E2E_ADMIN_PASSWORD="SINu_PAROOL"
powershell -ExecutionPolicy Bypass -File tests\e2e\run_e2e.ps1
```

Parooli ei salvestata GitHubi. Kui neid muutujaid pole, jäetakse kaks adminitesti automaatselt vahele (`skipped`), ülejäänud UI testid töötavad edasi.

### Chrome'i kasutamine

```powershell
$env:E2E_BROWSER="chrome"
powershell -ExecutionPolicy Bypass -File tests\e2e\run_e2e.ps1
```

### Headless režiim

```powershell
$env:E2E_HEADLESS="1"
powershell -ExecutionPolicy Bypass -File tests\e2e\run_e2e.ps1
```

## Tõrke korral

Ebaõnnestunud testi brauseripilt salvestatakse kausta `tests/e2e/screenshots/`. Seda kausta GitHubi ei lisata.
