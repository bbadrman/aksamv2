

// START Fetching Users Active //
window.onload = () => {

	let activer = document.querySelectorAll("[type=checkbox]")
	for (let bouton of activer) {
		bouton.addEventListener("click", function () {
			let xmlhttp = new XMLHttpRequest;

			xmlhttp.open("get", `/user/activer/${this.dataset.id}`)
			xmlhttp.send();

		})
	}
}



// START Fetching Groups //
function fetchGroups() {
	$.ajax({
		type: "GET",
		url: "/team",
		dataType: "json",
		success: function (response) {
			let listGroups = '';

			$.each(response.team, function (key, item) {
				listGroups += `<tr>
											<td>${item.id
					}</td>
											<td>${item.name
					}</td>
											<td>${item.description
					}</td>
											<td class="btn-toolbar">
											<button href="#"  value="${item.id
					}" class="show_groupe btn btn-success btn-xs"> <i class="fa fa-eye"></i></button>
											<button href="#"  value="${item.id
					}" class="edit_groupe btn btn-primary btn-xs"> <i class="fa fa-pencil"></i></button>
											<button href="#"  value="${item.id
					}" class="delete_groupe btn btn-danger btn-xs"> <i class="fa fa-trash"></i></button>
											</td>
										</tr>`;
			});
			groupList.html(listGroups);
		}
	});
}
// END Fetching Groups //

// START Adding a Team //

$(document).on('click', '.team_new', function (e) {
	e.preventDefault();
	const data = {
		'name': $('#team_name').val(),

		'description': $('.description').val(),
	};

	let AddGroupModal = $('#AddGroupeModal');

	$.ajax({
		type: "POST",
		url: "/team/new-team",
		data: data,
		dataType: "json",
		success: function (response) {

			if (response.status === 400) {
				$('#saveform_errList').html("").addClass('alert alert-danger');
				$.each(response.errors, function (key, err_values) {
					$('#saveform_errList').append('<li>' + err_values + '</li>');


				});
			} else {
				$('#saveform_errList').html("").removeClass('alert alert-danger');
				$('#success_message').addClass('alert alert-success').text(response.message);
				$('#user_teams').append('<option value="1" selected="selected">' + $('#team_name').val() + '</option>');
				AddGroupModal.modal('hide').find('#team_name').val("").find('#team_description').val("");

				fetchGroups();
			}
			// window.location.reload();
		}
	})
});

// END Adding a Team //


// START adding a Product //

$(document).on('click', '.product_new', function (e) {
	e.preventDefault();

	const data = {
		'nom': $('#product_nom').val(),

		'description': $('.description').val()
	};
	// console.log(data);

	let AddProductModal = $('#AddProductModal');

	$.ajax({
		type: "POST",
		url: "/product/new-product",
		data: data,
		dataType: "json",
		success: function (response) {
			if (response.status === 400) {
				$('#saveform_errList').html("").addClass('alert alert-danger');
				$.each(response.errors, function (key, err_values) {
					$('#saveform_errList').append('<li>' + err_values + '</li>');
				});
			} else {
				$('#saveform_errList').html("").removeClass('alert alert-danger');
				$('#success_message').addClass('alert alert-success').text(response.message);
				$('#user_products').append('<option value="1" selected="selected">' + $('#product_nom').val() + '</option>');
				AddProductModal.modal('hide').find('#product_nom').val("").find('#product_description').val("");

			}
		}
	})
});

// END adding a Product //

// START adding a Client //

// $(document).on('click', '.clients_add', function (e) {
// 	e.preventDefault();

// 	const data = {
// 		'firstname': $('#client_firstname').val(),

// 		'lastname': $('.client_lastname').val(),
// 		'phone': $('.client_phone').val(),
// 		'email': $('.client_email').val(),
// 		'raisonSociale': $('.client_raisonSociale').val()
// 	};

// 	let AddClientModal = $('#AddClientModal');

