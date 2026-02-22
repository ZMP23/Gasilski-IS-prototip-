const validacijaSpKO = new JustValidate("#urediKO");

validacijaSpKO
    .addField("#u_nazivKO", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaSpKO = document.getElementById("urediKO");
        const formaPodatkiSpKO = new FormData(formaSpKO);

        fetch("procesirajKoledarskoOznako.php", {
            method: "POST",
            body: formaPodatkiSpKO
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