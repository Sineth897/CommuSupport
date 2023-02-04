class PopUp {
    constructor() {
        this.popUpBackgroud = document.getElementById("popUpBackground");
        this.popUpContainer = document.getElementById("popUpContainer");
        this.popUpInfo = document.getElementById("popUpInfo");

    }

    setHeader(heading,obj = {},subheading = '') {
        this.popUpHeader = this.getDiv('popUpHeader',['popup-header']);
        this.header = document.createElement('h1');
        this.subheading = document.createElement('h4');
        if(Object.keys(obj).length !== 0) {
            this.header.innerHTML = obj[heading];
            if(subheading) {
                this.subheading.innerHTML = obj[subheading];
            }
        }
        else {
            this.header.innerHTML = heading;
            if(subheading) {
                this.subheading.innerHTML = subheading;
            }
        }
        this.popUpHeader.append(this.header);
        if(subheading.innerHTML !== '') {
            this.popUpHeader.append(this.subheading);
        }
        this.popUpContainer.append(this.popUpHeader);
    }

    setBody(arr, arrKeys, labels =[]) {
        this.popUpDetails = this.getDiv('',['popup-details']);
        for(let i = 0; i < arrKeys.length; i++) {
            if(labels.length !== 0) {
                this.setField(labels[i],arr[arrKeys[i]],arrKeys[i]);
            }
            else {
                this.setField(arrKeys[i],arr[arrKeys[i]],arrKeys[i]);
            }
        }
        this.popUpContainer.append(this.popUpDetails);
    }

    setField(label,value,id) {
        if(Array.isArray(label)) {
            this.label = this.getLabel(label[0],id);
            if(label[1] === 'textarea') {
                this.field = this.getTextArea(value,id);
            }
            else {
                this.field = this.getInputField(label[1], value,id);
            }
        }
        else {
            this.label = this.getLabel(label,id);
            this.field = this.getInputField('text',value,id);
        }
        this.inputDiv = document.createElement('div');
        this.inputDiv.setAttribute('class','form-group');
        this.inputDiv.append(this.label,this.field);
        this.popUpDetails.appendChild(this.inputDiv);
    }

    setButtons(buttons) {
        this.popUpButtons = this.getDiv('popUpButtons',['popup-btns']);
        for(let i = 0; i < buttons.length; i++) {
            this.popUpButtons.append(this.getButton(buttons[i]));
        }
        this.popUpContainer.append(this.popUpButtons);
    }

    getLabel(label,forId) {
        this.label = document.createElement('label');
        this.label.setAttribute('class','form-label');
        this.label.innerHTML = label;
        this.label.setAttribute('for',forId);
        return this.label;
    }

    getInputField(type, value, id ) {
        this.field = document.createElement('input');
        this.field.setAttribute('class','basic-input-field');
        this.field.setAttribute('type',type);
        this.field.setAttribute('value',value);
        this.field.setAttribute('id',id);
        this.field.setAttribute('disabled','');
        return this.field;
    }

    getTextArea(value,id) {
        this.field = document.createElement('textarea');
        this.field.setAttribute('class','basic-text-area description');
        this.field.setAttribute('disabled','');
        this.field.setAttribute('rows','6');
        this.field.innerHTML = value;
        this.field.setAttribute('id',id);
        return this.field;
    }
    setCloseButton() {
        this.closeIcon = document.createElement('i');
        this.closeIcon.setAttribute('class','material-icons');
        this.closeIcon.innerHTML = "close";
        this.closeButtonDiv = this.getDiv('closeButtonDiv',['close']);
        this.closeButtonDiv.append(this.closeIcon);
        this.closeButtonDiv.addEventListener('click',() => {
            document.getElementById("popUpContainer").innerHTML = "";
            document.getElementById("popUpBackground").style.display = "none";
        });
        this.popUpContainer.append(this.closeButtonDiv);
    }

    getDiv(id = "", classes = []) {
        this.div = document.createElement('div');
        if(id !== "") {
            this.div.setAttribute('id',id);
        }
        if(classes.length !== 0) {
            this.div.setAttribute('class',classes.join(' '));
        }
        return this.div;
    }
    showPopUp() {
        this.popUpBackgroud.style.display = "flex";
    }

    hidePopUp() {
        this.popUpBackgroud.style.display = "none";
    }

    clearPopUp() {
        this.popUpContainer.innerHTML = "";
        this.setCloseButton();
    }

    getButton(button) {
        this.button = document.createElement('button');
        let buttonClass = button['classes'];
        if(Array.isArray(buttonClass)) {
            buttonClass = buttonClass.join(' ');
        }
        this.button.setAttribute('class','btn ' + buttonClass);
        this.button.setAttribute('id',button['text']);
        this.button.innerHTML = button['text'];
        if(button['value'] !== undefined) {
            this.button.setAttribute('value',button['value']);
        }
        if(button['func'] !== undefined) {
            this.button.addEventListener('click',button['func']);
        }
        return this.button;
    }
}

export {PopUp};