// 	$.ajax({
// 		type: "POST",
// 		url: "/client/new-client",
// 		data: data,
// 		dataType: "json",
// 		success: function (response) {
// 			if (response.status === 400) {
// 				$('#saveform_errList').html("").addClass('alert alert-danger');
// 				$.each(response.errors, function (key, err_values) {
// 					$('#saveform_errList').append('<li>' + err_values + '</li>');
// 				});
// 			} else {
// 				$('#saveform_errList').html("").removeClass('alert alert-danger');
// 				$('#success_message').addClass('alert alert-success').text(response.message);

// 				AddClientModal.modal('hide').find('#client_firstname').val("").find('#client_lastname').find('#client_phone').find('#client_email').find('#client_raisonSociale').val("");

// 			}
// 		}
// 	})
// });

// START adding a Fonction //

$(document).on('click', '.fonction_new', function (e) {
	e.preventDefault();

	const data = {

		'name': $('#fonction_name').val(),
		'description': $('.description').val()
		// 'description': $('.description').val()
	};
	// console.log(data);

	let AddFonctionModal = $('#AddFonctionModal');

	$.ajax({
		type: "POST",
		url: "/fonctions/new-fonction",
		data: data,
		dataType: "json",
		success: function (response) {
			// console.log(response);
			if (response.status === 400) {
				$('#saveform_errList').html("").addClass('alert alert-danger');
				$.each(response.errors, function (key, err_values) {
					console.log(response);
					$('#saveform_errList').append('<li>' + err_values + '</li>');
				});
			} else {
				$('#saveform_errList').html("").removeClass('alert alert-danger');
				$('#success_message').addClass('alert alert-success').text(response.message);
				$('#user_fonctions').append('<option value="1" selected="selected">' + $('#fonction_name').val() + '</option>');
				AddFonctionModal.modal('hide').find('#fonction_name').val("").find('#fonction_description').val("");

			}
		}
	})
});

// END adding a Fonction //









// $(document).ready(function() {
// 	var $locationSelector = $('user_teams');
// 	var $specificLocationTarget = $('user_products');

// 	$locationSelector.on('change', function(e){
// 		$.ajax({
// 			url: $locationSelector.data('specification'),
// 			data: {
// 				location: $locationSelect.val()
// 			},
// 			success: function(html) {
// 				if(!html){
// 					$specificLocationTarget.find('select').remove();
// 					$specificLocationTarget.addClass('d-none');

// 					return;
// 				}

// 				$specificLocationTarget
// 				.html(html)
// 				.removeClass('d-none');
// 			}
// 		});
// 	});
// });


// pour cache le tableau prospect
$(document).ready(function () {
	$("#shrch").hide();  // masquer la div au chargement de la page
	$("#searchBtn").click(function () {  // ajouter un écouteur d'événements sur le bouton de recherche
		$("#shrch").show();  // afficher la div lorsque le bouton de recherche est cliqué
	});
});

$('document').ready(function () {
	$("#shrch").hide()

});


//Select Dynamique with API  

// $(document).ready(function () {
// 	handleTeamChange('#prospect_team', '#prospect_comrcl');
// 	handleTeamChange('#prospect_affect_team', '#prospect_affect_comrcl');
// 	function handleTeamChange(teamId, comercialId) {
// 		const prospectTeam = $(teamId);
// 		const prospectCommercial = $(comercialId);
// 		if (prospectTeam.length && prospectCommercial.length) {
// 			if (!prospectTeam.val().length) {
// 				prospectCommercial.parent().hide();

// 			} else {
// 				loadCommercials();
// 			}
// 			function loadCommercials() {
// 				const currentValue = prospectTeam.val();
// 				const commercialvalue = prospectCommercial.val();

// 				if (!currentValue.length) {
// 					return;
// 				}
// 				$.ajax({
// 					url: "/team/teams-api", success: function (result) {
// 						prospectCommercial.empty()
// 						const options = result.find(function (item) {
// 							return item.id == currentValue;
// 						});
// 						prospectCommercial.append(new Option());

