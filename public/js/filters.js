const FiltersForm = document.querySelector("#filters");

    document.querySelectorAll("#filters input").forEach(input => {
        input.addEventListener("change", () =>{
            // Récupération données du formulaire
            const Form = new FormData(FiltersForm);

            // Fabrication de ce qu'il y a après le "?" de l'URL
            const Params = new URLSearchParams();

            Form.forEach((value, key) => {
                Params.append(key, value);
            });

            // Récuperation de l'URL active
            const Url = new URL(window.location.href);
            
            // Lancement de la requête Ajax
            fetch(Url.pathname + "?" + Params.toString() + "&ajax=1",{
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response => {
                console.log(response)
            }).catch(e => alert(e));
        });
    });