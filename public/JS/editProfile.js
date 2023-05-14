import {getData, getTextData} from "./request.js";
import flash from "./flashmessages/flash.js";

const editProfileBtn = document.querySelector('#edit-profile');

const popUpBackground = document.querySelector('#popUpBackground');
const popUpContainer = document.querySelector('#popUpContainer');

const userType = document.querySelector('#userType').value;

const profile = document.querySelector('.profile');

const logout = document.querySelector('.logout form button');

const editBtnContainer = document.querySelector('.edit-change-password');

let initialValues = [];

const fields = {
    'donorIndividual': ['email', 'contactNumber'],
    'donorOrganization': ['representative', 'representativeContact', 'contactNumber', 'email'],
    'doneeIndividual': ['email', 'contactNumber'],
    'doneeOrganization': ['representative', 'representativeContact', 'contactNumber', 'email'],
    'logistic': ['address', 'contactNumber'],
    'manager': ['address', 'contactNumber'],
    'driver': ['address', 'contactNumber'],
}

function closePopup() {
    popUpBackground.style.display = 'none';
    popUpContainer.style.display = 'block';
    popUpBackground.querySelector('#popUpContainerEdit').remove();
}

const editProfileForm = `
  
    <div class="pass-requirement" style="width: 350px">
    
        <ul>
            <li>Enter new values in relevant fields </li>
            <li>If you change your mobile number you will be logged out and will need to verify your mobile number again.</li>
            <li>Confirm after updating</li>
        </ul>
        
    </div>
    
    <div class="popup-btns">
        <button class="btn-primary" id="editAgree">Agree</button>  
        <button class="btn-danger" id="cancel">Cancel</button>  
    </div>  
    `

editProfileBtn.addEventListener('click', showEditProfile);

function showEditProfile() {

    const popUpContainerEdit = document.createElement('div');
    popUpContainerEdit.classList.add('popup');
    popUpContainerEdit.id = 'popUpContainerEdit';

    popUpContainerEdit.innerHTML = editProfileForm;

    popUpBackground.style.display = 'flex';
    popUpContainer.style.display = 'none';
    popUpBackground.appendChild(popUpContainerEdit);

    popUpContainerEdit.querySelector('#cancel').addEventListener('click', closePopup);

    popUpContainerEdit.querySelector('#editAgree').addEventListener('click', enableEditableFields);
}

function toggleDisable(e) {
    e.disabled = !e.disabled;
}

function enableEditableFields(e) {

    const UpdatableFields = fields[userType].map(field => document.getElementById(field));

    // console.log(UpdatableFields);

    initialValues = UpdatableFields.map(field => field.value);

    UpdatableFields.forEach(toggleDisable);

    const btnDiv = document.createElement('div');
    btnDiv.classList.add('popup-btns');
    btnDiv.innerHTML = `<button class="btn-primary" id="confirm">Confirm</button>
            <button class="btn-danger" id="cancel">Cancel</button>`

    profile.appendChild(btnDiv);

    profile.querySelector('#confirm').addEventListener('click', updateProfile);
    profile.querySelector('#cancel').addEventListener('click', cancelUpdate);

    closePopup();

    // console.log(initialValues);

    editBtnContainer.style.display = 'none';

}

function cancelUpdate() {

    const UpdatableFields = fields[userType].map(field => document.getElementById(field));

    UpdatableFields.forEach(toggleDisable);

    profile.querySelector('.popup-btns').remove();

    UpdatableFields.forEach((field, index) => field.value = initialValues[index]);

    initialValues = [];

    editBtnContainer.style.display = 'flex';

}

async function updateProfile() {

        const UpdatableFields = fields[userType].map(field => document.getElementById(field));

        let data = {};

        for(let i = 0; i < UpdatableFields.length; i++) {
            if(UpdatableFields[i].value !== initialValues[i]) {
                data[fields[userType][i]] = UpdatableFields[i].value;
            }
        }

        const result = await getData('../updateprofile', 'post', {data:data,userType:userType});

        // console.log(result);

        if(!result['status']) {
            flash.showMessage({type:'error',value:"Profile update failed"});
            cancelUpdate();
            return;
        }

        UpdatableFields.forEach(toggleDisable);

        editBtnContainer.style.display = 'flex';
        profile.querySelector('.popup-btns').remove();

        flash.showMessage({type:'success',value:"Profile updated successfully"});

        if(userType.includes('donor') || userType.includes('donee') && data['contactNumber']) {
            logout.click();
        }

}