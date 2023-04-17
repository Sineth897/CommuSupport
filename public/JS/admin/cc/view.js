import { getData } from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import flash from "../../flashmessages/flash.js";
import {displayTable} from "../../components/table.js";
import Flash from "../../flashmessages/flash.js";

const viewBtns = document.querySelectorAll('a.btn-primary');

for(let i=0; i<viewBtns.length; i++){
    viewBtns[i].addEventListener('click', showPopup)
}

const popup = new PopUp();

function showPopup(e) {
    console.log(e.target.id);
}


const filterOptions = document.getElementById('filterOptions');
const cho = document.getElementById('cho');
const ccTable = document.getElementById('ccTable');

document.getElementById('filter').addEventListener('click', function(e) {
    if(filterOptions.style.display === 'block') {
        filterOptions.style.display = 'none';
    } else {
        filterOptions.style.display = 'block';
    }
});

document.getElementById('filterBtn').addEventListener('click', async function() {
    let data = {};
    if(cho.value) {
        data['cho'] = cho.value;
    }

    let result = await getData('./communitycenters/filter', 'post', data);

    if(!result['status']) {
        Flash.showMessage({type:'error', value:'Something went wrong'}, 5000);
    }

    result['CCs'].forEach(function(cc) {
       cc['cho'] = result['chos'][cc['cho']];
    });

    const tableData = {
        headings: ['City', 'Address', 'Contact Number', 'Community Head Office'],
        keys: ['city', 'address', 'contactNumber', 'cho',['',"View",'#',[],'ccID']],
        data: result['CCs']
    }

    displayTable(ccTable, tableData);

    filterOptions.style.display = 'none';
});