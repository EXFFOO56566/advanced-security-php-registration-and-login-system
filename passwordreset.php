<?php 
include "ASEngine/AS.php"; 
if(!isset($_GET['k']))
    header("Location: login.php");
?>
<!doctype html>
<html lang="en"> 
    <head>
        <meta charset="UTF-8">
        <title><?php echo ASLang::get('password_reset'); ?> | Advanced Security</title>
        <script type="text/javascript" src="assets/js/jquery.min.js"></script>
        <?php if(BOOTSTRAP_VERSION == 2): ?>
            <link rel='stylesheet' href='assets/css/bootstrap.min2.css' type='text/css' media='all' />
            <script type="text/javascript" src="assets/js/bootstrap.min2.js"></script>
            <link rel='stylesheet' href='ASLibrary/css/style2.css' type='text/css' media='all' />
        <?php else: ?>
            <link rel='stylesheet' href='assets/css/bootstrap.min3.css' type='text/css' media='all' />
            <script type="text/javascript" src="assets/js/bootstrap.min3.js"></script>
            <link rel='stylesheet' href='ASLibrary/css/style3.css' type='text/css' media='all' />
        <?php endif; ?>
        <link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet">
        <script type="text/javascript" charset="utf-8">
            var $_lang = <?php echo ASLang::all(); ?>;
        </script>
    </head>
    <body>
        <div class="container">
            <div class="modal" id="password-reset-modal">
                <div class="modal-dialog" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><?php echo WEBSITE_NAME; ?></h3>
                        </div>
                        <div class="modal-body">
                            <div class="well">
                                <?php $validator = new ASValidator(); ?>
                                <?php if ( $validator->prKeyValid($_GET['k']) ): ?>
                                    <form class="form-horizontal" id="password-reset-form">
                                        <fieldset>
                                            <div id="legend">
                                                <legend class=""><?php echo ASLang::get('password_reset'); ?></legend>
                                            </div>
                                            <div class="control-group form-group">
                                                <!-- Username -->
                                                <label class="control-label col-lg-4"  for="login-username">
                                                    <?php echo ASLang::get('new_password'); ?>
                                                </label>
                                                <div class="controls col-lg-8">
                                                    <input type="password" id="password-reset-new-password"  class="input-xlarge form-control" />
                                                </div>
                                            </div>

                                            <div class="control-group form-group">
                                                <!-- Button -->
                                                <div class="controls col-lg-offset-4 col-lg-8">
                                                    <button id="btn-reset-pass" class="btn btn-success">
                                                        <?php echo ASLang::get('reset_password'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                <?php else: ?>
                                    <?php echo "<h5 class='text-error' style='text-align: center;'>". ASLang::get('invalid_password_reset_key') ."</h5>"; ?>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <script type="text/javascript" src="assets/js/sha512.js"></script>
            <script type="text/javascript" src="ASLibrary/js/asengine.js"></script>
            <script type="text/javascript" src="ASLibrary/js/passwordreset.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                   $("#password-reset-modal").modal({
                       keyboard: false,
                       backdrop: "static"
                   }); 
                });
            </script>
    </body>
</html>