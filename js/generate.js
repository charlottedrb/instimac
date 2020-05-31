// ------------- formulaires ------------------
function generate_formulaire_ajout_publication() {

    var modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'add_publication';

    var form = document.createElement('form');
    form.className = 'actions-form modal-content';
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var idGroupe = document.getElementById('current_group').getAttribute('data-id');

        var input = document.createElement('input');
        setAttributes(input, {'type': 'hidden', 'name': 'groupe', 'value': idGroupe});
        this.append(input);

        var body = new FormData(this);
        postRequest('./api/publication/ajouter.php', body, pushToPublications, displayError);
        document.getElementById('closeModal-addpublication').click();
    });

    //FERMETURE DE LA MODAL
    var closeModal = document.createElement('span');
    closeModal.className = "closeModal";
    closeModal.id = 'closeModal-addpublication';

    var legend = document.createTextNode('Fermer');
    closeModal.appendChild(legend);
    closeModal.onclick = function () {
        var modal = document.getElementById('add_publication');
        modal.style.display = "none";
    };

    var formTitle = document.createElement('h3');
    var formTitle_content = document.createTextNode('Ajouter une publication');
    formTitle.appendChild(formTitle_content);

    //INPUT DESCRIPTION
    var description = document.createElement('input');
    setAttributes(description, {'type': 'text', 'name': 'description', 'id': 'description'});
    //label
    var description_label = document.createElement('LABEL');
    var description_label_content = document.createTextNode("Description");
    description_label.setAttribute('for', 'description');
    description_label.appendChild(description_label_content);

    //INPUT IMAGE
    var url_img = document.createElement('input');
    setAttributes(url_img, {'type': 'file', 'name': 'photo', 'id': 'url_img'});
    //label
    var url_img_label = document.createElement('LABEL');
    var url_img_label_content = document.createTextNode("Votre image");
    url_img_label.setAttribute('for', 'url_img');
    url_img_label.appendChild(url_img_label_content);

    //BUTTON SUBMIT
    var button = document.createElement('button');
    button.setAttribute('type', 'submit');
    button.innerHTML = "Envoyer";

    //APPENDS 
    modal.appendChild(closeModal);
    modal.appendChild(form);
    form.appendChild(formTitle);
    form.appendChild(description);
    form.appendChild(url_img);
    form.appendChild(button);
    form.insertBefore(description_label, description);
    form.insertBefore(url_img_label, url_img);

    //AFFICHAGE
    document.getElementById('affichage').appendChild(modal);
}

function generate_formulaire_ajout_groupe() {
    var modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'add_groupe';

    var form = document.createElement('form');
    form.className = 'actions-form modal-content';

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var body = new FormData(this);
        postRequest('./api/groupe/ajouter.php', body, displayError, displayError);
        document.getElementById('closeModal-publication').click();
        loadGroupes();
    });

    //FERMETURE DE LA MODAL
    var closeModal = document.createElement('span');
    closeModal.className = "closeModal";
    closeModal.id = "closeModal-publication";
    var legend = document.createTextNode('Fermer');
    closeModal.appendChild(legend);
    closeModal.onclick = function () {
        var modal = document.getElementById('add_groupe');
        modal.style.display = "none";
    };

    var formTitle = document.createElement('h3');
    var formTitle_content = document.createTextNode('Ajouter un évènement');
    formTitle.appendChild(formTitle_content);

    //INPUT TITRE
    var titre = document.createElement('input');
    setAttributes(titre, {'type': 'text', 'name': 'titre', 'id': 'titre'});
    //label
    var titre_label = document.createElement('LABEL');
    var titre_label_content = document.createTextNode("Titre");
    titre_label.setAttribute('for', 'titre');
    titre_label.appendChild(titre_label_content);

    //INPUT IMAGE
    var lieu = document.createElement('input');
    setAttributes(lieu, {'type': 'text', 'name': 'lieu', 'id': 'lieu'});
    //label
    var lieu_label = document.createElement('LABEL');
    var lieu_label_content = document.createTextNode("Lieu");
    lieu_label.setAttribute('for', 'lieu');
    lieu_label.appendChild(lieu_label_content);

    //INPUT DATE
    var date = document.createElement('input');
    setAttributes(date, {'type': 'date', 'name': 'date', 'id': 'date'});
    //label
    var date_label = document.createElement('LABEL');
    var date_label_content = document.createTextNode("Date");
    date_label.setAttribute('for', 'date');
    date_label.appendChild(date_label_content);

    //BUTTON SUBMIT
    var button = document.createElement('button');
    button.setAttribute('type', 'submit');
    button.innerHTML = "Envoyer";

    //APPENDS 
    modal.appendChild(closeModal);
    modal.appendChild(form);
    form.appendChild(formTitle);
    form.appendChild(titre);
    form.appendChild(lieu);
    form.appendChild(date);
    form.appendChild(button);
    form.insertBefore(titre_label, titre);
    form.insertBefore(lieu_label, lieu);
    form.insertBefore(date_label, date);

    //AFFICHAGE
    document.getElementById('affichage').appendChild(modal);
}

