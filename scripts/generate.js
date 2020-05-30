//TABLEAUX POUR LES TESTS

var groupe = {
    id: 21,
    titre: "Jeudimac",
    lieu: "Copernic",
    date: "2020-02-12 12:12:20",
};

var groupes = [
    {
        id: 21,
        titre: "WEI",
        lieu: "Pommé",
        date: "2020-02-12 12:12:20",
    },
    {
        id: 22,
        titre: "Gala",
        lieu: "Confiné",
        date: "2020-02-12 12:12:20",
    },
];

var publication = {
    id: 23,
    description: "ceci est une publication",
    photoURL: "img/taiga.jpg",
    date: "2020-02-12 12:12:20",
    utilisateur: {
        photoURL: "img/taiga.jpg",
        nom: "ceci est un utilisateur",
    }
};

var publications = [
    {
        id: 23,
        description: "waaaaaaa trop bien",
        photoURL: "img/taiga.jpg",
        utilisateur: {
            photoURL: "img/taiga.jpg",
            nom: "edward",
        }
    },
    {
        id: 23,
        description: "chaussette",
        photoURL: "img/taiga.jpg",
        utilisateur: {
            photoURL: "img/taiga.jpg",
            nom: "jean",
        }
    },
]

var reaction = {
    love: 23
}


var commentaires = [
    {
        id: 23,
        contenu: "blabla",
        date: "2020-02-12 12:12:20",
        utilisateur: {
            photoURL: "img/taiga.jpg",
            nom: "Bapt",
        }
    },
    {
        id: 23,
        contenu: "blabla",
        date: "2020-02-12 12:12:20",
        utilisateur: {
            photoURL: "img/taiga.jpg",
            nom: "Malo",
        }
    },
]


// ------------- ajout d'elements ------------------

var commentaire = {
    date: "2020-02-12 12:12:20",
    contenu: "Bonjour, je suis un commentaire",
    utilisateur: {
        nom: "utilisateur",
        photoURL: "img/taiga.jp"
    }
};

var evenement = {
    id: 21,
    titre: "",
    lieu: "",
    date: "2020-02-12 12:12:20"
};

//AFFICHAGE INDIVIDUEL////////////////////////////////////////////////////////////////////
function afficher_groupe(groupe_json) { //affiche les groupes sur le fil d'actualité

    var groupe = document.createElement('div');//crée l'element html
    groupe.className = 'event'; //lui donne un attribut class contenant "event

    var groupe_container = document.createElement('div');
    groupe_container.className = 'container-event';

    var groupe_name = document.createElement('h2');
    groupe_name.className = 'event-name';
    var texte_groupe_name = document.createTextNode(groupe_json.titre);
    groupe_name.appendChild(texte_groupe_name);

    var groupe_date = document.createElement('div');
    groupe_date.className = 'event-date';
    var texte_groupe_date = document.createTextNode(groupe_json.date);
    //Permet de garder seulement le nombre du jour
    var day = texte_groupe_date.textContent.substring(8, 10);
    groupe_date.innerHTML = day;

    groupe_container.appendChild(groupe_name); //ajoute un enfant groupe_name à groupe_container
    groupe_container.appendChild(groupe_date);
    groupe.appendChild(groupe_container);
    document.querySelector('.fil').appendChild(groupe); //ajoute au fil d'actualité
}

