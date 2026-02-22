const validacijaSp = new JustValidate("#urediUp");

validacijaSp
    .addField("#u_ime", [
        {
            rule: "required"
        }
    ])
    .addField("#u_priimek", [
        {
            rule: "required"
        }
    ])
    .addField("#u_delSkp", [
        {
            rule: "required"
        }
    ])
    .addField("#u_email", [
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
    .addField("#u_geslo", [
        {
            rule: "password"
        }
    ])
    .addField("#u_potr_geslo", [
        {
            validator: (value, fields) => {
                return value === fields["#u_geslo"].elem.value;
            }
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaSp = document.getElementById("urediUp");
        const formaPodatkiSp = new FormData(formaSp);

        fetch("spremeniUporabnika.php", {
            method: "POST",
            body: formaPodatkiSp
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