function generate_formulaire_modif_groupe() {

    var modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'modif_groupe';

    var form = document.createElement('form');
    form.className = 'actions-form modal-content';
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var parent = this.parentNode;
        var idGroupe = parent.getAttribute('data-id');

        var input = document.createElement('input');
        setAttributes(input, {'type': 'hidden', 'name': 'id', 'value': idGroupe});
        this.append(input);

        var body = new FormData(this);
        postRequest('./api/groupe/modifier.php', body, displayError, displayError);
        document.getElementById('closeModal-publication-maj').click();
        loadGroupes();
    });

    //FERMETURE DE LA MODAL
    var closeModal = document.createElement('span');
    closeModal.className = "closeModal";
    closeModal.id = 'closeModal-publication-maj';

    var legend = document.createTextNode('Fermer');
    closeModal.appendChild(legend);
    closeModal.onclick = function () {
        var modal = document.getElementById('modif_groupe');
        modal.style.display = "none";
    };

    var formTitle = document.createElement('h3');
    var formTitle_content = document.createTextNode('Modifier un événement');
    formTitle.appendChild(formTitle_content);

    //INPUT TITRE
    var titre = document.createElement('input');
    setAttributes(titre, {'type': 'text', 'name': 'titre', 'id': 'titre'});
    //label
    var titre_label = document.createElement('LABEL');
    var titre_label_content = document.createTextNode("Titre");
    titre_label.setAttribute('for', 'titre');
    titre_label.appendChild(titre_label_content);

    //INPUT IMAGE
    var lieu = document.createElement('input');
    setAttributes(lieu, {'type': 'text', 'name': 'lieu', 'id': 'lieu'});
    //label
    var lieu_label = document.createElement('LABEL');
    var lieu_label_content = document.createTextNode("Lieu");
    lieu_label.setAttribute('for', 'lieu');
    lieu_label.appendChild(lieu_label_content);

    //INPUT DATE
    var date = document.createElement('input');
    setAttributes(date, {'type': 'date', 'name': 'date', 'id': 'date'});
    //label
    var date_label = document.createElement('LABEL');
    var date_label_content = document.createTextNode("Date");
    date_label.setAttribute('for', 'date');
    date_label.appendChild(date_label_content);

    //BUTTON SUBMIT
    var button = document.createElement('button');
    button.setAttribute('type', 'submit');
    button.innerHTML = "Envoyer";

    //APPENDS 
    modal.appendChild(closeModal);
    modal.appendChild(form);
    form.appendChild(formTitle);
    form.appendChild(titre);
    form.appendChild(lieu);
    form.appendChild(date);
    form.appendChild(button);
    form.insertBefore(titre_label, titre);
    form.insertBefore(lieu_label, lieu);
    form.insertBefore(date_label, date);

    //AFFICHAGE
    document.getElementById('affichage').appendChild(modal);
}

