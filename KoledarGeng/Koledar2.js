
const trenutniDatum = document.querySelector(".mesec");
const vsiDneviDiv = document.querySelector(".dnevi");
const prejPolSimboli = document.querySelectorAll(".simboli");
const gumbiStanja = document.querySelectorAll(".gumb_stanja");
const gumbNazajStanje = document.querySelector("#nazajGumb");
const podlagaIzbirnika = document.querySelector(".podlaga_za_izbirnik");
//const gumbShraniStanje = document.querySelector("#shraniGumb");

const danasnjiDatum = new Date();

let date = new Date();
let trenutnoLeto = date.getFullYear();
let trenutniMesec = date.getMonth();

const meseci = ["Januar", "Februar", "Marec", "April", "Maj", "Juni", "Juli", "Avgust", "September", "Oktober", "November", "December"]

const izmenskiCikelDan = [2, 1, 3, 4];
const izmenskiCikelNoc = [4, 2, 1, 3];
const referencniDat = new Date(2025, 5, 2); 

function pridobiIzmenoZaDan(dat) {
    const miliSekRazlika = dat - referencniDat;
    const danRazlika = Math.floor(miliSekRazlika / (1000 * 60 * 60 * 24));

    const indeks = ((danRazlika % izmenskiCikelDan.length) + izmenskiCikelDan.length) % izmenskiCikelDan.length;
    return izmenskiCikelDan[indeks];
}

function pridobiIzmenoZaNoc(dat) {
    const miliSekRazlika = dat - referencniDat;
    const danRazlika = Math.floor(miliSekRazlika / (1000 * 60 * 60 * 24));

    const indeks = ((danRazlika % izmenskiCikelNoc.length) + izmenskiCikelNoc.length) % izmenskiCikelNoc.length;
    return izmenskiCikelNoc[indeks];
}

function formatirajDatum(datum) {
    const leto = datum.getFullYear();
    const mesec = String(datum.getMonth() + 1).padStart(2, '0');
    const dan = String(datum.getDate()).padStart(2, '0');
    return `${leto}-${mesec}-${dan}`;
}

let trenutnaOdsotnost = null;

function dodajFunkcionalnostGumbu (gumb, odsId, trenutnoStanje, razlog, imePriimek, datum) {
    gumb.addEventListener("click", () => {
        document.getElementById("odsotnostId").value = odsId;
        trenutnaOdsotnost = odsId;
        document.querySelectorAll("input[name='stanje']").forEach(radio => radio.checked = false);
        document.querySelector("#rip").innerHTML = razlog + ": " + imePriimek;
        document.querySelector("#dat").innerHTML = datum;

        if (trenutnoStanje === 'SO-POT') {
            document.getElementById("potrdi").checked = true;
        } else if (trenutnoStanje === 'SO-ZAV') {
            document.getElementById("zavrni").checked = true;
        } else {
            document.getElementById("nedoloceno").checked = true;
        }
        podlagaIzbirnika.style.display = "flex";
    });
};

function dodajOdsotnostVkoledar (razlog, razlogNaziv, imePriimek, stanje, id, datumStr, obdelovaniDiv) {
    let posamezniNapisZgumbomDiv = document.createElement("div");
    posamezniNapisZgumbomDiv.classList.add("posamezni_napis_z_gumbom");
    let posNapis = document.createElement("span");
    posNapis.classList.add ("pos_napis");
    let napis = document.createElement("p");
    napis.classList.add("napis_stanje_gumb");
    napis.innerHTML = `${imePriimek}: ${razlog}`;
    napis.title = `${razlogNaziv}`;
    posNapis.append(napis);
    posamezniNapisZgumbomDiv.append(posNapis);
    let gumbStanja = document.createElement("button");
    gumbStanja.classList.add("gumb_stanja");
    if (stanje === 'SO-POT') gumbStanja.id = "gumb_stanja_pot";
    else if (stanje === 'SO-ZAV') {
        gumbStanja.id = "gumb_stanja_zav";
        napis.innerHTML = `<del>${imePriimek}: ${razlog}</del>`;
    } 
    else gumbStanja.id = "gumb_stanja_nep";
    dodajFunkcionalnostGumbu(gumbStanja, id, stanje, razlog, imePriimek, datumStr);
    if (razlog === 'DOPU') {
        posamezniNapisZgumbomDiv.append(gumbStanja);
    }
    obdelovaniDiv.append(posamezniNapisZgumbomDiv);
    obdelovaniDiv.style.display = 'block';
};

