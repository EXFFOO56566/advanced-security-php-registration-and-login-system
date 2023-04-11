<?php 
include 'templates/header.php';

$userDetails = $user->getDetails();
?>

            <!-- Left nav
            ================================================== -->
            <div class="row">
               <div class="span3 bs-docs-sidebar">
                <ul class="nav nav-list bs-docs-sidenav">
                  <li>
                      <a href="index.php">
                          <i class="icon-home glyphicon glyphicon-home"></i>
                          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
                          <?php echo ASLang::get('home'); ?>
                      </a>
                  </li>
                  <li class="active">
                      <a href="profile.php">
                          <i class="icon-user glyphicon glyphicon-user"></i>
                          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
                          <?php echo ASLang::get('my_profile'); ?>
                      </a>
                  </li>
                  <?php if($user->isAdmin()): ?>
                  <li>
                      <a href="users.php">
                          <i class="icon-fire glyphicon glyphicon-fire"></i>
                          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
                          <?php echo ASLang::get('users'); ?>
                      </a>
                  </li>
                  <li>
                      <a href="user_roles.php">
                          <i class="icon-fire glyphicon glyphicon-fire"></i>
                          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
                          <?php echo ASLang::get('user_roles'); ?>
                      </a>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>
              <div class="span9 profile-details-wrapper">
                    <!-- main content here -->

                    <?php if ( ! $user->isAdmin() ): ?>
                        <div class="alert alert-warning" style="margin-top: 30px;">
                            <strong><?php echo ASLang::get('note'); ?>! </strong>
                            <?php echo ASLang::get('to_change_email_username'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form class="form-horizontal no-submit" id="form-changepassword">
                        <fieldset>
                        
                        <!-- Form Name -->
                        <legend><?php echo ASLang::get('change_password'); ?></legend>
                        
                        <!-- Password input-->
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="old_password">
                            <?php echo ASLang::get('old_password'); ?>
                          </label>
                          <div class="controls col-lg-8">
                            <input id="old_password" name="old_password" type="password" placeholder="" class="input-xlarge form-control" >
                            
                          </div>
                        </div>
                        
                        <!-- Password input--> 
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="new_password">
                            <?php echo ASLang::get('new_password'); ?>
                          </label>
                          <div class="controls col-lg-8">
                            <input id="new_password" name="new_password" type="password" placeholder="" class="input-xlarge form-control" >
                            
                          </div>
                        </div>
                        
                        <!-- Password input-->
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="new_password_confirm">
                            <?php echo ASLang::get('confirm_new_password'); ?>
                          </label>
                          <div class="controls col-lg-8">
                            <input id="new_password_confirm" name="new_password_confirm" type="password" placeholder=""  class="input-xlarge form-control">
                            
                          </div>
                        </div>
                        
                        <!-- Button -->
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="change_password"></label>
                          <div class="controls col-lg-8">
                            <button id="change_password" name="change_password" class="btn btn-primary">
                              <?php echo ASLang::get('update'); ?>
                            </button>
                          </div>
                        </div>
                        
                        </fieldset>
                    </form>
                    
                    
                    <form class="form-horizontal no-submit" id="form-details">
                        <fieldset>
                        
                        <!-- Form Name -->
                        <legend><?php echo ASLang::get('your_details'); ?></legend>
                        
                        <!-- Text input-->
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="first_name">
                            <?php echo ASLang::get('first_name'); ?>
                          </label>
                          <div class="controls col-lg-8">
                            <input id="first_name" name="first_name" type="text" value="<?php echo htmlentities($userDetails['first_name']); ?>" class="input-xlarge form-control">
                            
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="last_name">
                            <?php echo ASLang::get('last_name'); ?>
                          </label>
                          <div class="controls col-lg-8">
                            <input id="last_name" name="last_name" type="text" value="<?php echo htmlentities($userDetails['last_name']); ?>" class="input-xlarge form-control">
                            
                          </div>
                        </div>
                        
                       <!-- Text input-->
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="address">
                            <?php echo ASLang::get('address'); ?>
                          </label>
                          <div class="controls col-lg-8">
                            <input id="address" name="address" type="text" value="<?php echo htmlentities($userDetails['address']); ?>" class="input-xlarge form-control">
                            
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="phone">
                            <?php echo ASLang::get('phone'); ?>
                          </label>
                          <div class="controls col-lg-8">
                            <input id="phone" name="phone" type="text" value="<?php echo htmlentities($userDetails['phone']); ?>" class="input-xlarge form-control">
                            
                          </div>
                        </div>
                        
                        <!-- Button -->
                        <div class="control-group form-group">
                          <label class="control-label col-lg-4" for="update_details"></label>
                          <div class="controls col-lg-8">
                            <button id="update_details" name="update_details" class="btn btn-primary">
                              <?php echo ASLang::get('update'); ?>
                            </button>
                          </div>
                        </div>
                        
                        </fieldset>
                    </form>


              </div>
            </div>

    <?php include 'templates/footer.php'; ?>
    
    <script src="assets/js/sha512.js" type="text/javascript" charset="utf-8"></script>
    <script src="ASLibrary/js/asengine.js" type="text/javascript" charset="utf-8"></script>
    <script src="ASLibrary/js/index.js" type="text/javascript" charset="utf-8"></script>
    <script src="ASLibrary/js/profile.js" type="text/javascript" charset="utf-8"></script>
    
  </body>
</html>
