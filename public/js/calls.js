// calls.js
document.addEventListener('DOMContentLoaded', function () {
    // Vérifier si le bouton existe avant d'ajouter l'événement
    const showCallsButton = document.getElementById('showCallsButton');

    if (showCallsButton) {
        showCallsButton.addEventListener('click', function () {
            const prospectId = this.dataset.id;
            const callsContent = document.getElementById('callsContent');

            // Afficher un indicateur de chargement
            if (callsContent) {
                callsContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement des appels...</div>';
            }

            // Faire la requête pour récupérer les appels
            fetch(`/appels/${prospectId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    if (callsContent) {
                        callsContent.innerHTML = data;
                    }
                })
                .catch(error => {
                    console.error('Error fetching calls:', error);
                    if (callsContent) {
                        callsContent.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Erreur lors du chargement des appels. Veuillez réessayer.
                                <br><small>Détails: ${error.message}</small>
                            </div>
                        `;
                    }
                });
        });
    } else {
        console.warn('Bouton showCallsButton non trouvé dans le DOM');
    }
});