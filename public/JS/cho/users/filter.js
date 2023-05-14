

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

// filtering donee donr organizaton indiviual

const filter = document.querySelectorAll('#filter p')[0];
const filterBtn = document.querySelectorAll("#filterBtn")[0];

filter.addEventListener("click",function (){

    let display = document.getElementById("filterOptions").style.display;
    if(display==="block"){
        document.getElementById("filterOptions").style.display="none";
    }
    else{
        document.getElementById("filterOptions").style.display="block";
    }
})

const table = document.querySelectorAll("tbody")[0];
const sortRows = table.getElementsByTagName("tr");

filterBtn.addEventListener("click",function(){
    let selectedType = document.getElementById("filterCategory").value;

    for(let i=0;i<sortRows.length;i++)
    {
        var row = sortRows[i];
        var type1 = row.getElementsByTagName("td")[1].textContent.toLowerCase();
        var type2 = row.getElementsByTagName("td")[5].textContent.toLowerCase();
        if(selectedType=="all"|| selectedType===type1 ||selectedType===type2)
        {
            row.style.display="";

        }
        else{
            row.style.display="none";
        }
    }
    document.getElementById("filterOptions").style.display="none";
})

