class PopUp {

    popInfoFlag = false;
    splitFormFlag = false;
    splitDiv = null;
    statusIcon = {
        Upcoming: 'status-green',
    }

    inputFields = [];
    constructor(background = 'popUpBackground', container = 'popUpContainer', info = 'popUpInfo') {
        this.popUpBackgroud = document.getElementById(background);
        this.popUpContainer = document.getElementById(container);
        this.popUpInfo = document.getElementById(info);

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
        if(this.splitFormFlag) {
            this.splitDiv.append(this.popUpDetails);
        }
        else {
            this.popUpContainer.append(this.popUpDetails);
        }
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
            if(buttons[i]['cancel'] ) {
                this.popUpButtons.append(this.getCancelButton(buttons[i]));
            }
        }
        this.popUpContainer.append(this.popUpButtons);
    }

    insertHeading(heading) {
        this.heading = document.createElement('h3');
        this.heading.classList.add('form-heading');
        this.heading.innerHTML = heading;
        if(this.splitFormFlag) {
            this.splitDiv.append(this.heading);
        }
        else {
            this.popUpContainer.append(this.heading);
        }
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
        this.inputFields.push(this.field);
        return this.field;
    }

    getTextArea(value,id) {
        this.field = document.createElement('textarea');
        this.field.setAttribute('class','basic-text-area description');
        this.field.setAttribute('disabled','');
        this.field.innerHTML = value;
        this.field.setAttribute('id',id);
        this.inputFields.push(this.field);
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

    getiTag(innerText,classes = []) {
        this.iTag = document.createElement('i');
        this.iTag.innerHTML = innerText;
        if(classes.length !== 0) {
            this.iTag.setAttribute('class',classes.join(' '));
        }
        return this.iTag;
    }

    getpTag(innerText,classes = []) {
        this.pTag = document.createElement('p');
        this.pTag.innerHTML = innerText;
        if(classes.length !== 0) {
            this.pTag.setAttribute('class',classes.join(' '));
        }
        return this.pTag;
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
        this.inputFields = [];
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

    getCancelButton(button) {
        let cancelBtn = this.getButton({text:'Cancel',classes:['btn-secondary'],value:'Cancel'});
        cancelBtn.style.display = 'none';

            cancelBtn.addEventListener('click',(e) => {
                this.showAllButtonsExceptCancel(e.target.parentElement);
                cancelBtn.previousSibling.innerHTML = button['text'];
                cancelBtn.style.display = 'none';
                if(button['text'] === 'Update') {
                    for(let i = 0; i < this.inputFields.length; i++) {
                        this.inputFields[i].setAttribute('disabled','');
                    }
                }
            });

        return cancelBtn;
    }

    startPopUpInfo(info) {
        this.popUpInfo = this.getDiv('',['popup-info']);
        this.popInfoFlag = true;
    }

    endPopUpInfo() {
        this.popUpContainer.append(this.popUpInfo);
        this.popInfoFlag = false;
    }

    startSplitDiv() {
        this.splitDiv = this.getDiv('',['form-split']);
        this.splitFormFlag = true;
    }

    endSplitDiv() {
        this.popUpContainer.append(this.splitDiv);
        this.splitDiv = null;
        this.splitFormFlag = false;
    }

    showStatus(status) {
        this.statusDiv = this.getDiv('',['status']);
        this.statusDiv.append(this.getiTag('fiber_manual_record',['material-icons',this.statusIcon[status]]),this.getpTag(status,));

        if(this.popInfoFlag) {
            this.popUpInfo.append(this.statusDiv);
        }
        else {
            this.popUpContainer.append(this.statusDiv);
        }
    }

    showParticipants(participants) {
        this.participantsDiv = this.getDiv('',['participants']);
        this.participantsDiv.append(this.getiTag('people',['material-icons']),this.getpTag(participants,));
        if(this.popInfoFlag) {
            this.popUpInfo.append(this.participantsDiv);
        }
        else {
            this.popUpContainer.append(this.participantsDiv);
        }
    }

    showAllButtonsExceptCancel(parent) {
        for(let i = 0; i < parent.children.length; i++) {
            if(parent.children[i].value !== 'Cancel') {
                parent.children[i].style.display = 'block';
            }
        }
    }

    include (file) {
        let pdfDiv = document.createElement('div');
        let pdf = document.createElement('iframe');
        pdf.setAttribute('src',file);
        pdf.setAttribute('width','100%');
        pdf.setAttribute('height','100%');
        pdfDiv.append(pdf);
        this.popUpContainer.append(pdfDiv);
    }
}

export {PopUp};