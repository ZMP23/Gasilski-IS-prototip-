const validacijaKO = new JustValidate("#dodKO");

validacijaKO
    .addField("#kodaKO", [
        {
            rule: "required"
        }
    ])
    .addField("#nazivKO", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaKO = document.getElementById("dodKO");
        const formaPodatkiKO = new FormData(formaKO);

        fetch("procesirajKoledarskoOznako.php", {
            method: "POST",
            body: formaPodatkiKO
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                window.location.reload();
            } else {
            alert("Napaka: " + data.message); 
            }
        })
        .catch(error => {
            console.error("Error: ", error);
            alert("Povezava s strežnikom ni uspela.");
        })
    });