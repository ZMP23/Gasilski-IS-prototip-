const validacijaOds = new JustValidate("#dodDS");

validacijaOds
    .addField("#kodaDS", [
        {
            rule: "required"
        }
    ])
    .addField("#imeDS", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaDS = document.getElementById("dodDS");
        const formaPodatkiDS = new FormData(formaDS);

        fetch("procesirajDelovnoSkupino.php", {
            method: "POST",
            body: formaPodatkiDS
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