// 						options?.commercials?.map(function (item) {
// 							prospectCommercial.append(new Option(item.username, item.id));
// 						})
// 						prospectCommercial.val(commercialvalue).change();
// 						prospectCommercial.parent().show();
// 						console.log('RESULT', options);
// 					}
// 				});
// 			}
// 			prospectTeam.change(function () {
// 				loadCommercials();
// 			})
// 			console.log('HEre im 2')

// 		}
// 	}
// })

//select dynamique


// document.addEventListener('DOMContentLoaded', function () {
// 	const teamSelectEl = document.getElementById('prospect_team');
// 	teamSelectEl.addEventListener('change', function (e) {
// 		// console.log('okok');
// 		const formEl = teamSelectEl.closest('form');
// 		// console.log("formEl: " + formEl);

// 		fetch(formEl.action, {
// 			method: formEl.method,
// 			body: new FormData(formEl)
// 		})
// 			.then(response => response.text())
// 			.then(html => {


// 				const parser = new DOMParser();
// 				const doc = parser.parseFromString(html, 'text/html');
// 				const newComrclFormFieldEl = doc.getElementById('prospect_comrcl');


// 				newComrclFormFieldEl.addEventListener('change', function (e) {
// 					e.target.classList.remove('is-invalid');
// 				});
// 				document.getElementById('prospect_comrcl').replaceWith(newComrclFormFieldEl);

// 			})
// 			.catch(function (err) {
// 				console.warn('Something went wrong.', err);
// 			});
// 	});
// });



// form prospect  select 
// function setupDisplayControl(fieldId, containerId, targetValue) {
// 	var field = document.getElementById(fieldId);
// 	var container = document.getElementById(containerId);
// 	if (field !== null && container !== null) {
// 		field.addEventListener('change', function () {
// 			container.style.display = field.value === targetValue ? 'block' : 'none';
// 		});
// 	}
// }

// // Configuration des différents champs de formulaire
// setupDisplayControl('prospect_typeProspect', 'subcategory-container', '2');
// setupDisplayControl('prospect_lastAssure', 'subResil-container', 'Oui');





//select motiveRelanced pour afficher la ajouter client quand on click sur pasage contart
//select motiveRelanced pour afficher la calandrie et comment quand on click sur rndv
$(document).ready(function () {
	$('.modal').on('shown.bs.modal', function () {
		var resilField = $(this).find('.relance_prospect_relance');
		var commentField = $(this).find('.MotivRelcoment-container');
		var buttonField = $(this).find('.SubmitRelance-container');
		var subresilContainer1 = $(this).find('.subMotivRelc-container');
		var subresilContainer2 = $(this).find('.subMotivContrat-container');

		resilField.change(function () {
			var value = $(this).val();
			if (value === '1' || value === '2' || value === '3' || value === '4' || value === '5') {
				subresilContainer1.show();
				subresilContainer2.hide();
				commentField.show();
				buttonField.show();
			} else if (value === '6' || value === '7' || value === '8' || value === '10' || value === '11' || value === '12' || value === '13') {
				subresilContainer1.hide();
				subresilContainer2.hide();
				commentField.show();
				buttonField.show();
			} else if (value === '9') {
				subresilContainer1.hide();
				subresilContainer2.show();
				commentField.hide();
				buttonField.hide();
			} else {
				subresilContainer1.hide();
				subresilContainer2.hide();
				commentField.hide();
				buttonField.show();
			}
		});
	});
});


// Select du Transaction

var motifField = document.getElementById('transaction_motif');
var debitField = document.getElementById('transaction_debit');
var creditField = document.getElementById('transaction_credit');



if (motifField !== null) {
	motifField.addEventListener('change', function () {
		if (motifField.value === '1er reglement' || motifField.value === '2eme reglement' || motifField.value === '3eme reglement') {
			creditField.style.display = 'block';
			debitField.style.display = 'none';

		} else if (motifField.value === 'Remboursement' || motifField.value === 'ContrepartiE') {
			creditField.style.display = 'none';
			debitField.style.display = 'block';

		} else {
			creditField.style.display = 'none';
			debitField.style.display = 'none';

		}
	});

}



