window.onload = () => {
    // Gestion des liens "Supprimer"
    let links = document.querySelectorAll("[data-delete]")
    let files = document.querySelectorAll("[file-delete]")

    //boucle sur links
    for(link of links){
        link.addEventListener("click", function(e){
            // empécher la navigation
            e.preventDefault()

            // Confirmation suppression
            if(confirm("Voulez-vous supprimer cette image ?")){
                // On envoie une requête Ajax vers le href du lien avec la méthode DELETE
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers:{
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    // récupération de réponse en json
                    response => response.json()
                ).then(data => {
                    if(data.success)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch( e => alert(e))
            }
        })

    }
    
    //boucle sur files
    for(file of files){
        file.addEventListener("click", function(e){
            // empécher la navigation
            e.preventDefault()

            // Confirmation suppression
            if(confirm("Voulez-vous supprimer ce manuel ?")){
                // On envoie une requête Ajax vers le href du lien avec la méthode DELETE
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers:{
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    // récupération de réponse en json
                    response => response.json()
                ).then(data => {
                    if(data.success)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch( e => alert(e))
            }
        })

    }
    
}