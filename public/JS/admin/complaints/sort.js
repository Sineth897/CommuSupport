
const pending = document.getElementById("pending")
pending.addEventListener("click",function (e){
    e.preventDefault();
    filterComplaints("pending")

});

const completed = document.getElementById("completed")
completed.addEventListener("click",function (e){
    e.preventDefault();
    filterComplaints("completed")

});



//sorting by date field
const filter = document.getElementById("filter");
const table = document.querySelectorAll("tbody")[0];
const rows = table.getElementsByTagName("tr");

function filterComplaints(selectedType){
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
}

//sort by reviewed Date
const sort_btn = document.getElementById("sort")
// sort_btn.addEventListener("click",sortTable);
const sortOption = document.querySelectorAll("#sortOptions")[0]; //array 0th element
const sort = document.getElementById("sortBtn")
sort_btn.addEventListener("click",function(){

    let display = document.getElementById("sortOptions").style.display;
    if(display==="block"){
        document.getElementById("sortOptions").style.display="none";
    }
    else{
        document.getElementById("sortOptions").style.display="block";
    }

});

sort.addEventListener("click",sortTable)

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

    document.getElementById("sortOptions").style.display="none";
}

