/* ============================= */
/* main.js - mentés gombbal és drag&drop */
/* ============================= */

let draggedItem = null;

/* Eredeti állapot minden termékről */
let eredetiRekeszAllapot = {};
let eredetiAllapot = {};

/* Oldal betöltésekor eltároljuk az induló állapotot */
document.addEventListener("DOMContentLoaded", () => {

    // Mentés gomb kiválasztása
    const mentesGomb = document.getElementById("mentes-gomb");
    if (mentesGomb) mentesGomb.disabled = true; // alapból inaktív

    // Eredeti állapot mentése és drag események hozzárendelése
    document.querySelectorAll(".termek-item").forEach(item => {
        const termekId = item.dataset.termekId;
        if(!termekId) return;

        const container = item.closest(".termekek-container");
        if(!container) return;

        eredetiRekeszAllapot[termekId] = parseInt(container.dataset.rekeszId);

        const nevInput = item.querySelector("input[name='nev']");
        const mennyInput = item.querySelector(".menny-box input");
        const lejaratInput = item.querySelector("input[name='lejarati_datum']");
        const csoportInput = item.querySelector("input[name='termek_csoport']");
        const vasarlasInput = item.querySelector("input[name='vasarlasi_datum']");

        eredetiAllapot[termekId] = {
            nev: nevInput ? nevInput.value : "",
            mennyiseg: mennyInput ? parseFloat(mennyInput.value) : 0,
            lejarati_datum: lejaratInput ? lejaratInput.value : "",
            termek_csoport: csoportInput ? csoportInput.value : "",
            vasarlasi_datum: vasarlasInput ? vasarlasInput.value : ""
        };

        // Drag attribútum
        item.setAttribute("draggable", true);
        item.addEventListener("dragstart", dragStart);
        item.addEventListener("dragend", dragEnd);

        // Módosításkor aktiváljuk a mentés gombot
        item.querySelectorAll("input").forEach(inp => {
            inp.addEventListener("change", aktivAlapmentesGomb);
        });
    });

    // Drag & Drop események a rekeszekre
    document.querySelectorAll(".rekesz").forEach(r => {
        r.addEventListener("dragover", e => { e.preventDefault(); r.classList.add("drag-over"); });
        r.addEventListener("dragleave", () => { r.classList.remove("drag-over"); });
        r.addEventListener("drop", e => {
            e.preventDefault();
            r.classList.remove("drag-over");

            const container = r.querySelector(".termekek-container");
            if(draggedItem && container) {
                container.appendChild(draggedItem);
                aktivAlapmentesGomb();
            }
        });
    });

    // Lejárati dátum jelölés
    jeloldLejaroTermekeket();

    // Mentés gomb esemény
    if (mentesGomb) {
        mentesGomb.addEventListener("click", () => {
            mentesGombClick(mentesGomb);
        });
    }
});

/* ============================= */
/* Drag & Drop funkciók */
/* ============================= */
function dragStart(e) {
    draggedItem = e.target.closest(".termek-item");
    if(draggedItem) draggedItem.classList.add("dragging");
}
function dragEnd(e) {
    if(draggedItem) draggedItem.classList.remove("dragging");
    draggedItem = null;
}

/* Rekesz kihúzás */
function kihuzaRekesz(r) { r.classList.toggle("kihuza"); }

/* +- gomb funkció */
function modosit(event, ertek){
    event.stopPropagation();

    const elem = event.target.closest(".termek-item");
    if(!elem) return;

    const szam = elem.querySelector(".mennyiseg-szam");
    if(!szam) return;

    let uj = parseFloat(szam.textContent) + ertek;
    if(uj < 0) uj = 0;
    szam.textContent = Math.round(uj);

    aktivAlapmentesGomb();
}

/* Lejárati dátum jelölés */
function jeloldLejaroTermekeket(){
    const ma = new Date();
    ma.setHours(0,0,0,0);

    document.querySelectorAll(".termek-item").forEach(item => {
        const lejaratStr = item.dataset.lejarat;
        if(!lejaratStr) return;

        const lejarat = new Date(lejaratStr);
        lejarat.setHours(0,0,0,0);

        const kulonbseg = (lejarat - ma)/(1000*60*60*24);

        item.classList.remove("lejaro","lejart");

        if(kulonbseg < 0) item.classList.add("lejart");
        else if(kulonbseg <= 14) item.classList.add("lejaro");
    });
}

/* ============================= */
/* Mentés gomb funkció AJAX-sal */
/* ============================= */
function mentesGombClick(mentesGomb){
    const valtozott = {};

    document.querySelectorAll(".termek-item").forEach(item => {
        const termekId = item.dataset.termekId;
        if(!termekId) return;

        const container = item.closest(".termekek-container");
        if(!container) return;

        const rekeszId = parseInt(container.dataset.rekeszId);

        const nevInput = item.querySelector("input[name='nev']");
        const mennyInput = item.querySelector(".menny-box input");
        const lejaratInput = item.querySelector("input[name='lejarati_datum']");
        const csoportInput = item.querySelector("input[name='termek_csoport']");
        const vasarlasInput = item.querySelector("input[name='vasarlasi_datum']");

        const ujMezok = {
            rekesz_id: rekeszId,
            nev: nevInput ? nevInput.value : "",
            mennyiseg: mennyInput ? parseFloat(mennyInput.value) : 0,
            lejarati_datum: lejaratInput ? lejaratInput.value : "",
            termek_csoport: csoportInput ? csoportInput.value : "",
            vasarlasi_datum: vasarlasInput ? vasarlasInput.value : ""
        };

        if(!eredetiAllapot[termekId]) {
            eredetiAllapot[termekId] = { ...ujMezok };
            eredetiRekeszAllapot[termekId] = rekeszId;
        }

        if(JSON.stringify(ujMezok) !== JSON.stringify(eredetiAllapot[termekId]) ||
           rekeszId !== eredetiRekeszAllapot[termekId]) {
            valtozott[termekId] = ujMezok;
            eredetiAllapot[termekId] = { ...ujMezok };
            eredetiRekeszAllapot[termekId] = rekeszId;
        }
    });

    if(Object.keys(valtozott).length === 0){
        alert("Nincs változás mentésre.");
        return;
    }

    // WordPress AJAX URL és nonce közvetlen használata
    fetch(ajaxurl, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            action: "rekesz_mentes",
            security: rekeszNonce,
            adatok: JSON.stringify(valtozott)
        })
    })
    .then(resp => resp.json())
    .then(data => {
        if(!data.success) alert("Hiba történt a mentés során: " + data.data);
        else alert("Sikeres mentés!");
        // Mentés után gomb inaktív
        if(mentesGomb) mentesGomb.disabled = true;
    })
    .catch(err => { alert("Hiba történt a mentés során: " + err); });
}

/* Mentés gomb aktiváló */
function aktivAlapmentesGomb(){
    const mentesGomb = document.getElementById("mentes-gomb");
    if(mentesGomb) mentesGomb.disabled = false;
}