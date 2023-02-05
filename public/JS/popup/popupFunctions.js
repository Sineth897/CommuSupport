
class PopUpFunctions {

    getUpdatedValues(target,fieldsToUpdate) {
        target.innerHTML = 'Update';
        let updateValues = {};
        for(let i = 0; i < fieldsToUpdate.length ; i++) {
            let field = document.getElementById(fieldsToUpdate[i]);
            field.setAttribute('disabled','');
            updateValues[fieldsToUpdate[i]] = field.value;
        }
        return updateValues;

    }

    changeToInput(target,fieldsToUpdate) {
        target.innerHTML = 'Confirm';
        for(let i = 0; i < fieldsToUpdate.length ; i++) {
            document.getElementById(fieldsToUpdate[i]).removeAttribute('disabled');
        }
    }

    hideAllElementsWithin(parent) {
        for(let i = 0; i < parent.children.length; i++) {
            parent.children[i].style.display = 'none';
        }
    }
}

export {PopUpFunctions};
