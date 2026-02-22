const validacijaSpDS = new JustValidate("#urediDS");

validacijaSpDS
    .addField("#u_imeDS", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaSpDS = document.getElementById("urediDS");
        const formaPodatkiSpDS = new FormData(formaSpDS);

        fetch("procesirajDelovnoSkupino.php", {
            method: "POST",
            body: formaPodatkiSpDS
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