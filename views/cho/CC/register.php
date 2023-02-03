<?php
<<<<<<< HEAD

/** @var $cc \app\models\ccModel */
/** @var $user \app\models\userModel */

?>

<?php
echo $_SESSION['user'];
echo '<pre>';
if(empty(\app\core\Application::session()->getFlash('success'))) {
    print_r(\app\core\Application::session()->getFlash('error'));
} else if(empty(\app\core\Application::session()->getFlash('error'))) {
=======
/** @var $cc \app\models\ccModel */
/** @var $user \app\models\userModel */
?>
<?php
echo $_SESSION=['user'];
echo "<pre>";
if(empty(\app\core\Application::session()->getFlash('success'))){
    print_r(\app\core\Application::session()->getFlash('error'));
}else if (empty(\app\core\Application::session()->getFlash('error'))){
>>>>>>> eff85e44986d3bc01499410308913423699b159b
    print_r(\app\core\Application::session()->getFlash('success'));
}
echo '</pre>';
?>

<?php $ccRegistrationForm = \app\core\components\form\form::begin('','post') ?>

<?php $ccRegistrationForm->inputField($cc,"Address",'text','address') ?>

<?php $ccRegistrationForm->inputField($cc,"City",'text','city') ?>

<?php $ccRegistrationForm->inputField($cc,"Email",'email','email') ?>

<<<<<<< HEAD
<?php $ccRegistrationForm->inputField($cc,"Fax",'text','fax')?>

<?php $ccRegistrationForm->inputField($cc,"ContactNumber",'text','contactNumber')?>

<?php $ccRegistrationForm->inputField($user, "username",'text','username') ?>

<?php $ccRegistrationForm->inputField($user, "Password",'password','password') ?>
=======
<?php $ccRegistrationForm->inputField($cc,"Fax",'text','fax') ?>

<?php $ccRegistrationForm->inputField($cc, "Contact Number",'text','contactNumber') ?>
>>>>>>> eff85e44986d3bc01499410308913423699b159b

<?php $ccRegistrationForm->button("Confirm") ?>

<?php $ccRegistrationForm->end() ?>
<<<<<<< HEAD

=======
>>>>>>> eff85e44986d3bc01499410308913423699b159b