function generate_formulaire_delete_groupe() {

    var modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'delete_groupe';

    var form = document.createElement('form');
    form.className = 'actions-form modal-content';

    form.addEventListener('submit', function (e) {

        e.preventDefault();

        var parent = this.parentNode;
        var idGroupe = parent.getAttribute('data-id');
        var body = {id: idGroupe};

        getRequest('./api/groupe/supprimer.php', body, displayError, displayError);
        document.getElementById('closeModal-groupe').click();
        loadGroupes();
    });

    //FERMETURE DE LA MODAL
    var legend = document.createTextNode('Fermer');

    var closeModal = document.createElement('span');
    closeModal.className = "closeModal";
    closeModal.id = 'closeModal-groupe';
    closeModal.appendChild(legend);
    closeModal.onclick = function () {
        var modal = document.getElementById('delete_groupe');
        modal.style.display = "none";
    };

    var formTitle = document.createElement('h3');
    var formTitle_content = document.createTextNode('Supprimer l\'événement');
    formTitle.appendChild(formTitle_content);

    //BUTTON SUBMIT
    var button = document.createElement('button');
    button.setAttribute('type', 'submit');
    button.innerHTML = "Supprimer";

    //APPENDS 
    modal.appendChild(closeModal);
    modal.appendChild(form);
    form.appendChild(formTitle);
    form.appendChild(button);

    //AFFICHAGE
    document.getElementById('affichage').appendChild(modal);
}

console.log("Création des formulaires");
generate_formulaire_ajout_publication();
generate_formulaire_ajout_groupe();
generate_formulaire_modif_groupe();
generate_formulaire_delete_groupe();

//AFFICHAGE INDIVIDUEL////////////////////////////////////////////////////////////////////
function afficher_groupe(groupe_json) { //affiche les groupes sur le fil d'actualité

    var groupe_container = document.createElement('div');
    groupe_container.className = 'container-event';

    var groupe = document.createElement('div');//crée l'element html
    groupe.className = 'event'; //lui donne un attribut class contenant "event

    var groupe_name = document.createElement('h2');
    groupe_name.className = 'event-name';
    var texte_groupe_name = document.createTextNode(groupe_json.titre);
    groupe_name.appendChild(texte_groupe_name);
    groupe_name.onclick = function () {
        clearFilActu();
        clearSidebar();
        groupeSidebar();
        afficher_page_groupe();

        console.log(groupe_json.id);

        loadGroupeInfo(groupe_json.id);
        loadPublications(groupe_json.id);
    };

    var groupe_date = document.createElement('div');
    groupe_date.className = 'event-date';
    var texte_groupe_date = document.createTextNode(groupe_json.date);
    //Permet de garder seulement le nombre du jour
    var day = texte_groupe_date.textContent.substring(8, 10);
    groupe_date.innerHTML = day;

    var groupe_actions = document.createElement('div');
    groupe_actions.className = 'actions';

    //ICONE DE MODIF
    var action_modif = document.createElement('div');
    action_modif.className = 'modif icon';
    action_modif.setAttribute('data-id', groupe_json.id);

    var modif_icon = document.createElement('img');
    modif_icon.setAttribute('src', '../img/modif.png');
    modif_icon.className = 'icon-img';
    action_modif.onclick = function () {
        var modal = document.getElementById('modif_groupe');
        modal.setAttribute('data-id', this.getAttribute('data-id'));
        modal.style.display = "block";
    };

    //ICONE DE SUPPRESSION
    var action_delete = document.createElement('div');
    action_delete.className = 'delete icon';
    action_delete.setAttribute('data-id', groupe_json.id);

    var delete_icon = document.createElement('img');
    delete_icon.setAttribute('src', '../img/delete.png');
    delete_icon.className = 'icon-img';


    action_delete.onclick = function () {
        var modal = document.getElementById('delete_groupe');
        modal.setAttribute('data-id', this.getAttribute('data-id'));
        modal.style.display = "block";
    };

    //Ajout des icones à la div action 
    groupe_actions.appendChild(action_modif);
    groupe_actions.appendChild(action_delete);

    //Ajout des images aux icones
    action_modif.appendChild(modif_icon);
    action_delete.appendChild(delete_icon);

    groupe_container.appendChild(groupe_name); //ajoute un enfant groupe_name à groupe_container
    groupe_container.appendChild(groupe_actions);
    groupe.appendChild(groupe_container);
    groupe.appendChild(groupe_date);
    document.querySelector('.fil').appendChild(groupe); //ajoute au fil d'actualité
}

