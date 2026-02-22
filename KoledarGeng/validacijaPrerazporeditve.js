const validacijaP = new JustValidate("#dodPrer");

validacijaP
    .addField("#imePriimekPrer", [
        {
            rule: "required"
        }
    ])
    .addField("#datOdsPrer", [
        {
            rule: "required"
        }
    ])
    .addField("#kodaKOPrer", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaP = document.getElementById("dodPrer");
        const formaPodatkiP = new FormData(formaP);

        fetch("procesirajPrerazporeditev.php", {
            method: "POST",
            body: formaPodatkiP
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