document.getElementById('showCallsButton').addEventListener('click', function () {
    const prospectId = this.dataset.id;

    fetch(`/traiter/appels/${prospectId}`) // Replace with your actual route for fetching calls
        .then(response => response.text())
        .then(data => {
            document.getElementById('callsContent').innerHTML = data;
        })
        .catch(error => {
            console.error('Error fetching calls:', error);
            document.getElementById('callsContent').innerHTML = 'Error loading calls.';
        });
});

// $(document).ready(function () {
//     $('#showCallsButton').on('click', function () {
//         var prospectId = $(this).data('id');

//         $.ajax({
//             url: '/show/appels/${prospectId}',
//             method: 'GET',
//             success: function (response) {
//                 $('#callsContent').html(response);
//                 $('#callsModal').modal('show');
//             },
//             error: function () {
//                 $('#callsContent').html('<p>Erreur lors du chargement des appels.</p>');
//             }
//         });
//     });
// });




// function loadProspectCalls(prospectId) {
//     fetch(`/show/prospect/${prospectId}/appels`)
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Erreur lors du chargement des appels.');
//             }
//             return response.text();
//         })
//         .then(html => {
//             document.getElementById('modal-body').innerHTML = html;
//             $('#callsModal').modal('show');
//         })
//         .catch(error => {
//             console.error('Erreur:', error);
//             alert('Erreur lors du chargement des appels.');
//         });
// }

// $(document).ready(function () {
//     $('#appelsModal').on('show.bs.modal', function (event) {
//         var button = $(event.relatedTarget);
//         var prospectId = button.data('prospect-id');
//         var modal = $(this);

//         // Afficher un spinner ou un message de chargement
//         modal.find('.modal-body').html('<p>Chargement...</p>');

//         // Effectuer la requête AJAX
//         $.ajax({
//             url: '/prospect/' + prospectId + '/appels',
//             method: 'GET',
//             success: function (response) {
//                 modal.find('.modal-body').html(response.html);
//             },
//             error: function () {
//                 modal.find('.modal-body').html('<p>Erreur lors du chargement des appels.</p>');
//             }
//         });
//     });
// });


// document.getElementById('showCallsButton').addEventListener('click', function () {
//     const prospectId = this.dataset.id;

//     fetch(`show/appels/${prospectId}`) // Replace with your actual route for fetching calls
//         .then(response => response.text())
//         .then(data => {
//             document.getElementById('callsContent').innerHTML = data;
//         })
//         .catch(error => {
//             console.error('Error fetching calls:', error);
//             document.getElementById('callsContent').innerHTML = 'Error loading calls.';
//         });
// });

// $(document).ready(function () {
//     $('#callsModal').on('show.bs.modal', function (event) {
//         var button = $(event.relatedTarget);
//         var prospectId = button.data('id');

//         $.ajax({
//             url: 'show/appels/' + prospectId,
//             method: 'GET',
//             success: function (data) {
//                 $('#callsContent').html(data);
//             },
//             error: function () {
//                 $('#callsContent').html('Erreur lors du chargement des appels.');
//             }
//         });
//     });
// });


