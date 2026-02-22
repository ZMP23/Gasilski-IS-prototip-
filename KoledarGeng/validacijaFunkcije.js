const validacijaFun = new JustValidate("#dodFun");

validacijaFun
    .addField("#kodaFun", [
        {
            rule: "required"
        }
    ])
    .addField("#imeFun", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaFun = document.getElementById("dodFun");
        const formaPodatkiFun = new FormData(formaFun);

        fetch("procesirajFunkcijo.php", {
            method: "POST",
            body: formaPodatkiFun
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