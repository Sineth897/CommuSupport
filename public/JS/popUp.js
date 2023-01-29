class PopUp {
    constructor() {
        this.popUpBackgroud = document.getElementById("popUpBackground");
        this.popUpContainer = document.getElementById("popUpContainer");
    }

    setHeader(header,obj = {}) {
        this.header = document.createElement('h2');
        this.closeIcon = this.getCloseButton();
        if(Object.keys(obj).length !== 0) {
            this.header.innerHTML = obj[header];
        }
        else {
            this.header.innerHTML = header;
        }
        this.headerDiv = document.createElement('div');
        this.headerDiv.append(this.header,this.closeButton);
        this.popUpContainer.appendChild(this.headerDiv);
    }

    setBody(arr, arrKeys, labels =[]) {
        for(let i = 0; i < arrKeys.length; i++) {
            if(labels.length !== 0) {
                this.setField(labels[i],arr[arrKeys[i]]);
            }
            else {
                this.setField(arrKeys[i],arr[arrKeys[i]]);
            }
        }
    }

    setField(label,value) {
        if(Array.isArray(label)) {
            this.label = this.getLabel(label[0]);
            if(label[1] === 'textarea') {
                this.field = this.getTextArea(value);
            }
            else {
                this.field = this.getInputField(label[1], value);
            }
        }
        else {
            this.label = this.getLabel(label);
            this.field = this.getInputField('text',value);
        }
        this.popUpContainer.appendChild(this.label);
        this.popUpContainer.appendChild(this.field);
    }

    getLabel(label,forId = "") {
        this.label = document.createElement('label');
        this.label.innerHTML = label;
        if(forId === "") {
            this.label.setAttribute('for',forId);
        }
        return this.label;
    }

    getInputField(type, value,id = "") {
        this.field = document.createElement('input');
        this.field.setAttribute('type',type);
        this.field.setAttribute('value',value);
        if(id !== "") {
            this.field.setAttribute('id',id);
        }
        return this.field;
    }

    getTextArea(value,id = "") {
        this.field = document.createElement('textarea');
        this.field.innerHTML = value;
        if(id !== "") {
            this.field.setAttribute('id',id);
        }
        return this.field;
    }
    getCloseButton() {
        this.closeButton = document.createElement('button');
        this.closeButton.innerHTML = "close";
        this.closeButton.addEventListener('click',() => {
            document.getElementById("popUpContainer").innerHTML = "";
            document.getElementById("popUpBackground").style.display = "none";
        });
        return this.closeButton;
    }
    showPopUp() {
        this.popUpBackgroud.style.display = "block";
    }

    hidePopUp() {
        this.popUpBackgroud.style.display = "none";
    }

    clearPopUp() {
        this.popUpContainer.innerHTML = "";
    }
}

export {PopUp};