function afficher_publication_in_publicationList(publication_json) { //affiche les pré_vues des publication sur la page du groupe

    //modal_detail_publication(publication_json);

    var publication = document.createElement('div');
    publication.className = 'publication';
    publication.setAttribute('data-id', publication_json.id);
    publication.addEventListener("click", function () {
        loadPublication(this.getAttribute('data-id'));
    });

    //IMAGE
    var publication_image = document.createElement('div');
    publication_image.className = 'publication-image';
    var publication_image_url = document.createElement('img');
    publication_image_url.src = publication_json.photoURL;
    publication_image_url.alt = "photo";
    publication_image.appendChild(publication_image_url);
    publication.appendChild(publication_image);

    //USER
    var user = document.createElement('div');
    user.className = 'user';
    var user_name = document.createElement('div');
    user_name.className = 'user-name';
    var texte_user_name = document.createTextNode(publication_json.utilisateur.nom);
    user_name.appendChild(texte_user_name);
    user.appendChild(user_name);
    publication.appendChild(user);

    document.querySelector('.publications').appendChild(publication);
}

function modal_detail_publication(publication_json) {
    //MODAL
    var modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'publication-' + publication_json.id;
    console.log(modal.id);

    //FERMETURE DE LA MODAL
    var closeModal = document.createElement('span');
    closeModal.className = "closeModal";
    var legend = document.createTextNode('Fermer');
    closeModal.appendChild(legend);
    closeModal.onclick = function () {
        var modal = document.getElementById('publication-' + publication_json.id);
        modal.style.display = "none";
    };
    modal.appendChild(closeModal);
}

function afficher_commentaire(commentaire_json) { //affiche un commentaire

    var commentaires = document.getElementById('commentaires');

    var commentaire = document.createElement('div');
    commentaire.className = 'commentaire';

    //USER
    var user = document.createElement('div');
    user.className = 'user';
    var user_name = document.createElement('div');
    user_name.className = 'user-name';
    var paragraphe_user_name = document.createElement('p');
    var texte_user_name = document.createTextNode(commentaire_json.utilisateur.nom);
    paragraphe_user_name.appendChild(texte_user_name);
    user_name.appendChild(paragraphe_user_name);
    user.appendChild(user_name);
    commentaire.appendChild(user);

    //CONTENU
    var commentaire_content = document.createElement('div');
    commentaire_content.className = 'commentaire-content';
    var paragraphe_commentaire = document.createElement('p');
    var texte_commentaire = document.createTextNode(commentaire_json.contenu);
    paragraphe_commentaire.appendChild(texte_commentaire);
    commentaire_content.appendChild(paragraphe_commentaire);

    //DATE
    var commentaire_date = document.createElement('div');
    commentaire_date.className = 'commentaire-date';
    var text_commentaire_date = document.createTextNode(commentaire_json.date);
    commentaire_date.appendChild(text_commentaire_date);
    commentaire_content.appendChild(commentaire_date);

    commentaire.appendChild(commentaire_content);
    commentaires.appendChild(commentaire);
}

//AFFICHAGES MULTIPLES////////////////////////////////////////////////////////////////////////////
function afficher_toutes_les_publications(publications) {
    for (let i = 0; i < publications.length; i++) {
        afficher_publication_in_publicationList(publications[i]);
    }
}

function afficher_tous_les_commentaires(commentaires) {

    for (let i = 0; i < commentaires.length; i++) {
        afficher_commentaire(commentaires[i]);
    }

    //affichage du formulaire d'ajout d'un commentaire par l'utilisateur
    var input = document.createElement('input');
    setAttributes(input, {'type': 'text', 'name': 'contenu', 'placeholder': 'blableblo'});

    var button = document.createElement('button');
    button.setAttribute('class', 'btn');
    button.innerHTML = '+';

    var form = document.createElement('form');
    form.className = 'form-commentaire';
    form.id = 'form-commentaire';
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var publiId = document.getElementById('current-publication-modale').getAttribute('data-id');
        var input = document.createElement('input');
        setAttributes(input, {'type': 'hidden', 'name': 'publication-id', 'value': publiId});
        this.append(input);
        var body = new FormData(this);
        postRequest('./api/commentaire/ajouter.php', body, pushToCommentaires, displayError);
    });

    form.appendChild(input);
    form.appendChild(button);

    document.getElementById('commentaires').appendChild(form);
}

function afficher_tous_les_groupes(groupes) {

    document.querySelector('.fil').innerHTML = '';

    for (let i = 0; i < groupes.length; i++) {
        afficher_groupe(groupes[i]);
    }
}

//AFFICHAGE DE PAGE////////////////////////////////////////////////////////////////////
//genere les divs pour injecter les infos des requettes

