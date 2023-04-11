<?php
include "ASEngine/AS.php";
if(!isset($_GET['k']))
    header("Location: login.php");
?>
<!doctype html>
<html lang="en"> 
    <head>
        <meta charset="UTF-8">
        <title><?php echo ASLang::get('email_confirmation'); ?> | Advanced Security</title>
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
            <div class="modal" id="confirm-modal">
                <div class="modal-dialog" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3><?php echo WEBSITE_NAME; ?></h3>
                        </div>
                        <div class="modal-body">
                            <div class="well">
                                <?php
                                $key = $_GET['k'];
                                $result = $db->select(
                                        "SELECT * FROM `as_users`
                                         WHERE `confirmation_key` = :k", array("k" => $key)
                                );
                                if (count($result) == 1) {
                                    $db->update(
                                            'as_users', array("confirmed" => "Y"), "`confirmation_key` = :k", array("k" => $key)
                                    );

                                    echo "<h4 class='text-success'>". ASLang::get('email_confirmed') .".</h4>";
                                    echo "<h5 class='text-success'>". ASLang::get('you_can_login_now', array( 'link' => 'login.php') ) ."</h5>";
                                }
                                else
                                    echo "<h5 class='text-error'>". ASLang::get('user_with_key_doesnt_exist') ."</h5>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <script type="text/javascript">
                $(document).ready(function () {
                   $("#confirm-modal").modal({
                       keyboard: false,
                       backdrop: "static"
                   }); 
                });
            </script>
    </body>
</html>