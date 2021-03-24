//Zodra de pagina ingeladen word, laad de functie in.
window.onload = function () {
    //Check all en de inputs declareren
    const checkAll = document.querySelector('.checkbox-toggle');
    const inputs = document.querySelectorAll('input');
    console.log(inputs.classList)

    //Per checkbox checken of er daadwerkelijk op geklikt word
    inputs.forEach(input => {
        //Check of de klik plaatsvind
        input.addEventListener('click', function () {
            let parent = input.parentElement;
            //Voegt de .add class toe of verwijdert deze.
            input.classList.toggle('add')
            parent.classList.toggle('add')
            //Console log welke check er daadwerkelijk heeft plaats gevonden
            console.log('check1');
        })
    })

    //De uncheck functie declareren
    function uncheckAll(e) {
        //zorgt er voor dat de button, het form niet vertuurd
        e.preventDefault();
        //Als er op de uncheck bunnen geklikt word.
        this.removeEventListener('click', uncheckAll);
        //Check de waarde per input
        inputs.forEach(input => {
            let parent = input.parentElement;
            input.checked = false;
            //Verwijder de .add class van de checkbox
            input.classList.remove('add')
            parent.classList.remove('add')
            //Log naar de console welke check heeft plaats gevonden
            console.log('check2');

        });
        //Check via de checkHandler,
        checkAll.addEventListener('click', checkHandler);
    }


    function checkHandler(e) {
        //zorgt er voor dat de button, het form niet vertuurd
        e.preventDefault();
        this.removeEventListener('click', checkHandler);
        inputs.forEach(input => {
            let parent = input.parentElement;
            //als de class .add is toegevoegd aan het element voer dan de if statement uit.
            if (input.classList = 'add') {
                input.checked = true;
                console.log(input.classList)
                //voeg de .add class toe aan het element.
                input.classList.add('add')
                parent.classList.add('add')
                console.log('check3');
            } else {
                //verwijder de .add class doormiddel van de functie hierboven
                input.checked = false;
                console.log('checkfalse');
            }

        });
        //Voeg de click event doe, zodat als er geklikt word er daadwerkelijk wat gebeurt
        checkAll.addEventListener('click', uncheckAll);
    }

//Voeg de click event doe, zodat als er geklikt word er daadwerkelijk wat gebeurt
    checkAll.addEventListener('click', checkHandler);
}