function dodajPrerazporeditevVkoledar (razlog, razlogNaziv, imePriimek, stanje, id, datumStr, obdelovaniDiv, delSkp, cssPripona, nepopolno) {
    let posamezniNapisZgumbomDiv = document.createElement("div");
    posamezniNapisZgumbomDiv.classList.add("posamezni_napis_z_gumbom", `posamezni_napis_z_gumbom_${delSkp}_${cssPripona}`);
    
    let posNapis = document.createElement("span");
    posNapis.classList.add ("pos_napis");
    
    let napis = document.createElement("p");
    napis.classList.add("napis_stanje_gumb");
    napis.innerHTML = `${imePriimek}: ${razlog}`;
    napis.title = `${razlogNaziv}`;
    
    if (nepopolno) {
        napis.style.color = "red";
    }

    posNapis.append(napis);
    posamezniNapisZgumbomDiv.append(posNapis);
    let gumbStanja = document.createElement("button");
    gumbStanja.classList.add("gumb_stanja");
    if (stanje === 'SO-POT') gumbStanja.id = "gumb_stanja_pot";
    else if (stanje === 'SO-ZAV') {
        gumbStanja.id = "gumb_stanja_zav";
        napis.innerHTML = `<del>${imePriimek}: ${razlog}</del>`;
    } 
    else gumbStanja.id = "gumb_stanja_nep";
    dodajFunkcionalnostGumbu(gumbStanja, id, stanje, razlog, imePriimek, datumStr);
    //posamezniNapisZgumbomDiv.append(gumbStanja);
    obdelovaniDiv.append(posamezniNapisZgumbomDiv);
    obdelovaniDiv.style.display = 'block';
};