// Select du Contrat
// var contratypeField = document.getElementById('contrat_type');
// var produitField = document.getElementById('contrat_products');
// var typeCondField = document.getElementById('contrat_typeConducteur');
// var CondField = document.getElementById('contrat_conducteur');
// var datePermField = document.getElementById('contrat_datePermis');
// var activtField = document.getElementById('contrat_activite');
// var raisonField = document.getElementById('contrat_raisonSociale');
// var imatricltField = document.getElementById('contrat_imatriclt');

// function updateFields() {
// 	var contratType = contratypeField ? contratypeField.value : null;
// 	var produitType = produitField ? produitField.value : null;

// 	if (contratType === 'Particulier' && (produitType === '1' || produitType === '10' || produitType === '12')) {
// 		CondField.style.display = 'block';
// 		datePermField.style.display = 'block';
// 		activtField.style.display = 'none';
// 		raisonField.style.display = 'none';
// 		imatricltField.style.display = 'block';
// 		typeCondField.style.display = 'block';
// 	} else if (contratType === 'Particulier' && (produitType === '2' || produitType === '7' || produitType === '11')) {
// 		CondField.style.display = 'none';
// 		datePermField.style.display = 'none';
// 		activtField.style.display = 'none';
// 		raisonField.style.display = 'none';
// 		imatricltField.style.display = 'none';
// 		typeCondField.style.display = 'none';
// 	} else if (contratType === 'Particulier') {
// 		CondField.style.display = 'block';
// 		datePermField.style.display = 'block';
// 		activtField.style.display = 'none';
// 		raisonField.style.display = 'block';
// 		imatricltField.style.display = 'block';
// 		typeCondField.style.display = 'block';
// 	}
// 	else if (contratType === 'Professionnel' && (produitType === '1' || produitType === '10' || produitType === '12')) {

// 		activtField.style.display = 'block';
// 		raisonField.style.display = 'block';
// 		CondField.style.display = (produitType === '1' || produitType === '10' || produitType === '12') ? 'block' : 'block';
// 		datePermField.style.display = (produitType === '1' || produitType === '10' || produitType === '12') ? 'block' : 'block';
// 		imatricltField.style.display = (produitType === '1' || produitType === '10' || produitType === '12') ? 'block' : 'block';
// 		typeCondField.style.display = (produitType === '1' || produitType === '10' || produitType === '12') ? 'block' : 'block';

// 	}
// 	else if (contratType === 'Professionnel' && (produitType === '2' || produitType === '7' || produitType === '11')) {

// 		activtField.style.display = 'block';
// 		raisonField.style.display = 'block';
// 		CondField.style.display = (produitType === '1' || produitType === '10' || produitType === '12') ? 'block' : 'none';
// 		datePermField.style.display = (produitType === '1' || produitType === '10' || produitType === '12') ? 'block' : 'none';
// 		imatricltField.style.display = (produitType === '1' || produitType === '10' || produitType === '12') ? 'block' : 'none';
// 		typeCondField.style.display = (produitType === '1' || produitType === '10' || produitType === '12') ? 'block' : 'none';

// 	}

// 	else if (contratType === 'Professionnel') {

// 		CondField.style.display = 'block';
// 		datePermField.style.display = 'block';
// 		activtField.style.display = 'block';
// 		raisonField.style.display = 'block';
// 		imatricltField.style.display = 'block';
// 		typeCondField.style.display = 'block';

// 	} else {
// 		// Default case if neither specific conditions are met
// 		CondField.style.display = 'block';
// 		datePermField.style.display = 'block';
// 		activtField.style.display = 'block';
// 		raisonField.style.display = 'block';
// 		imatricltField.style.display = 'block';
// 		typeCondField.style.display = 'block';
// 	}
// }

