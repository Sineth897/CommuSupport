<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../../public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="../../public/CSS/flashMessages.css">
<?php
/** @var $cc \app\models\ccModel */

?>


<!--        Profile Details-->
<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Register a Community Center");

$headerDiv->end();
?>

<div class="content-form">

<?php $ccRegistrationForm = \app\core\components\form\form::begin('','post') ?>
<div class="form-box">

    <?php $ccRegistrationForm->formHeader("Community Center Details") ?>

    <div >
        <?php $ccRegistrationForm->inputField($cc,"Address",'text','address') ?>

        <?php $ccRegistrationForm->inputField($cc,"ContactNumber",'text','contactNumber')?>

        <?php $ccRegistrationForm->inputField($cc,"Email",'email','email') ?>

        <?php $ccRegistrationForm->inputField($cc,"City",'text','city','city') ?>

        <div>
            <p>  Mark the location on the map</p>
            <button class="btn-primary" type="button" id="setLocation">Set location</button>
            <input type="hidden" id="lat" name="latitude" value="0">
            <input type="hidden" id="lng" name="longitude" value="0">
        </div>


        <?php $ccRegistrationForm->inputField($cc,"Fax",'text','fax')?>

    </div>

    <div style="padding: 2rem;display:flex;justify-content: center">
        <?php $ccRegistrationForm->button("Confirm",'submit','confirm') ?>
    </div>

</div>



<?php $ccRegistrationForm->end() ?>
</div>


<div class="popup-background" id="mapDiv">
    <div id="mapContainer" class="popup popup-map">
        <p class="popup-description"> Please drag the marker to your location on the map to set your location</p>
        <div id="map" class="map-styles" ></div>
        <button id="confirmLocation" class="btn btn-cta-primary" type="button">Confirm Location</button>
    </div>
</div>

<?php if(isset($_POST['latitude']) && isset($_POST['longitude'])) : ?>
    <script>
        document.getElementById('lat').value = '<?php echo $_POST['latitude'] ?>';
        document.getElementById('lng').value = '<?php echo $_POST['longitude'] ?>';
    </script>
<?php endif?>

<script type="module" src="../../public/JS/cho/cc/register.js"></script>
<script type="module">
    import MapMarker from "../../public/JS/map/map-marker.js";
    function initMap()  {
        MapMarker.initMap();
    }
    window.initMap = initMap;
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv492o7hlT-nKoy2WGWmnceYZLSw2UDWw&callback=initMap" async defer></script>

