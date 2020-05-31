function modale(content) {

    //Container global
    var modale = document.createElement('div');
    modale.className = 'modal';
    modale.setAttribute('style', 'display: block;');

    //bouton fermeture modale
    var closeModal = document.createElement('span');
    closeModal.className = "closeModal";
    closeModal.innerText ="fermer";
    closeModal.addEventListener('click', function () {
        var modale = this.parentNode;
        modale.remove();
    });

    modale.append(closeModal);
    modale.append(content);

    document.getElementById('affichage').append(modale);
}

