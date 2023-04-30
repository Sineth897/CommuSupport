
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
