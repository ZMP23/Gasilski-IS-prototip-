const validacijaSpP = new JustValidate("#urediPrer");

validacijaSpP
    .addField("#u_imePriimekPrer", [
        {
            rule: "required"
        }
    ])
    .addField("#u_kodaKOPrer", [
        {
            rule: "required"
        }
    ])
    .addField("#u_datOdsPrer", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaSpP = document.getElementById("urediPrer");
        const formaPodatkiSpP = new FormData(formaSpP);

        fetch("procesirajPrerazporeditev.php", {
            method: "POST",
            body: formaPodatkiSpP
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