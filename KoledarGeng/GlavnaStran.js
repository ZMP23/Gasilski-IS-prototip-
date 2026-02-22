const gumbKoledar = document.querySelector("#gumbiMPkoledar");
const gumbPodatki = document.querySelector("#gumbiMPpregledPodatkov");
const gumbOdjava = document.querySelector("#gumbiMPodjava");

gumbKoledar.addEventListener("click", () => {
    window.location.href = "Koledar2.php";
});

gumbPodatki.addEventListener("click", () => {
    window.location.href = "ObdelavaPodatkov.php";
});

gumbOdjava.addEventListener("click", () => {
    window.location.href = "Odjava.php";
});