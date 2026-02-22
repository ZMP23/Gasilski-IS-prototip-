const validacijaCl = new JustValidate("#dodCl");

validacijaCl
    .addField("#imeCl", [
        {
            rule: "required"
        }
    ])
    .addField("#priimekCl", [
        {
            rule: "required"
        }
    ])
    .addField("#delSkpCl", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaCl = document.getElementById("dodCl");
        const formaPodatkiCl = new FormData(formaCl);

        fetch("procesirajClana.php", {
            method: "POST",
            body: formaPodatkiCl
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
