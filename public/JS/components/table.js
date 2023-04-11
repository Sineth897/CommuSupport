
let tableKeys = [];

function displayTable(tableDiv, array) {

    //clearing the div
    tableDiv.innerHTML = '';

    //getting the array keys
    tableKeys = array['keys'];
    let table = document.createElement('table');

    //creating the header of the table
    let tableHead = document.createElement('thead');
    let tableRow = document.createElement('tr');
    let tableHeadings = array['headings'];

    //creating the header cells
    tableHeadings.forEach( function (heading) {
        tableRow.append(Object.assign(document.createElement('th'),{innerHTML:heading}));
    });
    tableHead.appendChild(tableRow);

    //appending the table header
    table.appendChild(tableHead);

    //create and append the table body
    table.appendChild(createTableBody(array['data']));

    //appending the table to the div
    tableDiv.appendChild(table);
}

//creating the table body using the data
function createTableBody(data) {
    let tableBody = document.createElement('tbody');
    // creating the table rows
    data.forEach( function (row) {
        tableBody.appendChild(createTableRow(row));
    });
    return tableBody;
}

function createTableRow(row) {
    let tableRow = document.createElement('tr');

    //creating the table cells using the array keys
    tableKeys.forEach( function (cell) {
        //checking whether key is an array or not , to create buttons
        if(Array.isArray(cell)) {
            if(cell.length === 3 && cell[1] === 'bool') {
                tableRow.appendChild(createTableCell(cell[2][row[cell[0]]]));
            } else {
                tableRow.appendChild(createButton(cell,row));
            }
        } else {
            tableRow.appendChild(createTableCell(row[cell]));
        }
    });
    return tableRow;
}

// creating the table cell
function createTableCell(cell) {
    let tableCell = document.createElement('td');
    tableCell.innerHTML = cell;
    return tableCell;
}

//creating the button for the table
function createButton(btnInfo,row) {
    const tableCell = document.createElement('td');

    //check whether the field is empty
    if(row[btnInfo[0]]) {
        //if not empty, return the cell with value
        return createTableCell(row[btnInfo[0]]);
    }

    //creating the button using a anchor tag
    const btn = document.createElement('a');
    btn.classList.add('btn-primary');
    if(btnInfo[4]) {
        btn.id = row[btnInfo[4]];
    }
    btn.innerHTML = btnInfo[1];

    //adding href
    btn.href = btnInfo[2] + `?` + btnInfo[3].map((param) => {return row[param]}).join('&')

    tableCell.appendChild(btn);
    return tableCell;
}

export { displayTable };