// if (contratypeField !== null) {
// 	contratypeField.addEventListener('change', updateFields);
// }

// if (produitField !== null) {
// 	produitField.addEventListener('change', updateFields);
// }



// if (typeCondField !== null) {
// 	typeCondField.addEventListener('change', function () {
// 		if (typeCondField.value === 'Désigné') {
// 			CondField.style.display = 'block';
// 			datePermField.style.display = 'block';

// 		} else if (typeCondField.value === 'Multiconducteur') {
// 			CondField.style.display = 'none';
// 			datePermField.style.display = 'none';

// 		} else if (typeCondField.value !== 'Désigné' && typeCondField.value !== 'Multiconducteur') {
// 			CondField.style.display = 'block';
// 			datePermField.style.display = 'block';
// 		}
// 	});

// }


// Select du Sav
// Select du Sav
var natureField = document.getElementById('sav_natureDemande');
var motifField = document.getElementById('sav_motif');

// Function to update the motif field based on the nature field
function updateFields() {
	// Get the selected value of the nature field
	var selectedNature = natureField.value;

	// Reset the motif field
	for (var i = 0; i < motifField.options.length; i++) {
		motifField.options[i].style.display = 'none'; // Hide all options
	}

	// Show specific options based on the selected nature
	if (selectedNature === '1') {
		// Show options 1, 2, and 3
		motifField.options[0].style.display = 'block'; // Option 1
		motifField.options[1].style.display = 'block'; // Option 2
		motifField.options[2].style.display = 'block'; // Option 3
		motifField.options[3].style.display = 'block'; // Option 4
	} else if (selectedNature === '2') {
		// Show options 4, 5, and 6

		motifField.options[4].style.display = 'block'; // Option 5
		motifField.options[5].style.display = 'block'; // Option 6
		motifField.options[6].style.display = 'block'; // Option 7
	} else if (selectedNature === '3') {
		// Show options 7, 8, and 9

		motifField.options[7].style.display = 'block'; // Option 8
		motifField.options[8].style.display = 'block'; // Option 9
		motifField.options[9].style.display = 'block'; // Option 10
	}
	else if (selectedNature === '4') {
		// Show options 7, 8, and 9
		motifField.options[10].style.display = 'block'; // Option 11
		motifField.options[11].style.display = 'block'; // Option 12
	}
}

// Attach event listener to the nature field
natureField.addEventListener('change', updateFields);

// Initial call to set the correct state on page load
updateFields();


// Sélection des éléments
// var typeContratField = document.getElementById('contrat_type');
// var produitField = document.getElementById('contrat_produit');

// // Options pour chaque type de contrat
// var optionsParticulier = {
// 	auto: 'Auto',
// 	sante: 'sante',
// 	camion: 'camion',
// 	construction: 'construction',
// 	rcpro: 'RC',
// 	caravane: 'Caravane',
// };

// var optionsProfessionnel = {
// 	auto: 'Auto',
// 	sante: 'sante',
// 	camion: 'camion',
// 	construction: 'construction',
// 	caravane: 'Caravane',
// };

// // Fonction pour mettre à jour les options du champ produit
// function updateProduitOptions(options) {
// 	produitField.innerHTML = ''; // Vider les anciennes options

// 	// Ajouter les nouvelles options
// 	for (const [value, label] of Object.entries(options)) {
// 		var option = document.createElement('option');
// 		option.value = value;
// 		option.text = label;
// 		produitField.appendChild(option);
// 	}
// }

// // Écouter les changements sur le champ type de contrat
// if (typeContratField !== null) {
// 	typeContratField.addEventListener('change', function () {
// 		if (typeContratField.value === 'Particulier') {
// 			updateProduitOptions(optionsParticulier);
// 		} else if (typeContratField.value === 'Professionnel') {
// 			updateProduitOptions(optionsProfessionnel);
// 		} else {
// 			produitField.innerHTML = ''; // Vider si aucune sélection
// 		}
// 	});
// }





