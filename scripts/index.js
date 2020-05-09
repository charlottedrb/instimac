console.log(window.innerWidth);
if (window.innerWidth < 998 ){
    let status = false;
    let mois = document.querySelectorAll('li.mois-name');
    let moisActif = document.querySelector('li.mois-name.active'); 
    
    document.getElementById('icon').onclick = function() {
        status = true;
        //affiche la liste de mois
        for (let i = 0; i < mois.length; i++) {
            mois[i].style.display = "block";
        }
    };
    
    document.getElementById('menu').addEventListener("click", (e) => {
        console.log(e.target);
    
        //on récupère le mois .active qui sera toujours en 1ère position
        let active = document.querySelector('#menu>li.active');
        
        //on stocke les <a> dans des variables
        let tmp = active.innerHTML
        active.innerHTML = e.target.outerHTML;
        e.target.outerHTML = tmp;
    
        let notActive = document.querySelectorAll('#menu>li:not(.active)');
        console.log(notActive);
        notActive.forEach(element => {
            element.style.display = "none";
        });
    });
}