const uprKoledar = () => {
    let prviDanMeseca = new Date(trenutnoLeto, trenutniMesec, 1).getDay();
    prviDanMeseca = (prviDanMeseca === 0) ? 6 : prviDanMeseca - 1;
    let zadnjiDatumMeseca = new Date(trenutnoLeto, trenutniMesec + 1, 0).getDate();
    let zadnjiDanMeseca = new Date(trenutnoLeto, trenutniMesec, zadnjiDatumMeseca).getDay();
    console.log("Zadnji dan:" + zadnjiDanMeseca);
    let zadnjiDatumMesecaPrej = new Date(trenutnoLeto, trenutniMesec, 0).getDate();

    const aktivniFiltri = Array.from(document.querySelectorAll(".filterMoznost:checked")).map(el => el.value);

    const stVsehCelic = prviDanMeseca + zadnjiDatumMeseca + (zadnjiDanMeseca === 0 ? 0 : 7 - zadnjiDanMeseca);
    const tedniMeseca = Math.ceil(stVsehCelic / 7);

    vsiDneviDiv.style.setProperty('--num-rows', tedniMeseca);

    vsiDneviDiv.innerHTML =  "";

    for(let dan = prviDanMeseca; dan > 0; dan--) {
        let danDiv = document.createElement("div");
        danDiv.classList.add("dan");

        let stevilkaDiv = document.createElement("div");
        stevilkaDiv.classList.add("dan_stevilka", "neaktivni_dan_stevilka");
        stevilkaDiv.innerHTML = zadnjiDatumMesecaPrej - dan + 1;

        let stDneva = zadnjiDatumMesecaPrej - dan + 1;
        let celDat = new Date(trenutnoLeto, trenutniMesec - 1, stDneva);

        danDiv.dataset.date = formatirajDatum(celDat);

        let izmenaDiv = document.createElement("div");
        izmenaDiv.classList.add("izmena");

        let infoDiv = document.createElement("div");
        infoDiv.classList.add("dan_info", "neaktivni_dan_info");

        stevilkaDiv.appendChild(izmenaDiv);
        danDiv.appendChild(stevilkaDiv);
        danDiv.appendChild(infoDiv);

        vsiDneviDiv.appendChild(danDiv); 
    }  

    for (let dan = 1; dan <= zadnjiDatumMeseca; dan++) {
        let danDiv = document.createElement("div");
        danDiv.classList.add("dan");

        let danesDan = dan === danasnjiDatum.getDate() && 
               trenutniMesec === danasnjiDatum.getMonth() && 
               trenutnoLeto === danasnjiDatum.getFullYear() ? "aktivni" : "";

        let trenutniDanDat = new Date(trenutnoLeto, trenutniMesec, dan);
        danDiv.dataset.date = formatirajDatum(trenutniDanDat);

        let datumStr = formatirajDatum(trenutniDanDat);

        let izmenaDan = pridobiIzmenoZaDan(trenutniDanDat);
        let izmenaNoc = pridobiIzmenoZaNoc(trenutniDanDat);

        let stevilkaDiv = document.createElement("div");
        stevilkaDiv.classList.add("dan_stevilka");
        if (danesDan) stevilkaDiv.classList.add(danesDan);
        stevilkaDiv.innerHTML = dan;

        let izmenaDiv = document.createElement("div");
        izmenaDiv.classList.add("izmena");

        let dnevnaStDiv = document.createElement("div");
        dnevnaStDiv.classList.add("izmena_stevilka", "dnevna_izmena_stevilka");

        dnevnaStDiv.innerHTML = izmenaDan;

        let nocnaStDiv = document.createElement("div");
        nocnaStDiv.classList.add("izmena_stevilka", "nocna_izmena_stevilka");

        nocnaStDiv.innerHTML = izmenaNoc;

        dnevnaStDiv.id = `izmena${izmenaDan}dan`;

        nocnaStDiv.id = `izmena${izmenaNoc}noc`;

        let infoDiv = document.createElement("div");
        infoDiv.classList.add("dan_info");

        let oznakaDNSLUdiv = document.createElement("div");
        oznakaDNSLUdiv.classList.add("info_dnevna_sluzba");

        let napisDNSLU = document.createElement("p");
        napisDNSLU.classList.add("napis_oznake", "napis_oznake_dnslu");
        napisDNSLU.innerHTML = `Dnevna služba:`;
        oznakaDNSLUdiv.append(napisDNSLU);
        infoDiv.appendChild(oznakaDNSLUdiv);

        let oznakaDNIZMdiv = document.createElement("div");
        oznakaDNIZMdiv.classList.add(`info_dnevna_izmena`, `info_dnevna_izmena${izmenaDan}`);

        let napisDNIZM = document.createElement("p");
        napisDNIZM.classList.add("napis_oznake");
        napisDNIZM.innerHTML = `Dnevna izmena:`;

        oznakaDNIZMdiv.append(napisDNIZM);
        infoDiv.appendChild(oznakaDNIZMdiv);



        let oznakaNOIZMdiv = document.createElement("div");
        oznakaNOIZMdiv.classList.add("info_nocna_izmena", `info_nocna_izmena${izmenaNoc}`);

        let napisNOIZM = document.createElement("p");
        napisNOIZM.classList.add("napis_oznake");
        napisNOIZM.innerHTML = `Nočna izmena:`; 

        oznakaNOIZMdiv.append(napisNOIZM);
        infoDiv.appendChild(oznakaNOIZMdiv);

        let vsiZapisi = [];

        if (odsotniDnevi[datumStr]) {
            vsiZapisi.push(...odsotniDnevi[datumStr]);
        }
        if (prerDneviOds[datumStr]) {
            vsiZapisi.push(...prerDneviOds[datumStr]);
        }
        if (prerDneviDel[datumStr]) {
            vsiZapisi.push(...prerDneviDel[datumStr]);
        }

        vsiZapisi.sort((a, b) => {

            return new Date(a.datVpisa) - new Date(b.datVpisa);
        });

        vsiZapisi.forEach(vnos => {
            const filterTarget = vnos.delovnaSkupinaDel || vnos.delovnaSkupina;
            
            if (aktivniFiltri.includes(filterTarget)) {

                let targetDiv;
                if (vnos.koledarskaOznaka === 'DN-SLU') targetDiv = oznakaDNSLUdiv;
                else if (vnos.koledarskaOznaka === 'DN-IZM') targetDiv = oznakaDNIZMdiv;
                else if (vnos.koledarskaOznaka === 'NO-IZM') targetDiv = oznakaNOIZMdiv;


                if (vnos.razlog === 'PRER-ODS' || vnos.razlog === 'PRER-DEL') {
                    let cssPripona = vnos.koledarskaOznaka === 'NO-IZM' ? "noc" : "dan";
                    dodajPrerazporeditevVkoledar(vnos.razlog, vnos.razlogNaziv, vnos.imePriimek, vnos.stanje, vnos.id, datumStr, targetDiv, vnos.delovnaSkupina, cssPripona, vnos.nepopolno);
                } else {
                    dodajOdsotnostVkoledar(vnos.razlog, vnos.razlogNaziv, vnos.imePriimek, vnos.stanje, vnos.id, datumStr, targetDiv);
                }
            }
        });

        izmenaDiv.appendChild(dnevnaStDiv);
        izmenaDiv.appendChild(nocnaStDiv);
        stevilkaDiv.appendChild(izmenaDiv);
        danDiv.appendChild(stevilkaDiv);
        danDiv.appendChild(infoDiv);

        vsiDneviDiv.appendChild(danDiv);

    }

    if (zadnjiDanMeseca !== 0) {
        for (let dan = zadnjiDanMeseca; dan < 7; dan++) {
        let danDiv = document.createElement("div");
        danDiv.classList.add("dan");

        let stevilkaDiv = document.createElement("div");
        stevilkaDiv.classList.add("dan_stevilka", "neaktivni_dan_stevilka");
        stevilkaDiv.innerHTML = dan - zadnjiDanMeseca + 1;

        let stDneva = dan - zadnjiDanMeseca + 1;
        let celDat = new Date(trenutnoLeto, trenutniMesec + 1, stDneva);

        danDiv.dataset.date = formatirajDatum(celDat);

        let izmenaDiv = document.createElement("div");
        izmenaDiv.classList.add("izmena");

        let infoDiv = document.createElement("div");
        infoDiv.classList.add("dan_info");

        stevilkaDiv.appendChild(izmenaDiv);
        danDiv.appendChild(stevilkaDiv);
        danDiv.appendChild(infoDiv);

        vsiDneviDiv.appendChild(danDiv);
        }
    }

    
    trenutniDatum.innerHTML = `${meseci[trenutniMesec]} ${trenutnoLeto}`;
}