function afficher_fil_actualite() {

    var affichage = document.getElementById('affichage');

    //SIDEBAR
    var sidebar = document.createElement('div');
    sidebar.className = "sidebar";
    affichage.appendChild(sidebar);

    //LOGO
    var logo = document.createElement('div');
    logo.className = "logo";
    var logo_content = document.createElement('img');
    setAttributes(logo_content, {'src': '../img/logoIMAC.png', 'alt': 'logo-imac'});
    logo.appendChild(logo_content);

    //AJOUT NOUVEL EVENT
    var groupe_actions = document.createElement('div');
    groupe_actions.className = 'actions';

    //DESCRIPTION ACTION
    var action_description = document.createElement('div');
    action_description.className = 'sidebar-description';
    var action_description_content = document.createTextNode('Nouvel événement');
    action_description.appendChild(action_description_content);

    //ICONE D'AJOUT
    var action_add = document.createElement('div');
    action_add.className = 'add icon';
    action_add.onclick = function () {
        var modal = document.getElementById('add_groupe');
        modal.style.display = "block";
    };
    var add_icon = document.createElement('img');
    add_icon.setAttribute('src', '../img/plus.png');
    add_icon.className = 'icon-img';

    groupe_actions.appendChild(action_add);
    action_add.appendChild(add_icon);
    sidebar.appendChild(logo);
    sidebar.appendChild(groupe_actions);
    sidebar.appendChild(action_description);

    //FIL D'ACTU
    var fil = document.createElement('div');
    fil.className = "fil";
    affichage.appendChild(fil);
}

function afficher_page_groupe() {

    var header = document.createElement('header');
    header.className = 'header';
    var publications = document.createElement('div');
    publications.className = 'publications';
    var button = document.createElement('button');
    button.className = 'return-button';
    button.innerHTML = "Retour";
    button.onclick = function () {
        clearAll();
        generate_formulaire_ajout_groupe();
        generate_formulaire_modif_groupe();
        generate_formulaire_delete_groupe();
        afficher_fil_actualite();
        loadGroupes();
    };

    document.querySelector('.fil').appendChild(header);
    document.querySelector('.fil').appendChild(publications);
    document.querySelector('.fil').appendChild(button);

}

function afficher_titre_page_groupe(groupe_json) {

    var groupe_name = document.createElement('h1');
    groupe_name.className = 'title';
    var texte_groupe_name = document.createTextNode(groupe_json.titre);
    groupe_name.appendChild(texte_groupe_name);

    var groupe_date = document.createElement('div');
    groupe_date.className = 'event_date';
    var texte_groupe_date = document.createTextNode(groupe_json.date);
    groupe_date.appendChild(texte_groupe_date);

    var groupe_lieu = document.createElement('div');
    groupe_lieu.className = 'event_date';
    var texte_groupe_lieu = document.createTextNode(groupe_json.lieu);
    groupe_lieu.appendChild(texte_groupe_lieu);

    document.querySelector('.header').setAttribute('id', "current_group");
    document.querySelector('.header').setAttribute('data-id', groupe_json.id);

    document.querySelector('.header').appendChild(groupe_name);
    document.querySelector('.header').appendChild(groupe_date);
    document.querySelector('.header').appendChild(groupe_lieu);

}


//EVENEMENT////////////////////////////////////////////////////////////////////////////

//COMMANDES POUR AFFICHER TOUT LE FIL D'ACTULITÉ
afficher_fil_actualite();
console.log('cc');
//afficher_tous_les_groupes(groupes);
loadGroupes();

//COMMANDE POUR AFFICHER UN GROUPE EN ENTIER
//afficher_page_groupe();
//afficher_titre_page_groupe(groupe);
//afficher_toutes_les_publications(publications);

//COMMANDES POUR AFFICHER UNE PUBLICATION
// afficher_page_publication();
// afficher_publication_detail(publication);
// afficher_tous_les_commentaires(commentaires);
//manque afficher réactions

//Permet de paramétrer plusieurs attributs en même temps 
function setAttributes(el, attrs) {
    for (var key in attrs) {
        el.setAttribute(key, attrs[key]);
    }
}

function clearFilActu() {
    document.querySelector('.fil').innerHTML = "";
}

