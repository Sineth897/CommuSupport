export default function table() {
    return {
        createTable: createTable
    }
}

let tableKeys = [];

function createTable(tableDiv, array) {
    tableDiv.innerHTML = '';
    tableKeys = array['keys'];
    let table = document.createElement('table');
    let tableHead = document.createElement('thead');
    let tableRow = document.createElement('tr');
    let tableHeadings = array['headings'];
    tableHeadings.forEach( function (heading) {
        tableRow.append(Object.assign(document.createElement('th'),{innerHTML:heading}));
    });
    tableHead.appendChild(tableRow);
    table.appendChild(tableHead);
    table.appendChild(createTableBody(array['data']));
    tableDiv.appendChild(table);
}

function createTableBody(data) {
    let tableBody = document.createElement('tbody');
    data.forEach( function (row) {
        tableBody.appendChild(createTableRow(row));
    });
    return tableBody;
}

function createTableRow(row) {
    let tableRow = document.createElement('tr');
    tableKeys.forEach( function (cell) {
        tableRow.appendChild(createTableCell(row[cell]));
    });
    return tableRow;
}

function createTableCell(cell) {
    let tableCell = document.createElement('td');
    tableCell.innerHTML = cell;
    return tableCell;
}