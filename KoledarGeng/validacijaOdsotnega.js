const validacijaOdsZap = new JustValidate("#dodOdsZap");

validacijaOdsZap
    .addField("#imePriimekOdsZap", [
        {
            rule: "required"
        }
    ])
    .addField("#kodaOds", [
        {
            rule: "required"
        }
    ])
    .addField("#datOds", [
        {
            rule: "required"
        }
    ])
    .addField("#kodaKO", [
        {
            rule: "required"
        }
    ])
    .addField("#kodaSO", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaOdsZap = document.getElementById("dodOdsZap");
        const formaPodatkiOdsZap = new FormData(formaOdsZap);

        fetch("procesirajOdsotnega.php", {
            method: "POST",
            body: formaPodatkiOdsZap
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