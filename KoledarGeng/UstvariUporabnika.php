<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>Dodaj uporabnika</title>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validacija.js" defer></script>
</head>
<body>
    <h2>Dodajanje novega uporabnika</h2>
    <form action="procesirajUporabnika.php" method="post" id="dodUp" novalidate>
        <div>
            <label for="ime">Ime:</label>
            <input type="text" id="ime" name="ime">
        </div>
        <div>
            <label for="priimek">Priimek:</label>
            <input type="text" id="priimek" name="priimek">
        </div>
        <div>
            <label for="datRoj">Datum rojstva:</label>
            <input type="date" id="datRoj" name="datRoj">
        </div>
        <div>
            <label for="gsm">GSM:</label>
            <input type="text" id="gsm" name="gsm">
        </div>
        <div>
            <label for="email">Spletna pošta:</label>
            <input type="email" maxlength="50" id="email" name="email">
        </div>
        <div>
            <label for="geslo">Geslo:</label>
            <input type="password" id="geslo" name="geslo" required>
        </div>
        <div>
            <label for="potr_geslo">Potrdite geslo:</label>
            <input type="password" id="potr_geslo" name="potr_geslo" required>
        </div>

        <button type="submit">Dodaj</button>
    </form>
</body>
</html>