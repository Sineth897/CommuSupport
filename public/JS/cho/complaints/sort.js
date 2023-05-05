
const sort_btn = document.getElementById("sort-btn")
sort_btn.addEventListener("click",sortTable);

function sortTable() {
    let table, rows, switching, i, x, y, shouldSwitch;
    table = document.querySelectorAll("tbody")[0];
    switching = true;
    while (switching) {
        switching = false;
        rows = table.getElementsByTagName("tr");
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[5];
            y = rows[i + 1].getElementsByTagName("td")[5];
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }


}


//sorting by date field
const filter = document.getElementById("filter");
const table = document.querySelectorAll("tbody")[0];
const rows = table.getElementsByTagName("tr");

filter.addEventListener("change", function() {
    var selectedType = this.value
    // console.log(selectedType)
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var type = row.getElementsByTagName("td")[3].textContent.toLowerCase();
        console.log(type)
        if (selectedType === "all" || selectedType === type) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
});


