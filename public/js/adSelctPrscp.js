document.addEventListener("DOMContentLoaded", function () {
	var typeProspectField = document.getElementById("{{ form.typeProspect.vars.id }}");
	var subcategoryContainer = document.getElementById("subcategory-container");
	var subcategoryActivites = document.getElementById("subcategory-activite");

	typeProspectField.addEventListener("change", function () {
		if (typeProspectField.value === '2') {
			subcategoryContainer.style.display = "block";
			subcategoryActivites.style.display = "block";
		} else {
			subcategoryContainer.style.display = "none";
			subcategoryActivites.style.display = "none";
		}
	});
});
// setlc resilt
document.addEventListener("DOMContentLoaded", function () {
	var typeResilField = document.getElementById("{{ form.lastAssure.vars.id }}");
	var subcategoryMotif = document.getElementById("subcategory-motif");

	typeResilField.addEventListener("change", function () {
		if (typeResilField.value === 'Oui') {
			subcategoryMotif.style.display = "block";
		} else {
			subcategoryMotif.style.display = "none";
		}
	});
});