uprKoledar();

prejPolSimboli.forEach(simbol => {
    simbol.addEventListener("click", () => {
        trenutniMesec = simbol.id === "prej" ? trenutniMesec - 1 : trenutniMesec + 1;

       if (trenutniMesec < 0) {
            trenutniMesec = 11;
            trenutnoLeto--;
        } else if (trenutniMesec > 11) {
            trenutniMesec = 0;
            trenutnoLeto++;
        }

        uprKoledar();
    });
});

gumbNazajStanje.addEventListener("click", () => {
        podlagaIzbirnika.style.display="none";
});

// gumbShraniStanje.addEventListener("click", () => {
//     const izbranoStanje = document.querySelector("input[name='stanje']:checked").value;

//     if(trenutnaOdsotnost) {
//         fetch("procesirajStanjeOdsotnosti.php", {
//             method: "POST",
//             headers: { "Content-Type": "application/x-www-form-urlencoded" },
//             body: `id=${encodeURIComponent(trenutnaOdsotnost)}&stanje=${encodeURIComponent(izbranoStanje)}`
//         })
//         .then(response => response.json()) // Change to .json() to handle your PHP response
//         .then(data => {
//             if (data.status === "success") {
//                 // Map the radio value back to the database koda
//                 const novaKoda = izbranoStanje === "potrdi" ? "SO-POT" : 
//                                 (izbranoStanje === "zavrni" ? "SO-ZAV" : "SO-NEP");

//                 // Update the local data object so the change persists in the UI
//                 Object.keys(odsotniDnevi).forEach(datum => {
//                     odsotniDnevi[datum].forEach(vnos => {
//                         if (vnos.id == trenutnaOdsotnost) {
//                             vnos.stanje = novaKoda;
//                         }
//                     });
//                 });

//                 podlagaIzbirnika.style.display = "none";
                
//                 // Re-render the calendar. This uses the current 'trenutniMesec' 
//                 // and 'trenutnoLeto' variables, so you don't jump months.
//                 uprKoledar(); 
//             }
//         })
//         .catch(error => console.error('Napaka:', error));
//     }
// });

document.querySelectorAll(".filterMoznost").forEach(checkbox => {
    checkbox.addEventListener("change", () => {
        uprKoledar();
    });
});

