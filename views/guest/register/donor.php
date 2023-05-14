<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/registration/reg-base.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/**
 * @var $user \app\models\userModel
 * @var $donor \app\models\donorModel
 * @var $donorIndividual \app\models\donorIndividualModel
 * @var $donorOrganization \app\models\donorOrganizationModel
 */

$CHOs = \app\models\choModel::getCHOs();

print_r($donorIndividual->errors);

?>

<div class="background">

    <div class="reg-form-container">

        <?php $donorForm = \app\core\components\form\form::begin('', 'post') ?>

        <?php $indOrOrg = new \app\core\components\layout\headerDiv();

        $indOrOrg->heading('Sign Up as Donor');

        $indOrOrg->pages(['individuals', 'organizations']);

        $indOrOrg->end(); ?>
        <div class="form-sections">
            <h2>Personal Information</h2>
            <p>Provide accurate and relevant information using standard formatting.</p>
        </div>
        <div class="login-grid-2">

            <div class="left-block">

                <div style="display: none">
                    <?php $donorForm->inputField($donor, 'Donor Type', 'text', 'type', 'donorType'); ?>
                </div>

                <?php $donorForm->inputFieldwithPlaceholder($donorIndividual, 'First Name', 'text', 'fname', 'Nimesh', 'firstname-input'); ?>

                <div
                    id="organization-name-block" style="display: none">   <?php $donorForm->inputFieldwithPlaceholder($donorOrganization, 'Organization Name', 'text', 'organizationName', 'Ex : Leo Club Colombo', 'organizationName'); ?>
                </div>
                <?php $donorForm->inputFieldwithPlaceholder($donor, 'Email', 'email', 'email', 'Ex : donor@cms.com'); ?>

                <?php $donorForm->inputFieldwithPlaceholder($donor, 'Address', 'text', 'address', 'Ex : 12/3, Nuwara Beach, Ampara'); ?>

                <?php $donorForm->inputFieldwithPlaceholder($donor, 'Contact Number', 'text', 'contactNumber', 'No Spaces, 10 Numbers - Ex : 0771234567'); ?>

            </div>

            <div>
                <div id="individualForm">
                    <!--                        --><?php //$donorForm->inputField($donorIndividual, 'First Name','text','fname'); ?>

                    <?php $donorForm->inputFieldwithPlaceholder($donorIndividual, 'Last Name', 'text', 'lname', 'Ex : Fernando'); ?>

                    <?php $donorForm->inputFieldwithPlaceholder($donorIndividual, 'Age', 'number', 'age', '18'); ?>

                    <?php $donorForm->inputFieldwithPlaceholder($donorIndividual, 'NIC', 'text', 'NIC', '10 numbers - Ex : 1000897867'); ?>
                </div>

                <div id="organizationForm" style="display: none">
                    <!--                        --><?php //$donorForm->inputField($donorOrganization, 'Organization Name','text','organizationName'); ?>

                    <?php $donorForm->inputFieldwithPlaceholder($donorOrganization, 'Registration Number', 'text', 'regNo', 'Ex : WC99999 - 2 Letters and 5 Numbers'); ?>

                    <?php $donorForm->inputFieldwithPlaceholder($donorOrganization, 'Representative Name', 'text', 'representative', 'Ex : Leo Sarath Perera'); ?>

                    <?php $donorForm->inputFieldwithPlaceholder($donorOrganization, 'Representative Contact', 'text', 'representativeContact', 'No Spaces, 10 Numbers - Ex : 0771234567'); ?>

                </div>


            </div>
        </div>
        <div class="form-sections">
            <h2>Location Marking</h2>
            <ul>
                <li>Mark location accurately on the map.</li>
                <li style="color: red">Refresh the page if the map is not visible in the pop up</li>
            </ul>
        </div>
        <div class="login-grid-2">
            <?php $donorForm->dropDownList($donor, 'Choose District', 'district', $CHOs, 'district'); ?>

            <?php foreach ($CHOs as $key => $value) : ?>
                <div id="<?php echo $key ?>" class="form-group" style="display: none">
                    <?php $donorForm->dropDownList($donor, 'Choose City', 'ccID', \app\models\choModel::getCCsUnderCHO($key)); ?>
                </div>
            <?php endforeach; ?>
            <div id="city-placeholder"></div>

            <div class="form-group" style="align-self: center">
                <label class="form-label">Location</label>
                <button id="setLocation" class="btn btn-primary" type="button">Open Map</button>
                <input type="hidden" id="lat" name="latitude" value="0">
                <input type="hidden" id="lng" name="longitude" value="0">
            </div>
        </div>

        <div class="form-sections">
            <h2>User Account Creation</h2>
            <p>Create a unique and memorable Username and a strong password. This will be used to log you in.</p>
            <ul>
                <li>Password must be longer than 8 characters</li>
                <li>Password must contain a Uppercase letter</li>
                <li>Password must contain a Lowercase letter</li>
                <li>Password must contain a Number</li>
                <li>Password must contain a Special Character</li>
            </ul>
        </div>
        <div class="login-grid-2">

            <?php $donorForm->inputField($user, 'Username', 'text', 'username'); ?>
            <div></div>
            <?php $donorForm->inputField($user, 'Password', 'password', 'password'); ?>
            <div></div>
            <?php $donorForm->inputField($user, 'Confirm Password', 'password', 'confirmPassword'); ?>


        </div>

        <div class="register-button-container">
            <?php $donorForm->button('Accept Terms and Services & Register'); ?>

            <?php $donorForm->end() ?>
            <a href="/CommuSupport/" class="btn-danger">Cancel Registration</a>
        </div>
    </div>

</div>

<div class="popup-background" id="mapDiv">
    <div id="mapContainer" class="popup popup-map">
        <p class="popup-description"> Please drag the marker to your location on the map to set your location</p>
        <div id="map" class="map-styles"></div>
        <button id="confirmLocation" class="btn btn-cta-primary" type="button">Confirm Location</button>
    </div>
</div>

<?php if (isset($_POST['latitude']) && isset($_POST['longitude'])) : ?>
    <script>
        document.getElementById('lat').value = '<?php echo $_POST['latitude'] ?>';
        document.getElementById('lng').value = '<?php echo $_POST['longitude'] ?>';
    </script>
<?php endif ?>

<script type="module" src="../public/JS/guest/register/donor.js" defer></script>

<script type="module">
    import MapMarker from "../public/JS/map/map-marker.js";

    function initMap() {
        MapMarker.initMap();
    }

    window.initMap = initMap;
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv492o7hlT-nKoy2WGWmnceYZLSw2UDWw&callback=initMap" async
        defer></script>

<script>
    document.getElementById('donorType').value = 'Individual';
</script>

<?php if (isset($_POST['type'])) : ?>
    <script>
        <?php if($_POST['type'] == 'Individual') : ?>
        document.getElementById('donorType').value = 'Individual';
        document.getElementById('organizationForm').style.display = 'none';
        document.getElementById('individualForm').style.display = 'block';
        <?php else : ?>
        document.getElementById('donorType').value = 'Organization';
        document.getElementById('individualForm').style.display = 'none';
        document.getElementById('organizationForm').style.display = 'block';
        <?php endif; ?>
    </script>
<?php endif ?>

<?php if (isset($_POST['district'])) : ?>
    <script>
        document.getElementById('district').value = '<?php echo $_POST['district'] ?>';
    </script>
<?php endif ?>