function afficher_publication(publication_json) { //affiche les pré_vues des publication sur la page du groupe
    var publication = document.createElement('div');
    publication.className = 'publication';

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

function afficher_publication_detail(publication_json) {//affiche le detail d'une publication (sans les commentaires)
    //PUBLICATION

    var publication_image = document.createElement('div');
    publication_image.className = 'publication-image';

    var publication_image_url = document.createElement('img');
    publication_image_url.setAttribute('src', publication_json.photoURL);
    publication_image_url.alt = "photo";

    var publication_description = document.createElement('div');
    publication_description.className = 'publication-description';

    //USER
    var user = document.createElement('user');
    user.className = 'user';

    var user_name = document.createElement('div');
    user_name.className = 'user-name';
    var texte_user_name = document.createTextNode(publication_json.utilisateur.nom);

    var user_image = document.createElement('div');
    user_image.className = 'user-image roundImg';
    var user_image_url = document.createElement('img');
    user_image_url.src = publication_json.utilisateur.photoURL;
    user_image_url.alt = 'photo utilisateur';
    user_image.appendChild(user_image_url);
    user_name.appendChild(texte_user_name);
    user.appendChild(user_name);
    user.appendChild(user_image);

    publication_description.appendChild(user);

    //DESCRIPTION
    var description = document.createElement('p');
    var texte_description = document.createTextNode(publication_json.description);
    description.appendChild(texte_description);
    publication_description.appendChild(description);

    //DATE
    var publication_date = document.createElement('div');
    publication_date.className = 'publication-date';
    var texte_publication_date = document.createTextNode(publication_json.date);
    publication_date.appendChild(texte_publication_date);
    publication_description.appendChild(publication_date);

    //AUTRES APPEND
    document.querySelector('.publication-infos').insertBefore(publication_description,
        document.querySelector('.commentaires'));
    publication_image.appendChild(publication_image_url);
    document.querySelector('.publication').insertBefore(publication_image,
        document.querySelector('.publication-infos'));
}

function afficher_commentaire(commentaire_json) { //affiche un commentaire
    var commentaire = document.createElement('div');
    groupe.className = 'commentaire';

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
    document.querySelector('.commentaires').appendChild(commentaire);
}

//AFFICHAGES MULTIPLES////////////////////////////////////////////////////////////////////////////
function afficher_toutes_les_publications(publications) {
    for (let i = 0; i < publications.length; i++) {
        afficher_publication(publications[i]);
    }
}

function afficher_tous_les_commentaire(commentaires) {
    for (let i = 0; i < commentaires.length; i++) {
        afficher_commentaire(commentaires[i]);
    }
}

function afficher_tous_les_groupes(groupes) {
    for (let i = 0; i < groupes.length; i++) {
        afficher_groupe(groupes[i]);
    }
}

//AFFICHAGE DE PAGE////////////////////////////////////////////////////////////////////
//genere les divs pour injecter les infos des requettes

function afficher_fil_actualite() {
    var container_index = document.createElement('div');
    container_index.className = "container-index";

    var sidebar = document.createElement('div');
    sidebar.className = "sidebar";
    container_index.appendChild(sidebar);

    var mois_selection = document.createElement('div');
    mois_selection.className = "mois-selection";
    sidebar.appendChild(mois_selection);

    var menu = document.createElement('ul');
    menu.className = "menu";
    menu.id = "menu";
    mois_selection.appendChild(menu);

    var fil = document.createElement('div');
    fil.className = "fil";
    container_index.appendChild(fil);

    document.querySelector('.affichage').appendChild(container_index);
}

function afficher_page_groupe() {
    var header = document.createElement('header');
    header.className = 'header';
    var publications = document.createElement('publications');
    publications.className = 'publications';
    document.querySelector('.affichage').appendChild(header);
    document.querySelector('.affichage').appendChild(publications);
}

function afficher_titre_page_groupe(groupe_json) {//marche
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

    document.querySelector('.header').appendChild(groupe_name);
    document.querySelector('.header').appendChild(groupe_date);
    document.querySelector('.header').appendChild(groupe_lieu);

}

function afficher_page_publication() {
    var publication = document.createElement('div');
    publication.className = 'publication';

    var publication_infos = document.createElement('div');
    publication_infos.className = 'publication-infos';

    var commentaires = document.createElement('div');
    commentaires.className = 'commentaires';

    publication_infos.appendChild(commentaires);
    publication.appendChild(publication_infos);
    document.querySelector('.affichage').appendChild(publication);
}

//EVENEMENT////////////////////////////////////////////////////////////////////////////

//COMMANDES POUR AFFICHER TOUT LE FIL D'ACTULITÉ
afficher_fil_actualite();
console.log('cc');
afficher_tous_les_groupes(groupes);

//COMMANDE POUR AFFICHER UN GROUPE EN ENTIER
//afficher_page_groupe();
//afficher_titre_page_groupe(groupe);
//afficher_toutes_les_publications(publications);

//COMMANDES POUR AFFICHER UNE PUBLICATION
afficher_page_publication();
afficher_publication_detail(publication);
afficher_tous_les_commentaire(commentaires);
//manque afficher réactions

// ------------- formulaires ------------------

