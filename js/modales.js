function modale(content) {

    //Container global
    var modale = document.createElement('div');
    modale.className = 'modal';
    modale.setAttribute('style', 'display: block;');

    //bouton fermeture modale
    var closeModal = document.createElement('span');
    closeModal.className = "closeModal";
    closeModal.innerText = "fermer";
    closeModal.addEventListener('click', function () {
        var modale = this.parentNode;
        modale.remove();
    });

    modale.append(closeModal);
    modale.append(content);

    document.getElementById('affichage').append(modale);
}

function showPublication(publication_json) {

    //PUBLICATION
    var publication = document.createElement('div');
    publication.className = 'publication modal-content';
    publication.id = 'current-publication-modale';
    publication.setAttribute('data-id', publication_json.id);

    //IMAGE
    var publication_image = document.createElement('div');
    publication_image.className = 'publication-image';
    var publication_image_url = document.createElement('img');
    publication_image_url.setAttribute('src', publication_json.photoURL);
    publication_image_url.alt = "photo";
    publication_image.appendChild(publication_image_url);

    //INFOS
    var publication_infos = document.createElement('div');
    publication_infos.className = 'publication-infos';

    //DESCRIPTION
    var publication_description = document.createElement('div');
    publication_description.className = 'publication-description';
    var description = document.createElement('p');
    description.innerHTML = publication_json.description;

    //DATE
    var publication_date = document.createElement('div');
    publication_date.className = 'publication-date';
    var texte_publication_date = document.createTextNode(publication_json.date);
    publication_date.appendChild(texte_publication_date);

    //USER
    var user = document.createElement('user');
    user.className = 'user';

    var user_name = document.createElement('div');
    user_name.className = 'user-name';
    user_name.innerHTML = publication_json.utilisateur.nom;

    var user_image = document.createElement('div');
    user_image.className = 'user-image roundImg';
    var user_image_url = document.createElement('img');
    user_image_url.src = publication_json.utilisateur.photoURL;
    user_image_url.alt = 'photo utilisateur';
    user_image.appendChild(user_image_url);
    user.appendChild(user_name);
    user.appendChild(user_image);

    publication_description.appendChild(user);
    publication_description.appendChild(description);
    publication_description.appendChild(publication_date);
    publication_infos.appendChild(publication_description);

    var zone_commentaires = document.createElement('div');
    zone_commentaires.setAttribute('id', 'commentaires');
    publication_infos.appendChild(zone_commentaires);

    loadCommentaires(publication_json.id);

    publication.appendChild(publication_image);
    publication.appendChild(publication_infos);

    modale(publication);
}

function loadPublication(id) {
    getRequest('./api/publication/get.php', {id: id}, showPublication, displayError);
}

function generateCommentaire(commentaire_json) {

    var commentaire = document.createElement('div');
    commentaire.className = 'commentaire';

    //USER
    var user = document.createElement('div');
    user.className = 'user';
    var user_name = document.createElement('div');
    user_name.className = 'user-name';
    var paragraphe_user_name = document.createElement('p');
    paragraphe_user_name.innerHTML = commentaire_json.utilisateur.nom;
    user_name.appendChild(paragraphe_user_name);
    user.appendChild(user_name);
    commentaire.appendChild(user);

    //CONTENU
    var commentaire_content = document.createElement('div');
    commentaire_content.className = 'commentaire-content';
    var paragraphe_commentaire = document.createElement('p');
    paragraphe_commentaire.innerHTML = commentaire_json.contenu;
    commentaire_content.appendChild(paragraphe_commentaire);

    //DATE
    var commentaire_date = document.createElement('div');
    commentaire_date.className = 'commentaire-date';
    var text_commentaire_date = document.createTextNode(commentaire_json.date);
    commentaire_date.appendChild(text_commentaire_date);
    commentaire_content.appendChild(commentaire_date);

    commentaire.appendChild(commentaire_content);
    return commentaire;
}


function generatePublication(publication_json) {

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

    return publication;
}

function pushToCommentaires(commentToAddObject) {
    var form = document.getElementById('form-commentaire');
    var parent = form.parentNode;
    var commentaire = generateCommentaire(commentToAddObject);
    parent.insertBefore(commentaire, form);
}

function pushToPublications(publicationToPush) {
    var origine = document.querySelector('.publications');
    var publication = generatePublication(publicationToPush);
    origine.append(publication);
}