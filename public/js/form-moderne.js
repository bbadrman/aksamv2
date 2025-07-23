// ===================================================================
// JAVASCRIPT RÉSISTANT AUX CONFLITS CSS
// ===================================================================

document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Initialisation avec résistance aux conflits CSS...');

    // Attendre que tous les CSS soient chargés
    setTimeout(initializeConditionalFieldsRobust, 100);
});

function initializeConditionalFieldsRobust() {
    console.log('🔧 Initialisation robuste des champs conditionnels...');

    // 1. FORCER L'ÉTAT INITIAL DES CHAMPS
    const fieldsToManage = [
        'subcategory-activite',
        'subcategory-container',
        'subcategory-forceJuridique'
    ];

    // Forcer l'état initial
    fieldsToManage.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            forceHideField(field, fieldId);
        }
    });

    // 2. TROUVER ET CONFIGURER LE CHAMP TYPECONTRAT
    const typeContratField = findTypeContratField();
    if (!typeContratField) {
        console.error('❌ Champ typeContrat non trouvé');
        return;
    }

    console.log('✅ Champ typeContrat configuré:', typeContratField.id);

    // 3. CONFIGURER LES ÉVÉNEMENTS
    typeContratField.addEventListener('change', function () {
        handleTypeContratChangeRobust(this, fieldsToManage);
    });

    // 4. INITIALISATION IMMÉDIATE
    handleTypeContratChangeRobust(typeContratField, fieldsToManage);

    // 5. OBSERVER LES CHANGEMENTS DOM (au cas où d'autres scripts modifient les styles)
    observeFieldChanges(fieldsToManage);
}

function findTypeContratField() {
    const selectors = [
        '#typeContrat',
        'select[name*="typeContrat"]',
        '[id*="typeContrat"]',
        'select[id*="type_contrat"]'
    ];

    for (let selector of selectors) {
        const field = document.querySelector(selector);
        if (field) {
            console.log(`✅ TypeContrat trouvé avec: ${selector}`);
            return field;
        }
    }

    return null;
}

function handleTypeContratChangeRobust(field, fieldsToManage) {
    const value = field.value || '';
    console.log('🔄 Changement robuste:', value);

    // Afficher les options disponibles
    if (field.options) {
        console.log('📋 Options disponibles:');
        Array.from(field.options).forEach((option, index) => {
            console.log(`  ${index}: "${option.value}" - "${option.text}"`);
        });
    }

    // Déterminer si c'est professionnel
    const isProfessionnel = isValueProfessionnel(value);
    console.log('🏢 Est professionnel?', isProfessionnel);

    // Appliquer les changements avec force
    fieldsToManage.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            if (isProfessionnel) {
                forceShowField(field, fieldId);
            } else {
                forceHideField(field, fieldId);
            }
        }
    });
}

function isValueProfessionnel(value) {
    if (!value) return false;

    const professionnelValues = [
        'professionnel', 'Professionnel', 'PROFESSIONNEL',
        'pro', 'Pro', 'PRO',
        'entreprise', 'Entreprise', 'ENTREPRISE',
        'société', 'Société', 'SOCIETE',
        'business', 'Business', 'BUSINESS',
        'company', 'Company', 'COMPANY',
        '2', 'prof', 'ent', 'soc'
    ];

    const normalizedValue = value.toString().trim().toLowerCase();
    return professionnelValues.some(prof =>
        normalizedValue.includes(prof.toLowerCase()) ||
        normalizedValue === prof.toLowerCase()
    );
}

function forceShowField(field, fieldId) {
    console.log(`✅ FORCER AFFICHAGE: ${fieldId}`);

    // Méthode 1: Styles inline avec !important via JavaScript
    field.style.setProperty('display', 'block', 'important');
    field.style.setProperty('opacity', '1', 'important');
    field.style.setProperty('visibility', 'visible', 'important');
    field.style.setProperty('height', 'auto', 'important');
    field.style.setProperty('overflow', 'visible', 'important');
    field.style.setProperty('position', 'relative', 'important');
    field.style.setProperty('z-index', '10', 'important');

    // Méthode 2: Classes CSS
    field.classList.remove('conditional-hidden', 'debug-hidden');
    field.classList.add('conditional-visible', 'debug-visible');

    // Méthode 3: Attributs data
    field.setAttribute('data-state', 'visible');
    field.setAttribute('data-forced', 'true');

    // Méthode 4: Forcer sur tous les enfants
    const children = field.querySelectorAll('*');
    children.forEach(child => {
        if (child.style.display === 'none') {
            child.style.setProperty('display', 'block', 'important');
        }
    });

    // Vérification
    setTimeout(() => {
        const isVisible = field.offsetHeight > 0 && field.offsetWidth > 0;
        console.log(`📊 Vérification ${fieldId}:`, {
            offsetHeight: field.offsetHeight,
            offsetWidth: field.offsetWidth,
            display: getComputedStyle(field).display,
            visibility: getComputedStyle(field).visibility,
            opacity: getComputedStyle(field).opacity,
            isVisible: isVisible
        });

        if (!isVisible) {
            console.warn(`⚠️ ${fieldId} n'est toujours pas visible, tentative de correction...`);
            emergencyShowField(field, fieldId);
        }
    }, 100);
}