$(document).ready(function () {
	$("#relance-div").click(function () {
		$("#relanceChoix").animate({
			height: 'toggle'
		});
	});
});


//pour afficher comment onclick sur comment sur motifrelanced	
function showFullText(element) {
	// Récupère l'élément contenant le texte complet
	var fullText = element.querySelector('.fullText');
	// Change la visibilité du texte complet au clic
	if (fullText.style.display === 'none') {
		fullText.style.display = 'inline'; // Montre le texte complet
		element.querySelector('.shortened').style.display = 'none'; // Cache le texte raccourci
	} else {
		fullText.style.display = 'none'; // Cache le texte complet
		element.querySelector('.shortened').style.display = 'inline'; // Montre le texte raccourci
	}
}

// submut deux button en meme temps
// document.addEventListener('DOMContentLoaded', function () {
// 	const relanceSubmitButton = document.querySelector('#relance-submit');
// 	const clientSubmitButton = document.querySelector('#client-submit');
// 	const clientForm = document.querySelector('#client-form');

// 	clientSubmitButton.addEventListener('click', async (event) => {
// 		event.preventDefault(); // Empêche la soumission par défaut du formulaire client
// 		await submitForm(clientForm); // Soumet le formulaire client manuellement
// 		await delay(100); // Attente de 100 ms pour laisser le temps à la soumission de se terminer
// 		relanceSubmitButton.click(); // Déclenche la soumission du formulaire de relance
// 	});

// 	async function submitForm(form) {
// 		try {
// 			await fetch(form.action, {
// 				method: form.method,
// 				body: new FormData(form)
// 			});
// 		} catch (error) {
// 			console.error('Une erreur s\'est produite :', error);
// 		}
// 	}

// 	function delay(ms) {
// 		return new Promise(resolve => setTimeout(resolve, ms));
// 	}
// });
// submut deux button en meme temps
document.addEventListener('DOMContentLoaded', function () {
	const relanceSubmitButton = document.querySelector('#relance-submit');
	const clientSubmitButton = document.querySelector('#client-submit');
	const clientForm = document.querySelector('#client-form');

	// Vérifiez si les éléments existent avant de les utiliser
	if (relanceSubmitButton && clientSubmitButton && clientForm) {
		clientSubmitButton.addEventListener('click', async (event) => {
			event.preventDefault(); // Empêche la soumission par défaut du formulaire client
			await submitForm(clientForm); // Soumet le formulaire client manuellement
			await delay(100); // Attente de 100 ms pour laisser le temps à la soumission de se terminer
			relanceSubmitButton.click(); // Déclenche la soumission du formulaire de relance
		});
	} else {
		console.error('Un ou plusieurs éléments nécessaires sont manquants dans le DOM.');
	}

	async function submitForm(form) {
		try {
			await fetch(form.action, {
				method: form.method,
				body: new FormData(form)
			});
		} catch (error) {
			console.error('Une erreur s\'est produite :', error);
		}
	}

	function delay(ms) {
		return new Promise(resolve => setTimeout(resolve, ms));
	}
});
// affichier les apl par modal :

// document.getElementById('showCallsButton').addEventListener('click', function () {
// 	const prospectId = this.dataset.id;

// 	fetch(`/show/appels/${prospectId}`) // Replace with your actual route for fetching calls
// 		.then(response => response.text())
// 		.then(data => {
// 			document.getElementById('callsContent').innerHTML = data;
// 		})
// 		.catch(error => {
// 			console.error('Error fetching calls:', error);
// 			document.getElementById('callsContent').innerHTML = 'Error loading calls.';
// 		});
// });

// function refreshShowalls(prospectId) {
// 	fetch(`/show/appels/${prospectId}`).then(response => response.text()).then(html => {
// 		document.getElementById('showalls-content').innerHTML = html;
// 	}).catch(error => console.error('Error fetching calls:', error));
// }



