<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/registration/reg-base.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $user \app\models\userModel */
/** @var $donee \app\models\doneeModel */
/** @var $doneeIndividual \app\models\doneeIndividualModel */
/** @var $doneeOrganization \app\models\doneeOrganizationModel */

$CHOs = \app\models\choModel::getCHOs();


?>

<div class="background">

    <div class="reg-form-container">

    <?php $doneeForm = \app\core\components\form\form::begin('','post') ?>

    <?php
    $indOrOrg = new \app\core\components\layout\headerDiv();

    $indOrOrg->heading('Donee Sign Up');

    $indOrOrg->pages(['individuals', 'organizations']);

    $indOrOrg->end(); ?>

        <div class="login-grid-2">

         <div>

             <div style="display: none">
                 <?php $doneeForm->inputField($donee, 'Donor Type','text','type','doneeType'); ?>
             </div>

             <?php $doneeForm->dropDownList($donee,'Choose District','district',$CHOs,'district'); ?>

             <?php foreach ($CHOs as $key => $value) : ?>
                 <div id="<?php echo $key ?>" class="form-group" style="display: none">
                     <?php $doneeForm->dropDownList($donee,'Choose City','ccID',\app\models\choModel::getCCsUnderCHO($key)); ?>
                 </div>
             <?php endforeach; ?>

             <?php $doneeForm->inputField($donee, 'Email','email','email'); ?>

             <?php $doneeForm->inputField($donee, 'Address','text','address'); ?>

             <?php $doneeForm->inputField($donee, 'Contact Number','text','contactNumber'); ?>

             <?php $doneeForm->inputField($user, 'Username','text','username'); ?>

             <?php $doneeForm->inputField($user, 'Password','password','password'); ?>

             <?php $doneeForm->inputField($user, 'Confirm Password','password','confirmPassword'); ?>

         </div>

        <div>

            <div id="individualForm" >
                <?php $doneeForm->inputField($doneeIndividual, 'First Name','text','fname'); ?>

                <?php $doneeForm->inputField($doneeIndividual, 'Last Name','text','lname'); ?>

                <?php $doneeForm->inputField($doneeIndividual, 'Age','number','age'); ?>

                <?php $doneeForm->inputField($doneeIndividual, 'NIC','text','nic'); ?>

                <?php $doneeForm->fileInput($donee,'Upload your NIC Front','nicFront'); ?>

                <?php $doneeForm->fileInput($donee,'Upload your NIC Back','nicBack'); ?>
            </div>

            <div id="organizationForm" style="display: none">
                <?php $doneeForm->inputField($doneeOrganization, 'Organization Name','text','organizationName'); ?>

                <?php $doneeForm->inputField($doneeOrganization, 'Registration Number','text','regNo'); ?>

                <?php $doneeForm->fileInput($donee,'Upload your registration certificate front','certificateFront'); ?>

                <?php $doneeForm->fileInput($donee,'Upload your NIC certificate back','certificateBack'); ?>

                <?php $doneeForm->inputField($doneeOrganization, 'Representative Name','text','representative'); ?>

                <?php $doneeForm->inputField($doneeOrganization, 'Representative Contact','text','representativeContact'); ?>

                <?php $doneeForm->inputField($doneeOrganization, 'How many dependents are present? (If only applicable)','number','capacity'); ?>

            </div>

                <div class="form-group" style="align-self: center">
                    <label for="setLocation">Location</label>
                    <button id="setLocation" class="btn btn-primary" type="button">Mark your location</button>
                    <input type="hidden" id="lat" name="latitude" value="0">
                    <input type="hidden" id="lng" name="longitude" value="0">
                </div>

        </div>

<?php $doneeForm->button('Register'); ?>

<?php $doneeForm->end() ?>

    </div>

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


    <script type="module" src="../public/JS/guest/register/donee.js" defer></script>
    <script type="module">
        import MapMarker from "../public/JS/map/map-marker.js";
        function initMap()  {
            MapMarker.initMap();
        }
        window.initMap = initMap;
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAl1ekIlhUhjhwMjrCqiZ5-fOWaxRIAKos&callback=initMap" async defer></script>


    <script>
        document.getElementById('donorType').value = 'Individual';
    </script>

<?php if(!empty($_POST['type'])) : ?>
    <script>
        <?php if($_POST['type'] == 'Individual') : ?>
        document.getElementById('doneeType').value = 'Individual';
        document.getElementById('organizationForm').style.display = 'none';
        document.getElementById('individualForm').style.display = 'block';
        <?php else : ?>
        document.getElementById('doneeType').value = 'Organization';
        document.getElementById('individualForm').style.display = 'none';
        document.getElementById('organizationForm').style.display = 'block';
        <?php endif; ?>
    </script>
<?php endif?>

<?php if(!empty($_POST['district'])) : ?>
    <script>
        document.getElementById('district').value = '<?php echo $_POST['district'] ?>';
        document.getElementById('<?php echo $_POST['district'] ?>').style.display = 'block';
    </script>
<?php endif?>
