
<link rel="stylesheet" href="./public/CSS/cards/driver-delivery-card.css">

<style>

    @media print {

        .sidenav {
            display: none;
        }

    }

</style>

<?php

//$sql



?>

<button id="btn">Click</button>

<script>

    window.onload = function(){
        console.log("loaded");
        document.querySelector("#btn").addEventListener("click", function(){
            console.log("clicked");
            window.print();
        })
    }




    // window.print();


</script>
