<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/registration/reg-base.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $user \app\models\userModel */
/** @var $donee \app\models\doneeModel */
/** @var $doneeIndividual \app\models\doneeIndividualModel */
/** @var $doneeOrganization \app\models\doneeOrganizationModel */

$CHOs = \app\models\choModel::getCHOs();
//
//echo '<pre>';
//var_dump($donee->errors);
//echo '</pre>';

?>

<div class="background">

    <div class="reg-form-container">

        <?php $doneeForm = \app\core\components\form\form::begin('', 'post') ?>

        <?php
        $indOrOrg = new \app\core\components\layout\headerDiv();

        $indOrOrg->heading('Sign up as a Donee');
        ?>
        <?php

        $indOrOrg->pages(['individuals', 'organizations']);

        $indOrOrg->end(); ?>
        <div class="form-sections">
            <h2>Personal Information</h2>
            <p>Provide accurate and relevant information using standard formatting.</p>
        </div>
        <div class="login-grid-2">

            <div class="left-block">
                <!-- remove the-->
                <div style="display: none">
                    <?php $doneeForm->inputField($donee, 'Donee Type', 'text', 'type', 'doneeType'); ?>
                </div>

                <?php $doneeForm->inputFieldwithPlaceholder($doneeIndividual, 'First Name', 'text', 'fname', 'Ex : Nimal', 'firstname-input'); ?>

                <div id="#organization-name-block" style="display: none">
                    <?php $doneeForm->inputFieldwithPlaceholder($doneeOrganization, 'Organization Name', 'text', 'organizationName', 'Ex : Araliya Children\'s Home', 'organizationName'); ?>
                </div>

                <?php $doneeForm->inputFieldwithPlaceholder($donee, 'Email', 'email', 'email', 'Ex : example@cms.com'); ?>

                <?php $doneeForm->inputFieldwithPlaceholder($donee, 'Address', 'text', 'address', 'Ex : 12/3, Colombo Road, Colombo 07'); ?>

                <?php $doneeForm->inputFieldwithPlaceholder($donee, 'Contact Number', 'text', 'contactNumber', 'No Spaces, 10 Numbers - Ex : 0771234567'); ?>

            </div>

            <div class="right-block">

                <div id="individualForm">

                    <?php $doneeForm->inputFieldwithPlaceholder($doneeIndividual, 'Last Name', 'text', 'lname', 'Ex : Kumara'); ?>

                    <?php $doneeForm->inputField($doneeIndividual, 'Age', 'number', 'age'); ?>

                    <?php $doneeForm->inputFieldwithPlaceholder($doneeIndividual, 'NIC', 'text', 'NIC', '10 numbers - Ex : 1000897867'); ?>

                    <!--                    --><?php //$doneeForm->fileInput($donee, 'Upload your NIC Front', 'nicFront'); ?>
                    <!---->
                    <!--                    --><?php //$doneeForm->fileInput($donee, 'Upload your NIC Back', 'nicBack'); ?>
                </div>

                <div id="organizationForm" style="display: none">

                    <?php $doneeForm->inputFieldwithPlaceholder($doneeOrganization, 'Representative Name', 'text', 'representative', 'Ex : Araliya Fernando'); ?>



                    <?php $doneeForm->inputFieldwithPlaceholder($doneeOrganization, 'Representative Contact', 'text', 'representativeContact', 'No Spaces, 10 Numbers - Ex : 0771234567077'); ?>

                    <?php $doneeForm->inputFieldwithPlaceholder($doneeOrganization, 'Registration Number', 'text', 'regNo', 'Ex : WC99999 - 2 Letters and 5 Numbers'); ?>
                    <!---->
                    <!--                    --><?php //$doneeForm->fileInput($donee, 'Upload your registration certificate front', 'certificateFront'); ?>
                    <!---->
                    <!--                    --><?php //$doneeForm->fileInput($donee, 'Upload your registration certificate back', 'certificateBack'); ?>


                    <?php $doneeForm->inputField($doneeOrganization, 'How many dependents are present? (If only applicable)', 'number', 'capacity'); ?>

                    <!---->
                    <!--                    --><?php //$doneeForm->fileInput($donee, 'Upload your registration certificate front', 'certificateFront'); ?>
                    <!---->
                    <!--                    --><?php //$doneeForm->fileInput($donee, 'Upload your registration certificate back', 'certificateBack'); ?>
                    <!---->
                    <!---->
                    <!--                    --><?php //$doneeForm->inputField($doneeOrganization, 'Representative Contact', 'text', 'representativeContact'); ?>
                    <!---->
                    <!--                    --><?php //$doneeForm->inputField($doneeOrganization, 'How many dependents are present? (If only applicable)', 'number', 'capacity'); ?>

                </div>


            </div>


        </div>
        <div class="form-sections">
            <h2>Document Upload Section</h2>
            <p>Upload clear photographs or scanned PDFs of both sides of the required document.<br></p>
        </div>
        <div class="login-grid-2">

            <div class="nic-div-org">
                <?php $doneeForm->fileInput($donee, 'Upload your NIC Front', 'nicFront'); ?>
            </div>
            <div class="nic-div-org">
                <?php $doneeForm->fileInput($donee, 'Upload your NIC Back', 'nicBack'); ?>
            </div>
            <div class="cert-div-org" style="display: none;">
                <?php $doneeForm->fileInput($donee, 'Upload your registration certificate front', 'certificateFront'); ?>
            </div>
            <!---->

            <div class="cert-div-org" style="display: none">
                <?php $doneeForm->fileInput($donee, 'Upload your registration certificate back', 'certificateBack'); ?>
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
            <?php $doneeForm->dropDownList($donee, 'Choose District', 'district', $CHOs, 'district'); ?>

            <?php foreach ($CHOs as $key => $value) : ?>
                <div id="<?php echo $key ?>" class="form-group" style="display: none">
                    <?php $doneeForm->dropDownList($donee, 'Choose City', 'ccID', \app\models\choModel::getCCsUnderCHO($key)); ?>
                </div>
            <?php endforeach; ?>
            <div id="city-placeholder"></div>
            <div class="form-group" style="align-self: center">
                <label class="form-label" for="setLocation">Location</label>
                <button id="setLocation" class="btn btn-cta-primary" type="button">Open Map</button>
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
            <?php $doneeForm->inputField($user, 'Username', 'text', 'username'); ?>
            <div></div>
            <?php $doneeForm->inputField($user, 'Password', 'password', 'password'); ?>
            <div></div>
            <?php $doneeForm->inputField($user, 'Confirm Password', 'password', 'confirmPassword'); ?>

        </div>
        <div class="register-button-container">
            <?php $doneeForm->button('Accept Terms and Services & Register'); ?>
            <a href="http://localhost/CommuSupport" class="btn-danger">Cancel Registration</a>
        </div>
    </div>

    <?php $doneeForm->end() ?>
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


    <script type="module" src="../public/JS/guest/register/donee.js" defer></script>
    <script type="module">
        import MapMarker from "../public/JS/map/map-marker.js";

        function initMap() {
            MapMarker.initMap();
        }

        window.initMap = initMap;
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv492o7hlT-nKoy2WGWmnceYZLSw2UDWw&callback=initMap"
            async defer></script>


    <script>
        document.getElementById('doneeType').value = 'Individual';
    </script>

    <?php if (!empty($_POST['type'])) : ?>
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
    <?php endif ?>

    <?php if (!empty($_POST['district'])) : ?>
        <script>
            document.getElementById('district').value = '<?php echo $_POST['district'] ?>';
            document.getElementById('<?php echo $_POST['district'] ?>').style.display = 'block';
        </script>
    <?php endif ?>
