
$(document).ready(function () {
    $('#table-datatables').DataTable();
});


document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-details');
    const detailsTable = document.querySelector('#details-table tbody');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');

            // Efface les anciennes données
            detailsTable.innerHTML = '';

            // Requête AJAX pour charger les détails
            fetch(`/statcontratmont/detail/ajax/${userId}`).then(response => response.json()).then(data => {
                data.forEach(contrat => {
                    const row = `
                                    <tr>

                                     <td>${contrat.client
                        }</td>
                                        <td>${contrat.dateSouscrpt
                        }</td>
                                        <td>${contrat.frais
                        }</td>
                                        <td>${contrat.firstReglement
                        }</td>
                                        <td>${contrat.secondReglement
                        }</td>
                                       
                                    </tr>
                                `;
                    detailsTable.innerHTML += row;
                    $('#details-container').show();
                });
            }).catch(error => console.error('Erreur:', error));
        });
    });
});