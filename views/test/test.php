<!--<link rel="stylesheet" href="./public/CSS/cards/request-card.css">-->
<link rel="stylesheet" href="./public/CSS/cards/cc-donation-card.css">
<!--<link rel="stylesheet" href="./public/CSS/cards/postedRequestCard.css">-->
<link rel="stylesheet" href="./public/CSS/cards/request-card.css">

<div class="fake">
    <div class="sidenav-fake">

    </div>
    <div class="main-fake">
        <div class="profile-container">
            <div class="profile1">
                <h1>Driver</h1>
            </div>
            <div class="stats1">
                Stat
            </div>
            <div class="table">
<p>Heading</p>
                <div class="scroller">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .sidenav-fake {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 300px;
        background: var(--primary-color);
        padding: 6px 14px;
        z-index: 99;
        transition: all 0.5s ease;
    }

    .main-fake {
        position: absolute;
        height: 100vh;
        width: calc(100% - 300px);
        left: 300px;
        transition: all 0.5s ease;
        background-color: #f3f3f3;
        overflow-y: scroll;
        margin: 0;
    }

    .profile-container {
        height: 100%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(3, 1fr);
        grid-template-areas:
"profile1 stats1"
"profile1 table"
"profile1 table";
        grid-column-gap: 10px;
        grid-row-gap: 10px;
        width: 100%;
        padding: 10px 10px;

    }

    .profile-container > div {
        padding: 20px;
        border-radius: 12px;
        background-color: white;
    }

    .profile1 {
        grid-area: profile1;
    }

    .stats1 {
        grid-area: stats1;
    }

    .table{
        grid-area: table;
        display: block;
        overflow-y: scroll;
    }

    .scroller{
        overflow-y: scroll;
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 40px;
    }

    .bar{
        height: 100px;
        background-color: #00b705;
        width: 100%;
    }




</style>