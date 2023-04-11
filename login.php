<?php 
include "ASEngine/AS.php"; 
if($login->isLoggedIn())
    header("Location: index.php");

$token = $register->socialToken();
ASSession::set('as_social_token', $token);
$register->botProtection();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Advanced Security - PHP MySQL Register/Login System">
        <meta name="author" content="Milos Stojanovic">
        <title>Login | Advanced Security</title>
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
        <script type="text/javascript" src="assets/js/respond.min.js"></script>
        <script type="text/javascript">
            var SUCCESS_LOGIN_REDIRECT = "<?php echo SUCCESS_LOGIN_REDIRECT; ?>";
            var $_lang = <?php echo ASLang::all(); ?>;
        </script>
        
    </head>
    <body>
        <div class="container">
         <div class="flags-wrapper">
             <a href="?lang=en">
                 <img src="assets/img/en.png" alt="English"
                      class="<?php echo ASLang::getLanguage() != 'en' ? 'fade' : ''; ?>" />
             </a>
             <a href="?lang=rs">
                 <img src="assets/img/rs.png" alt="Serbian"
                      class="<?php echo ASLang::getLanguage() != 'rs' ? 'fade' : ''; ?>" />
             </a>
         </div>

         <div class="modal" id="loginModal">
             <div class="modal-dialog" >
                 <div class="modal-content">
          <div class="modal-header">
            <h3><?php echo WEBSITE_NAME; ?></h3>
          </div>
          <div class="modal-body">
            <div class="well">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#login" data-toggle="tab"><?php echo ASLang::get('login'); ?></a></li>
                <li><a href="#create" data-toggle="tab"><?php echo ASLang::get('create_account'); ?></a></li>
                <li><a href="#forgot" data-toggle="tab"><?php echo ASLang::get('forgot_password'); ?></a></li>
              </ul>
              <div id="myTabContent" class="tab-content">
                <div class="tab-pane active in" id="login">
                  <form class="form-horizontal">
                    <fieldset>
                      <div id="legend">
                        <legend class=""><?php echo ASLang::get('login'); ?></legend>
                      </div>    
                      <div class="control-group form-group">
                        <!-- Username -->
                        <label class="control-label col-lg-4"  for="login-username"><?php echo ASLang::get('username'); ?></label>
                        <div class="controls col-lg-8">
                          <input type="text" id="login-username" name="username" placeholder="" class="input-xlarge form-control"> <br />
                        </div>
                      </div>

                      <div class="control-group form-group">
                        <!-- Password-->
                        <label class="control-label col-lg-4" for="login-password"><?php echo ASLang::get('password'); ?></label>
                        <div class="controls col-lg-8">
                          <input type="password" id="login-password" name="password" placeholder="" class="input-xlarge form-control">
                        </div>
                      </div>
 
 
                      <div class="control-group form-group">
                        <!-- Button -->
                        <div class="controls col-lg-offset-4 col-lg-8">
                          <button id="btn-login" class="btn btn-success"><?php echo ASLang::get('login'); ?></button>
                        </div>
                      </div>
                    </fieldset>
                  </form>                
                </div>
                <div class="tab-pane fade" id="create">
                  <form class="form-horizontal register-form" id="tab">
                      <fieldset>
                        <div id="legend">
                          <legend class=""><?php echo ASLang::get('create_account'); ?></legend>
                        </div>

                        <div class="control-group  form-group">
                            <label class="control-label col-lg-4" for='reg-email' ><?php echo ASLang::get('email'); ?> <span class="required">*</span></label>
                            <div class="controls col-lg-8">
                                <input type="text" id="reg-email" class="input-xlarge form-control">
                            </div>
                        </div>

                        <div class="control-group  form-group">
                            <label class="control-label col-lg-4" for="reg-username"><?php echo ASLang::get('username'); ?> <span class="required">*</span></label>
                            <div class="controls col-lg-8">
                                <input type="text" id="reg-username" class="input-xlarge form-control">
                            </div>
                        </div>

                        <div class="control-group  form-group">
                            <label class="control-label col-lg-4" for="reg-password"><?php echo ASLang::get('password'); ?> <span class="required">*</span></label>
                            <div class="controls col-lg-8">
                                <input type="password" id="reg-password" class="input-xlarge form-control">
                            </div>
                        </div>

                        <div class="control-group  form-group">
                            <label class="control-label col-lg-4" for="reg-repeat-password"><?php echo ASLang::get('repeat_password'); ?> <span class="required">*</span></label>
                            <div class="controls col-lg-8">
                                <input type="password" id="reg-repeat-password" class="input-xlarge form-control">
                            </div>
                        </div>
                          
                        <div class="control-group  form-group">
                            <label class="control-label col-lg-4" for="reg-bot-sum">
                                <?php echo ASSession::get("bot_first_number"); ?> + 
                                <?php echo ASSession::get("bot_second_number"); ?>
                                <span class="required">*</span>
                            </label>
                            <div class="controls col-lg-8">
                                <input type="text" id="reg-bot-sum" class="input-xlarge form-control">
                            </div>
                        </div>

                        <div class="control-group  form-group">
                            <div class="controls col-lg-offset-4 col-lg-8">
                                <button id="btn-register" class="btn btn-success"><?php echo ASLang::get('create_account'); ?></button>
                            </div>
                        </div>
                       </fieldset>
                  </form>
                </div>
                
                  <div class="tab-pane in" id="forgot">
                        <form class="form-horizontal" id="forgot-pass-form">
                        <fieldset>
                          <div id="legend">
                            <legend class=""><?php echo ASLang::get('forgot_password'); ?></legend>
                          </div>    
                          <div class="control-group form-group">
                            <!-- Username -->
                            <label class="control-label col-lg-4"  for="forgot-password-email"><?php echo ASLang::get('your_email'); ?></label>
                            <div class="controls col-lg-8">
                              <input type="email" id="forgot-password-email" class="input-xlarge form-control">
                            </div>
                          </div>

                          <div class="control-group form-group">
                            <!-- Button -->
                            <div class="controls col-lg-offset-4 col-lg-8">
                              <button id="btn-forgot-password" class="btn btn-success"><?php echo ASLang::get('reset_password'); ?></button>
                            </div>
                          </div>
                        </fieldset>
                      </form>
                        
                  </div>

                  <div class="social-login">
                      <?php if ( TWITTER_ENABLED ): ?>
                          <a href="socialauth.php?p=twitter&token=<?php echo $token; ?>">
                              <img src="assets/img/twitter.png" class="fade high-opacity" alt="Twitter" title="<?php echo ASLang::get('login_with'); ?> Twitter"/>
                          </a>
                      <?php endif; ?>
                      <?php if ( FACEBOOK_ENABLED ): ?>
                          <a href="socialauth.php?p=facebook&token=<?php echo $token; ?>">
                              <img src="assets/img/fb.png" class="fade high-opacity" alt="Facebook" title="<?php echo ASLang::get('login_with'); ?> Facebook"/>
                          </a>
                      <?php endif; ?>
                      <?php if ( GOOGLE_ENABLED ): ?>
                          <a href="socialauth.php?p=google&token=<?php echo $token; ?>">
                              <img src="assets/img/gplus.png" class="fade high-opacity" alt="Google+" title="<?php echo ASLang::get('login_with'); ?> GooglePlus"/>
                          </a>
                      <?php endif; ?>
                  </div>
            </div>
          </div>
        </div>
                 </div>
             </div>
        </div>
            <script type="text/javascript" src="assets/js/sha512.js"></script>
            <script type="text/javascript" src="ASLibrary/js/asengine.js"></script>
            <script type="text/javascript" src="ASLibrary/js/register.js"></script>
            <script type="text/javascript" src="ASLibrary/js/login.js"></script>
            <script type="text/javascript" src="ASLibrary/js/passwordreset.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                   $("#loginModal").modal({
                       keyboard: false,
                       backdrop: "static"
                   }); 
                });
            </script>
    </body>
</html>