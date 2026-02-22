const validacijaSpOds = new JustValidate("#urediOds");

validacijaSpOds
    .addField("#u_nazivOds", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaOds = document.getElementById("urediOds");
        const formaPodatkiOds = new FormData(formaOds);

        fetch("procesirajOdsotnost.php", {
            method: "POST",
            body: formaPodatkiOds
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