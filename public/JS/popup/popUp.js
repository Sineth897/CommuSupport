class PopUp {

    popInfoFlag = false;
    splitFormFlag = -1;
    splitDiv = [];
    statusIcon = {
        Upcoming: 'status-green',
    }

    inputFields = [];
    constructor(background = 'popUpBackground', container = 'popUpContainer', info = 'popUpInfo') {
        this.popUpBackgroud = document.getElementById(background);
        this.popUpContainer = document.getElementById(container);
        this.popUpInfo = document.getElementById(info);

        this.splitDiv.push(this.popUpContainer);

        this.splitFormFlag++;
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

    setSubHeading(subheading) {
        this.subheading = document.createElement('h4');
        this.subheading.innerHTML = subheading;
        this.splitDiv[this.splitFormFlag].append(this.subheading);
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
        this.splitDiv[this.splitFormFlag].append(this.popUpDetails);
    }

    setField(label,value,id) {
        if(Array.isArray(label)) {
            this.label = this.getLabel(label[0],id);
            if(label[1] === 'textarea') {
                this.field = this.getTextArea(value,id);
            }
            else if(label[1] === 'bool') {
                value = (value === 1) ? 'Yes' : 'No';
                this.field = this.getInputField('text', value,id);
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
        this.splitDiv[this.splitFormFlag].append(this.heading);
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

    setComplaintIcon(processID, processName){
        this.complaintHyper = document.createElement('a');
        this.complaintHyper.setAttribute('href','./complaints/file?processID=' + processID +'&process=' + processName);
        this.complaintIcon = document.createElement('i');
        this.complaintIcon.setAttribute('class','material-icons');
        this.complaintIcon.innerHTML = "report";
        this.complaintIconDiv = this.getDiv('complaintIconDiv',['complaint-icon']);
        this.complaintIconDiv.append(this.complaintHyper);
        this.complaintHyper.append(this.complaintIcon);
        this.popUpContainer.append(this.complaintIconDiv);
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

    hidePopUpContainer() {
        this.popUpContainer.style.display = "none";
    }

    showPopUpContainer() {
        this.popUpContainer.style.display = "block";
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

    startDiv() {
        this.splitDiv.push(this.getDiv(document.createElement('div')));
        this.splitFormFlag++;
    }

    endDiv() {
        this.splitDiv[this.splitFormFlag-1].append(this.splitDiv[this.splitFormFlag]);
        this.splitDiv.pop();
        this.splitFormFlag--;
    }

    startSplitDiv() {
        this.splitDiv.push(this.getDiv('',['form-split']));
        this.splitFormFlag++;
    }

    endSplitDiv() {
        this.splitDiv[this.splitFormFlag-1].append(this.splitDiv[this.splitFormFlag]);
        this.splitDiv.pop();
        this.splitFormFlag--;
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

    include (files) {
        let filesDiv = document.createElement('div');
        filesDiv.classList.add('file-upload');
        for(let i = 0; i < files.length; i++) {
            let file = files[i];
            let fileDiv = document.createElement('div');
            fileDiv.classList.add('file-upload-item');
            let fileLabel = Object.assign(document.createElement('p'),{innerHTML:file['name']});
            let anchor = Object.assign(this.getaTag(file['url'],''),{target:'_blank'});
            let icon = document.createElement('i');
            icon.setAttribute('class','material-icons');
            icon.innerHTML = 'visibility';
            anchor.append(icon);
            fileDiv.append(fileLabel,anchor);
            filesDiv.append(fileDiv);
        }
        this.popUpContainer.append(filesDiv);
    }

    getaTag(href,innerText) {
        this.aTag = document.createElement('a');
        this.aTag.setAttribute('href',href);
        this.aTag.innerHTML = innerText;
        return this.aTag;
    }

    insertElement(element) {
        if(this.popInfoFlag) {
            this.popUpInfo.append(element);
        }
        else {
            this.popUpContainer.append(element);
        }
    }

    getElement(elementTag,classes = [],innerText = '') {
        this.element = document.createElement(elementTag);
        this.element.innerHTML = innerText;
        if(classes.length !== 0) {
            this.element.setAttribute('class',classes.join(' '));
        }
        return this.element;
    }

    static deliveryStageText = [
        ['Delivery assigned to a driver',['Delivery arrived at the CC','Delivery delivered to donee']],
        ['Delivery assigned to a driver','Delivery arrived at the CC','Delivery delivered to donee'],
        ['Delivery assigned to a driver',"Delivery arrived at the donor's CC","Delivery transferred to donee's CC",'Delivery delivered to donee']];

    setDeliveryDetails(subdeliveries) {
        const deliveryDiv = this.getDiv('',['popup-delivery']);

        deliveryDiv.innerHTML = `<div class="delivery-header"><h4>Delivery Progress</h4></div>`;

        this.deliveryDiv = this.getDiv('',['delivery-progress']);
        this.deliverySteps = this.getElement('ul',['delivery-steps']);
        this.deliveryDiv.append(this.deliverySteps);
        const subdeliveryCount = subdeliveries[0]['subdeliveryCount'];
        const deliveryStageText = PopUp.deliveryStageText[subdeliveryCount-1];

        //To show the initial step where the first delivery should be marked separately
        this.deliverySteps.append(this.getDeliveryStage(subdeliveries[0],0,deliveryStageText));

        this.deliverySteps.innerHTML = ``;
        //then for each remaining subdelivery, create a delivery stage
        for(let i = 0; i < subdeliveryCount; i++) {

            if( i === 0 ) {

                const initialStage = this.getInitialStages(subdeliveries[i],deliveryStageText);
                initialStage.forEach((stage) => {
                    // console.log(stage);
                    this.deliverySteps.append(stage);
                });

            }
            else if ( i >= subdeliveries.length) {
                this.deliverySteps.append(this.getDeliveryStage([],i,deliveryStageText));
            }
            else {
                this.deliverySteps.append(this.getDeliveryStage(subdeliveries[i],i,deliveryStageText));
            }


        }
        deliveryDiv.append(this.deliveryDiv);
        this.splitDiv[this.splitFormFlag].append(deliveryDiv);

    }

    getDeliveryStage(subdelivery,stage,deliveryStageText) {
        const deliveryStage = this.getElement('li',['delivery-stage']);

        if(subdelivery) {
            deliveryStage.innerHTML = `<span class="stage-number">${stage+2}</span><p class="stage-label next-stages-description">${deliveryStageText[stage+1]}</p>`;
            return deliveryStage;
        }

        switch (subdelivery['deliveryStatus']) {
            case 'Completed':
                deliveryStage.innerHTML = `<span class="stage-number">${stage+2}</span><p class="stage-label completed-stage-description">${deliveryStageText[stage+1]}</p>`;
                break;
            default:
                deliveryStage.classList.add('current-stage');
                deliveryStage.innerHTML = `<span class="stage-number">${stage+2}</span><p class="stage-label current-stage-description">${deliveryStageText[stage+1]}</p>`;
        }

        return deliveryStage;
    }

    getInitialStages(subdelivery,deliveryStageText) {
        const firstStage = this.getElement('li',['delivery-stage']);
        const secondStage = this.getElement('li',['delivery-stage']);

        const secondStageTextIndex = subdelivery['end'].includes('donee') ? 1 : 0;

        switch (subdelivery['deliveryStatus']) {
            case 'Not Assigned':
            case 'Not assigned':
                firstStage.classList.add('current-stage');
                firstStage.innerHTML = `<span class="stage-number">1</span><p class="stage-label current-stage-description">${deliveryStageText[0]}</p>`;
                secondStage.innerHTML = `<span class="stage-number">2</span><p class="stage-label next-stages-description">${ subdelivery['subdeliveryCount'] === 1 ? deliveryStageText[1][secondStageTextIndex] : deliveryStageText[1] }</p>`;
                break;
            case 'Ongoing':
                secondStage.classList.add('current-stage');
                firstStage.innerHTML = `<span class="stage-number">1</span><p class="stage-label completed-stages-description">${deliveryStageText[0]}</p>`;
                secondStage.innerHTML = `<span class="stage-number">2</span><p class="stage-label current-stage-description">${ subdelivery['subdeliveryCount'] === 1 ? deliveryStageText[1][secondStageTextIndex] : deliveryStageText[1] }</p>`;
                break;
            default:
                firstStage.innerHTML = `<span class="stage-number">1</span><p class="stage-label completed-stages-description">${deliveryStageText[0]}</p>`;
                secondStage.innerHTML = `<span class="stage-number">2</span><p class="stage-label completed-stages-description">${ subdelivery['subdeliveryCount'] === 1 ? deliveryStageText[1][secondStageTextIndex] : deliveryStageText[1] }</p>`;
        }
        return [firstStage,secondStage];
    }

}

export {PopUp};