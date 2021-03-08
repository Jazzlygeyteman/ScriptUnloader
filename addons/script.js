window.onload = function () {

    const checkAll = document.querySelector('.checkbox-toggle');
    const inputs = document.querySelectorAll('input');
    console.log(inputs.classList)

    inputs.forEach(input => {
        input.addEventListener('click', function () {
            let parent = input.parentElement;
            input.classList.toggle('add')
            parent.classList.toggle('add')
            console.log('check1');
        })
    })

    function uncheckAll(e) {
        e.preventDefault();
        this.removeEventListener('click', uncheckAll);
        inputs.forEach(input => {
            let parent = input.parentElement;
            input.checked = false;
            input.classList.remove('add')
            parent.classList.remove('add')
            console.log('check2');

        });
        checkAll.addEventListener('click', checkHandler);
    }


    function checkHandler(e) {
        e.preventDefault();
        this.removeEventListener('click', checkHandler);
        inputs.forEach(input => {
            let parent = input.parentElement;
//                 input.checked = true;
            if (input.classList = 'add') {
                input.checked = true;
                console.log(input.classList)
                input.classList.add('add')
                parent.classList.add('add')
                console.log('check3');
            } else {
                input.checked = false;
                console.log('checkfalse');
            }

        });
        checkAll.addEventListener('click', uncheckAll);
    }

    checkAll.addEventListener('click', checkHandler);
}