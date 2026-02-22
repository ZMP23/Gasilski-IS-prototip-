const validacijaSSO = new JustValidate("#prStOd");

validacijaSSO
    .onSuccess((event) => {
        event.preventDefault();
        const formaSSO = document.getElementById("prStOd");
        const formaPodatkiSSO = new FormData(formaSSO);

        fetch("procesirajStanjeOdsotnosti.php", {
            method: "POST",
            body: formaPodatkiSSO
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Inside the .then(data => { if (data.status === "success") { ... } }) block:

                const id = document.getElementById("odsotnostId").value;
                const selectedStanje = document.querySelector('input[name="stanje"]:checked').value;
                const koda = selectedStanje === "potrdi" ? "SO-POT" : (selectedStanje === "zavrni" ? "SO-ZAV" : "SO-NEP");

// Update local data
                Object.keys(odsotniDnevi).forEach(date => {
                odsotniDnevi[date].forEach(item => {
                    if (item.id == id) item.stanje = koda;
                });
            });

                document.querySelector(".podlaga_za_izbirnik").style.display = "none";
                uprKoledar(); // Re-render the month
            } else {
                alert("Napaka: " + data.message); 
            }
        })
        .catch(error => {
            console.error("Error: ", error);
            alert("Povezava s strežnikom ni uspela.");
        })
    });