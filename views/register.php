<h1>Register</h1>

<?php $form = \app\core\form\Form::begin('/register', 'post'); ?>

<?php echo $form->field($model, 'firstname')->textField(); ?>
<?php echo $form->field($model, 'lastname')->textField(); ?>
<?php echo $form->field($model, 'email')->emailField(); ?>
<?php echo $form->field($model, 'password')->passwordField(); ?>
<?php echo $form->field($model, 'confirmPassword')->passwordField(); ?>

<button type="submit">Register</button>

<?php \app\core\form\Form::end(); ?>