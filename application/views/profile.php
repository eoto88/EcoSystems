<form action="<?php echo URL::base(TRUE, TRUE) . 'login'; ?>" method="POST">
    <?php
    if ($error_messages) {
        echo '<ul id="profile-error-messages" class="error-messages">';
        foreach($create_account_messages as $key => $value) {
            echo '<li data-fieldnameerror="'. $key .'">'. $value .'</li>';
        }
        echo '</ul>';
    }
    echo $form;
    ?>
    <input type="hidden" name="action" value="create-account" />
    <div class="form-field">
        <label>Username:</label>
        <input type="text" name="username" />
    </div>
    <div class="form-field">
        <label>Name:</label>
        <input type="text" name="name" />
    </div>
    <div class="form-field">
        <label>Email:</label>
        <input type="text" name="email" />
    </div>
    <div class="form-field password-field">
        <label>Password:</label>
        <input type="password" id="create-account-password" name="password" />
        <div class="show-password-wrap">
            <input name="show-password" type="checkbox" id="show-create-account-password" class="chk-show-password" role="checkbox" aria-checked="false" value="1" style="-webkit-user-select: none;">
            <label for="show-password" title="Show Password">Show</label>
        </div>
    </div>
    <input type="submit" id="create-account-button" value="Create account" /> <input type="button" id="cancel-button" class="button" value="Cancel" />
</form>