function forceHideField(field, fieldId) {
    console.log(`❌ FORCER MASQUAGE: ${fieldId}`);

    // Méthode 1: Styles inline
    field.style.setProperty('display', 'none', 'important');
    field.style.setProperty('opacity', '0', 'important');
    field.style.setProperty('visibility', 'hidden', 'important');
    field.style.setProperty('height', '0', 'important');
    field.style.setProperty('overflow', 'hidden', 'important');

    // Méthode 2: Classes CSS
    field.classList.remove('conditional-visible', 'debug-visible');
    field.classList.add('conditional-hidden', 'debug-hidden');

    // Méthode 3: Attributs data
    field.setAttribute('data-state', 'hidden');
    field.setAttribute('data-forced', 'true');
}

function emergencyShowField(field, fieldId) {
    console.log(`🚨 PROCÉDURE D'URGENCE pour ${fieldId}`);

    // Supprimer tous les styles qui pourraient masquer le champ
    field.style.cssText = '';

    // Appliquer les styles de force maximale
    field.style.cssText = `
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        height: auto !important;
        overflow: visible !important;
        position: relative !important;
        z-index: 9999 !important;
        background: #fff3cd !important;
        border: 3px solid #ff6b6b !important;
        padding: 10px !important;
        margin: 5px !important;
        min-height: 50px !important;
        width: auto !important;
        max-width: none !important;
        min-width: 200px !important;
        flex: 1 !important;
    `;

    // Marquer comme urgence
    field.setAttribute('data-emergency', 'true');
    field.classList.add('emergency-visible');
}

function observeFieldChanges(fieldsToManage) {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                const field = mutation.target;
                const fieldId = field.id;

                if (fieldsToManage.includes(fieldId)) {
                    const expectedState = field.getAttribute('data-state');
                    const computedStyle = getComputedStyle(field);

                    // Vérifier si un autre script a modifié les styles
                    if (expectedState === 'visible' && computedStyle.display === 'none') {
                        console.warn(`⚠️ ${fieldId} a été masqué par un autre script, correction...`);
                        emergencyShowField(field, fieldId);
                    }
                }
            }
        });
    });

    fieldsToManage.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            observer.observe(field, {
                attributes: true,
                attributeFilter: ['style', 'class']
            });
        }
    });
}

// ===== FONCTIONS UTILITAIRES POUR LA CONSOLE =====
window.testConditionalFieldsRobust = function () {
    console.log('🧪 Test robuste des champs conditionnels...');

    const typeContratField = findTypeContratField();
    if (!typeContratField) {
        console.error('❌ Champ typeContrat non trouvé');
        return;
    }

    const fieldsToManage = [
        'subcategory-activite',
        'subcategory-container',
        'subcategory-forceJuridique'
    ];

    console.log('🧪 Test 1: Forcer professionnel...');
    typeContratField.value = 'Professionnel';
    handleTypeContratChangeRobust(typeContratField, fieldsToManage);

    setTimeout(() => {
        console.log('🧪 Test 2: Forcer particulier...');
        typeContratField.value = 'Particulier';
        handleTypeContratChangeRobust(typeContratField, fieldsToManage);
    }, 3000);
};

window.forceShowAllFields = function () {
    console.log('🔧 Forçage manuel de tous les champs...');

    const fieldsToManage = [
        'subcategory-activite',
        'subcategory-container',
        'subcategory-forceJuridique'
    ];

    fieldsToManage.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            emergencyShowField(field, fieldId);
        }
    });
};

window.debugCSSConflicts = function () {
    console.log('🔍 Diagnostic des conflits CSS...');

    const fieldsToManage = [
        'subcategory-activite',
        'subcategory-container',
        'subcategory-forceJuridique'
    ];

    fieldsToManage.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            const computedStyle = getComputedStyle(field);
            console.log(`📊 ${fieldId}:`, {
                display: computedStyle.display,
                opacity: computedStyle.opacity,
                visibility: computedStyle.visibility,
                height: computedStyle.height,
                overflow: computedStyle.overflow,
                position: computedStyle.position,
                zIndex: computedStyle.zIndex,
                offsetHeight: field.offsetHeight,
                offsetWidth: field.offsetWidth,
                classes: field.className,
                inlineStyles: field.style.cssText
            });
        }
    });
};

// Initialisation immédiate des fonctions de debug
console.log('🎯 Fonctions de debug disponibles:');
console.log('- testConditionalFieldsRobust(): Test automatique robuste');
console.log('- forceShowAllFields(): Forcer l\'affichage de tous les champs');
console.log('- debugCSSConflicts(): Diagnostiquer les conflits CSS');