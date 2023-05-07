
const searchInput = document.getElementById('search');
const rows = document.querySelectorAll("tbody tr");

searchInput.addEventListener('keyup',function (event) {
    //console.log(event);
    const q = event.target.value.toLowerCase();

    let searchData=[]
    rows.forEach((row) => {
        const td = row.querySelectorAll('td')
        td.forEach((ele) => {
            if (ele.textContent.toLowerCase().trim().startsWith(q)) {
                searchData.push(ele.parentElement)

            }


        });

    });

    searchData = searchData.filter((value,index)=>{
        return searchData.indexOf(value)===index;
    })

    rows.forEach((row)=>{
        row.style.display="none";
    })
    searchData.forEach((data)=>{
        data.style.display=""
    })
});


const filter = document.getElementById("filter");
const table = document.querySelectorAll("tbody")[0];
const fRows = table.getElementsByTagName("tr");

filter.addEventListener("change", function() {
    var selectedType = this.value
    // console.log(selectedType)
    for (var i = 0; i < fRows.length; i++) {
        var row = fRows[i];
        var type1 = row.getElementsByTagName("td")[1].textContent.toLowerCase();
        var type2 = row.getElementsByTagName("td")[5].textContent.toLowerCase();

        if (selectedType === "all" || selectedType === type1 || selectedType === type2) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
});


