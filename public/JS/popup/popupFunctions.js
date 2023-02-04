
class PopUpFunctions {

    update(target,fieldsToUpdate) {

        let fields = [];
        for(let i = 0; i < fieldsToUpdate.length ; i++) {
            fields[fieldsToUpdate[i]] = document.getElementById(fieldsToUpdate[i]);
        }

        if(target.innerHTML === 'Update') {

        }

    }

}

export {PopUpFunctions};
