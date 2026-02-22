const validacija = new JustValidate("#dodUp");

validacija
    .addField("#ime", [
        {
            rule: "required"
        }
    ])
    .addField("#priimek", [
        {
            rule: "required"
        }
    ])
    .addField("#delSkp", [
        {
            rule: "required"
        }
    ])
    .addField("#email", [
        {
            rule: "required"
        },
        {
            rule: "email"
        },
        {
            validator: (value) => {
                return fetch("validacijaEmaila.php?email=" + encodeURIComponent(value))
                       .then(function(response) {
                           return response.json();
                       })
                       .then(function(json) {
                           return json.available;
                       });
            },
            errorMessage: "Email že v uporabi."
        }
    ])
    .addField("#geslo", [
        {
            rule: "required"
        },
        {
            rule: "password"
        }
    ])
    .addField("#potr_geslo", [
        {
            validator: (value, fields) => {
                return value === fields["#geslo"].elem.value;
            },
            errorMessage: "Gesli se morata ujemati."
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const forma = document.getElementById("dodUp");
        const formaPodatki = new FormData(forma);

        fetch("procesirajUporabnika.php", {
            method: "POST",
            body: formaPodatki
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
