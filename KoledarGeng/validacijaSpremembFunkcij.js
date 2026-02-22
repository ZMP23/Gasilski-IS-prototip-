const validacijaSpFun = new JustValidate("#urediFun");

validacijaSpFun
    .addField("#u_imeFun", [
        {
            rule: "required"
        }
    ])
    .onSuccess((event) => {
        event.preventDefault();
        const formaFun = document.getElementById("urediFun");
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