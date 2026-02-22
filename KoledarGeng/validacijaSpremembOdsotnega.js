const validacijaSpOdsZap = new JustValidate("#urediOdsZap");

validacijaSpOdsZap
    .addField("#u_imePriimekOdsZap", [
        {
            rule: "required"
        }
    ])
    .addField("#u_kodaOds", [
        {
            rule: "required"
        }
    ])
    .addField("#u_datOds", [
        {
            rule: "required"
        }
    ])
    .addField("#u_kodaKO", [
        {
            rule: "required"
        }
    ])
    .addField("#u_kodaSO", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaSpOdsZap = document.getElementById("urediOdsZap");
        const formaPodatkiSpOdsZap = new FormData(formaSpOdsZap);

        fetch("procesirajOdsotnega.php", {
            method: "POST",
            body: formaPodatkiSpOdsZap
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