function clearSidebar() {
    document.querySelector('.sidebar').innerHTML = "";
}

function clearAll() {
    document.getElementById('affichage').innerHTML = "";
}

function groupeSidebar() {
    generate_formulaire_ajout_publication();
    var sidebar = document.querySelector('.sidebar');

    //LOGO
    var logo = document.createElement('div');
    logo.className = "logo";
    var logo_content = document.createElement('img');
    setAttributes(logo_content, {'src': '../img/logoIMAC.png', 'alt': 'logo-imac'});
    logo.appendChild(logo_content);

    //AJOUT NOUVELLE PUBLICATION
    var groupe_actions = document.createElement('div');
    groupe_actions.className = 'actions';

    //DESCRIPTION ACTION
    var action_description = document.createElement('div');
    action_description.className = 'sidebar-description';
    var action_description_content = document.createTextNode('Ajouter une publication');
    action_description.appendChild(action_description_content);

    //ICONE D'AJOUT
    var action_add = document.createElement('div');
    action_add.className = 'add icon';


    action_add.onclick = function () {
        var modal = document.getElementById('add_publication');
        modal.style.display = "block";
    };
    var add_icon = document.createElement('img');
    add_icon.setAttribute('src', '../img/plus.png');
    add_icon.className = 'icon-img';

    groupe_actions.appendChild(action_add);
    action_add.appendChild(add_icon);
    sidebar.appendChild(logo);
    sidebar.appendChild(groupe_actions);
    sidebar.appendChild(action_description);
}

////////////// LOADING CONTENTS FUNCTIONS ////////////////

function loadGroupes() {
    getRequest('./api/groupe/get-all.php', {}, afficher_tous_les_groupes, displayError);
}

function loadGroupeInfo(id) {
    getRequest('./api/groupe/get.php', {id: id}, afficher_titre_page_groupe, displayError);
}

function loadPublications(id) {
    getRequest('./api/publication/get-by-groupe.php', {groupe: id}, afficher_toutes_les_publications, displayError);
}

function loadCommentaires(id) {
    getRequest('./api/commentaire/get-by-publication.php', {"publication-id": id}, afficher_tous_les_commentaires, displayError);
}

function displayError(value) {
    console.log(value);
}


// function afficher_publication_detail(publication_json) {//affiche le detail d'une publication (sans les commentaires)

//     //PUBLICATION
//     var publication = document.createElement('div');
//     publication.className = 'publication modal-content';

//     //IMAGE
//     var publication_image = document.createElement('div');
//     publication_image.className = 'publication-image';
//     var publication_image_url = document.createElement('img');
//     publication_image_url.setAttribute('src', publication_json.photoURL);
//     publication_image_url.alt = "photo";
//     publication_image.appendChild(publication_image_url);

//     //INFOS
//     var publication_infos = document.createElement('div');
//     publication_infos.className = 'publication-infos';

//     //DESCRIPTION
//     var publication_description = document.createElement('div');
//     publication_description.className = 'publication-description';
//     var description = document.createElement('p');
//     var texte_description = document.createTextNode(publication_json.description);
//     description.appendChild(texte_description);

//     //DATE
//     var publication_date = document.createElement('div');
//     publication_date.className = 'publication-date';
//     var texte_publication_date = document.createTextNode(publication_json.date);
//     publication_date.appendChild(texte_publication_date);

//     //USER
//     var user = document.createElement('user');
//     user.className = 'user';

//     var user_name = document.createElement('div');
//     user_name.className = 'user-name';
//     var texte_user_name = document.createTextNode(publication_json.utilisateur.nom);

//     var user_image = document.createElement('div');
//     user_image.className = 'user-image roundImg';
//     var user_image_url = document.createElement('img');
//     user_image_url.src = publication_json.utilisateur.photoURL;
//     user_image_url.alt = 'photo utilisateur';
//     user_image.appendChild(user_image_url);
//     user_name.appendChild(texte_user_name);
//     user.appendChild(user_name);
//     user.appendChild(user_image);

//     publication_description.appendChild(description);
//     publication_description.appendChild(publication_date);
//     publication_description.appendChild(user);

//     publication-publication_infos.appendChild(publication_description);


//     publication.appendChild(publication_image);
//     publication.appendChild(publication_infos);

//     //AUTRES APPEND
//     modal.appendChild(publication);
// }
