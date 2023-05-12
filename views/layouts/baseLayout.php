<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title}</title>

    <!--    import material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.7/css/boxicons.min.css">
    <link rel="stylesheet" href="/Commusupport/public/CSS/layout.css">
    <link rel="stylesheet" href="/Commusupport/public/CSS/form/form.css">
    <link rel="stylesheet" href="/Commusupport/public/CSS/button/button-styles.css">
    <link rel="stylesheet" href="/Commusupport/public/CSS/flashMessages.css">
    <link rel="stylesheet" href="/Commusupport/public/CSS/notification/notification.css">
    <!--    {styles}-->
</head>

<body>


    <div id="popUpBackground" class="popup-background">
        <div id="popUpContainer" class="popup"></div>
    </div>

    <div id="flash-messages" class="flash-message-div"></div>

    {navbar}

<script type="module">
    import flash from "/Commusupport/public/JS/flashmessages/flash.js";
    <?php if (!empty($_SESSION['flashMessages'])): ?>
        const flashMessages = <?php echo json_encode($_SESSION['flashMessages']); ?>;
        flash.showInit(flashMessages);
    <?php endif; ?>

    const notificationBtn = document.getElementById('notif-btn');

    if(notificationBtn){
        notificationBtn.addEventListener('click', () => {
            if(notification.style.display === 'none'){
                notification.style.display = 'block';
            }else{
                notification.style.display = 'none';
            }
        });
    }


    const cardContainers = document.querySelectorAll('.card-container');
    const contenConatainers = document.querySelectorAll('.content');
    const postedRequestCardContainers = document.querySelectorAll('.posted-rq-card-container');

    if(cardContainers) {

        for(let i=0; i < cardContainers.length; i++) {

            const noDataImg = document.createElement('img');
            noDataImg.src = '/Commusupport/public/src/errors/NoData.svg';

            if(cardContainers[i].children.length === 0) {
                cardContainers[i].appendChild(noDataImg);
                cardContainers[i].classList.remove('card-container');
                cardContainers[i].classList.add('no-data');
            }
        }
    }

    if(contenConatainers) {

        for(let i=0; i < contenConatainers.length; i++) {

            const noDataImg = document.createElement('img');
            noDataImg.src = '/Commusupport/public/src/errors/NoData.svg';

            if(contenConatainers[i].children.length === 0) {
                contenConatainers[i].appendChild(noDataImg);
                contenConatainers[i].classList.add('no-data');
            }
        }

    }

    if(postedRequestCardContainers) {

        for(let i=0; i < postedRequestCardContainers.length; i++) {

            const noDataImg = document.createElement('img');
            noDataImg.src = '/Commusupport/public/src/errors/NoData.svg';

            if(postedRequestCardContainers[i].children.length === 0) {
                postedRequestCardContainers[i].appendChild(noDataImg);
                postedRequestCardContainers[i].classList.add('no-data');
                postedRequestCardContainers[i].classList.remove('posted-rq-card-container');
                postedRequestCardContainers[i].classList.add('posted-rq-flag')
            }
        }

    }

</script>

</body>

</html>
