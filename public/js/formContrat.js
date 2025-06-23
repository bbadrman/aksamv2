document.addEventListener('DOMContentLoaded', function () { // TOGGLE BLOCKS
    const toggles = [
        {
            btnId: 'toggle-souscripteur',
            fieldId: 'souscripteur-fields'
        }, {
            btnId: 'toggle-risque',
            fieldId: 'risque-fields'
        }, {
            btnId: 'toggle-payment',
            fieldId: 'payment-fields'
        }, {
            btnId: 'toggle-document',
            fieldId: 'document-fields'
        }
    ];

    toggles.forEach(toggle => {
        const btn = document.getElementById(toggle.btnId);
        const field = document.getElementById(toggle.fieldId);
        if (btn && field) {
            btn.style.cursor = 'pointer';
            btn.addEventListener('click', () => {
                const isHidden = field.style.display === 'none';
                field.style.display = isHidden ? 'flex' : 'none';
                field.style.flexWrap = 'wrap';
            });
        }
    });

    // GESTION DES RÈGLEMENTS
    const reglementSelect = document.querySelector('[name="contrat[payments][NmbrReglement]"]');
    const reglementGroups = document.querySelectorAll('.reglement-group');

    function updateReglementVisibility(count) {
        reglementGroups.forEach(group => {
            const index = parseInt(group.dataset.index);
            group.style.display = index < count ? 'flex' : 'none';
        });
    }

    if (reglementSelect) {
        reglementSelect.addEventListener('change', (e) => {
            const count = parseInt(e.target.value);
            if (!isNaN(count))
                updateReglementVisibility(count);



        });

        const initialCount = parseInt(reglementSelect.value);
        if (!isNaN(initialCount))
            updateReglementVisibility(initialCount);



    }

    // ANTÉCÉDENTS (NmbrAssure)
    const assureSelect = document.querySelector('[name="contrat[NmbrAssure]"]');
    const containerAssure = document.getElementById('antcdAssure-container');

    if (assureSelect && containerAssure) {
        assureSelect.addEventListener('change', (e) => {
            const targetCount = parseInt(e.target.value);
            containerAssure.innerHTML = '';
            const prototype = containerAssure.dataset.prototype;
            containerAssure.dataset.index = 0;

            for (let i = 0; i < targetCount; i++) {
                const newForm = prototype.replace(/__name__/g, containerAssure.dataset.index);
                const div = document.createElement('div');
                div.classList.add('antcdAssure-item');
                div.innerHTML = newForm;
                containerAssure.appendChild(div);
                containerAssure.dataset.index++;
            }
        });
    }

    // GESTION DES CHAMPS DYNAMIQUES
    const typeContratField = document.getElementById("{{ form.typeContrat.vars.id }}");
    const typeConducteurField = document.getElementById("{{ form.typeConducteur.vars.id }}");
    const productField = document.getElementById("{{ form.product.vars.id }}");
    const suspensinField = document.getElementById("{{ form.suspensionPermis.vars.id }}");
    const annulationPermisField = document.getElementById("{{ form.annulationPermis.vars.id }}");
    const faciliteField = document.getElementById("{{ form.facilite.vars.id }}");

    const subcategoryContainer = document.getElementById("subcategory-container");
    const subcategoryActivites = document.getElementById("subcategory-activite");
    const subcategorytypeConducteur = document.getElementById("subcategory-typeConducteur");
    const subcategoryimatriclt = document.getElementById("subcategory-imatriclt");
    const subcategoryForceJuridique = document.getElementById("subcategory-forceJuridique");

    const subcategoryconducteur = document.getElementById("subcategory-conducteur");
    const subcategorytypePermis = document.getElementById("subcategory-typePermis");
    const subcategorydatePermis = document.getElementById("subcategory-datePermis");
    const subcategorydateSuspension = document.getElementById("subcategory-dateSuspension");
    const subcategorymotifSuspension = document.getElementById("subcategory-motifSuspension");
    const subcategorydateAnnulation = document.getElementById("subcategory-dateAnnulation");
    const subcategorymotifAnnulation = document.getElementById("subcategory-motifAnnulation");
    const subcategoryNmbrReglement = document.getElementById("subcategory-NmbrReglement");

    function handleTypeContratChange() {
        const isParticulier = typeContratField.value === 'Particulier';
        subcategoryContainer.style.display = isParticulier ? "none" : "block";
        subcategoryActivites.style.display = isParticulier ? "none" : "block";
        subcategoryForceJuridique.style.display = isParticulier ? "none" : "block";
    }

    function handleTypeConducteurChange() {
        const isMulti = typeConducteurField.value === 'Multiconducteur';
        [subcategoryconducteur, subcategorytypePermis, subcategorydatePermis].forEach(el => {
            el.style.display = isMulti ? "none" : "block";
        });
    }

    function handleProductChange() {
        const hideFields = ['2', '11', '20'].includes(productField.value);
        [
            subcategoryconducteur,
            subcategorytypePermis,
            subcategorydatePermis,
            subcategorytypeConducteur,
            subcategoryimatriclt
        ].forEach(el => {
            el.style.display = hideFields ? "none" : "block";
        });
    }

    function handleSuspentionChange() {
        const isOui = suspensinField.value === '1';
        subcategorydateSuspension.style.display = isOui ? "block" : "none";
        subcategorymotifSuspension.style.display = isOui ? "block" : "none";
    }

    function handleAnulationpermiChange() {
        const isOui = annulationPermisField.value === '1';
        subcategorydateAnnulation.style.display = isOui ? "block" : "none";
        subcategorymotifAnnulation.style.display = isOui ? "block" : "none";
    }

    faciliteField.addEventListener("change", function () { // Adaptez la valeur selon vos options : '1', 'oui', 'true', etc.
        const isOui = this.value === '1';
        subcategoryNmbrReglement.style.display = isOui ? "block" : "none";
    });

    // État initial
    const isOui = faciliteField.value === '1';
    subcategoryNmbrReglement.style.display = isOui ? "block" : "none";


    if (typeContratField)
        typeContratField.addEventListener("change", handleTypeContratChange);



    if (typeConducteurField)
        typeConducteurField.addEventListener("change", handleTypeConducteurChange);



    if (productField)
        productField.addEventListener("change", handleProductChange);



    if (suspensinField)
        suspensinField.addEventListener("change", handleSuspentionChange);



    if (annulationPermisField)
        annulationPermisField.addEventListener("change", handleAnulationpermiChange);



    // Initial trigger
    handleTypeContratChange();
    handleTypeConducteurChange();
    handleProductChange();
    handleSuspentionChange();
    handleAnulationpermiChange();
});
// GESTION DU TYPE DE PAIEMENT
document.addEventListener('DOMContentLoaded', function () { // Pour chaque bloc de paiement
    document.querySelectorAll('[data-type-field]').forEach(typeFieldContainer => {
        const select = typeFieldContainer.querySelector('select');
        if (!select)
            return;



        const parent = typeFieldContainer.closest('.form-group.row');

        // Champs associés à ce bloc
        const fraisField = parent.querySelector('[data-show-when-type="frais"]');
        const cotisationField = parent.querySelector('[data-show-when-type="cotisation"]');
        const contrePartieField = parent.querySelector('[data-show-when-type="contrePartie"]');

        function updateFields() {
            const selected = select.value;

            // Masquer tous les champs
            [fraisField, cotisationField, contrePartieField].forEach(field => {
                if (field)
                    field.classList.add('d-none');



            });

            // Afficher le champ sélectionné
            switch (selected) {
                case 'frais':
                    if (fraisField)
                        fraisField.classList.remove('d-none');



                    break;
                case 'cotisation':
                    if (cotisationField)
                        cotisationField.classList.remove('d-none');



                    break;
                case 'contrePartie':
                    if (contrePartieField)
                        contrePartieField.classList.remove('d-none');



                    break;
            }
        }

        select.addEventListener('change', updateFields);
        updateFields(); // Initialiser au chargement
    });
});


// Force suppression du loader sur cette page
document.addEventListener('DOMContentLoaded', function () {
    console.log('🔧 Page new.html.twig - suppression loader');

    // Multiple tentatives
    function killLoader() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.remove();
            console.log('💀 Loader tué sur page show');
        }

        // CSS killer spécifique
        const style = document.createElement('style');
        style.textContent = '#loader { display: none !important; }';
        document.head.appendChild(style);
    }

    // Exécution immédiate et répétée
    killLoader();
    setTimeout(killLoader, 100);
    setTimeout(killLoader, 500);
    setTimeout(killLoader, 1000);

    // Force via fonction globale si disponible
    if (window.FORCE_DESTROY_LOADER) {
        setTimeout(window.FORCE_DESTROY_LOADER, 200);
    }
});