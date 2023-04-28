<!--<link rel="stylesheet" href="./public/CSS/cards/request-card.css">-->


<style>

    .card-container {
        /*padding: 0 10%;*/
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 20px;
        margin: 20px;
    }


    .rq-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
        gap: 20px;
        /*height: 12.5rem;*/
        position: relative;
        display: grid;
        grid-template-columns: 2fr 1fr;
    }

    .rq-card-right {
        display: grid;
        grid-template-rows: 1fr 1fr 1fr;
        grid-gap: 10px;
        padding: 10px;
        font-size: 0.8rem;
    }

    .rq-category{
        color: #9b9b9b;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .rq-description{
        margin-top: 10px;
        font-size: 0.8rem;
    }

    .rq-approval {
        display: flex;
        flex-direction: row;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .rq-approval > p {
        color: crimson;
    }

    .rq-card-left {
        display: grid;
        grid-template-rows: 1fr .5fr 2fr 1fr;

    }

    .rq-card-info {
        display: flex;
        flex-direction: row;
        gap: 0.5rem;
    }

    @media only screen and (max-width: 1400px) {
        .card-container {
            grid-template-columns: 1fr;
        }
    }




</style>


<div class="content">
    <div class="card-container">
        <div class="rq-card" id="request63f8697d115ca9.8">
            <div class="rq-card-left">
                <div class="rq-card-header">
                    <h1>Children clothes</h1>
                </div>
                <div class="rq-category">
                    <p>Clothes</p>
                </div>
                <div class="rq-description">
                    <p>something something</p>
                </div>
                <div class="rq-approval">
                    <strong> Approval : </strong> <p> approval </p>
                </div>

            </div>

            <div class="rq-card-right">
                <div class="rq-card-info">
                    <strong> Posted date : </strong>
                    <p> date </p>
                </div>
                <div class="rq-card-info">
                    <strong> Urgency : </strong>
                    <p> urgency </p>
                </div>
                <div class="rq-card-info">
                    <strong> Expired date : </strong>
                    <p> date </p>
                </div>


            </div>



<!--            <div class="rq-btns">-->
<!--                <button class="rq-btn btn-primary viewActiveRequest" value="request63f8697d115ca9.8">View</button>-->
<!--            </div>-->
        </div>
    </div>
</div>