// filter table
// $(document).ready(function () {
// 	var table = $('#example').DataTable({
// 		searchPanes: true
// 	});
// 	table.searchPanes.container().prependTo(table.table().container());
// 	table.searchPanes.resizePanes();
// });


//persist le champs activite
// document.addEventListener("DOMContentLoaded", function () {
// 	var typeProspectField = document.getElementById("{{ form.typeProspect.vars.id }}");
// 	var activitesField = document.getElementById("{{ form.activites.vars.id }}");
// 	var subcategoryContainer = document.getElementById("subcategory-container");

// 	typeProspectField.addEventListener("change", function () {
// 		if (typeProspectField.value === '2') {
// 			subcategoryContainer.style.display = "block";
// 			activitesField.setAttribute('required', 'required');
// 		} else {
// 			subcategoryContainer.style.display = "none";
// 			activitesField.removeAttribute('required');
// 		}
// 	});
// });

// pour passer au path client quen on click sur passage en contart
// document.addEventListener('DOMContentLoaded', function () {
// 	const motifRelanced = document.getElementById('relanced_motifRelanced'); // Assurez-vous de remplacer 'relanced_motifRelanced' par l'ID réel de votre champ de formulaire

// 	motifRelanced.addEventListener('change', function () {
// 		const selectedValue = this.value;
// 		const prospectId = document.getElementById('relance-submit').dataset.prospectId; // Récupérer l'ID du prospect à partir du bouton

// 		if (selectedValue === '10') {
// 			window.location.href = '/client/new/'; // Redirection vers la route client avec l'ID du prospect
// 		}
// 	});
// });



//button dashbord
// function togglePanel() {
// 	var panel = document.getElementById('panell');
// 	if (panel.style.display === 'none') {
// 	  panel.style.display = 'table-row'; // ou 'block' si le parent est un tbody
// 	} else {
// 	  panel.style.display = 'none';
// 	}
// 	 var panel = document.getElementById('panel1');
// 	if (panel.style.display === 'none') {
// 	  panel.style.display = 'table-row'; // ou 'block' si le parent est un tbody
// 	} else {
// 	  panel.style.display = 'none';
// 	}
//   }



// $(document).ready(function(){
// $("#flipp").click(function(){
// 	$("#panell").slideToggle("slow");
// 	$("#panel1").slideToggle("slow");
// });
// });
// // select typeProspect

// var categoryField = document.getElementById('prospect_typeProspect');
// var subcategoryContainer = document.getElementById('subcategory-container');

// if (categoryField !== null) {
// 	categoryField.addEventListener('change', function () {
// 		if (categoryField.value === 'Professionnels') {

// 			subcategoryContainer.style.display = 'block';
// 		} else {
// 			subcategoryContainer.style.display = 'none';
// 		}
// 	});
// }



// //select motiveProspect
// var motifField = document.getElementById('prospect_source');
// var submotifContainer = document.getElementById('subMotiv-container');
// if (motifField !== null) {
// 	motifField.addEventListener('change', function () {
// 		if (motifField.value === 'Saisie manuelle') {

// 			submotifContainer.style.display = 'block';
// 		} else {
// 			submotifContainer.style.display = 'none';
// 		}
// 	});
// }

// //select motiveResil
// var resilField = document.getElementById('prospect_lastAssure');
// var subresilContainer = document.getElementById('subResil-container');
// if (resilField !== null) {
// 	resilField.addEventListener('change', function () {
// 		if (resilField.value === 'Oui') {

// 			subresilContainer.style.display = 'block';
// 		} else {
// 			subresilContainer.style.display = 'none';
// 		}
// 	});
// }

// function playNotificationSound() {
// 	const audio = new Audio('/assets/sounds/notification-soundtone360.mp3');
// 	audio.play();
// }

// $.ajax({
// 	url: 'http://localhost:92/api/prospects',
// 	success: function (data) {
// 		// Process prospect data
// 		playNotificationSound();
// 	}
// });

