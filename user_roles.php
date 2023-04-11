<?php 
include 'templates/header.php';

 if(!$user->isAdmin())
    header("Location: index.php");
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
                  <li>
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
                  <li class="active">
                      <a href="user_roles.php">
                          <i class="icon-fire glyphicon glyphicon-fire"></i>
                          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
                          <?php echo ASLang::get('user_roles'); ?>
                      </a>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>

              <div class="span9">
              		<div class="control-group roles-input">
              			<div class="controls col-lg-3">
          					  <input type="text" class="form-control col-lg-3" id='role-name' placeholder="<?php echo ASLang::get('role_name'); ?>">
          					</div>
                    <button type="submit" class="btn btn-success" onclick="roles.addRole();">
                      <?php echo ASLang::get('add'); ?>
                    </button>
          		</div>
				<?php $roles = $db->select("SELECT * FROM `as_user_roles` WHERE `role_id` NOT IN (1,2,3)"); ?>
              <table class="table table-striped roles-table">
                  <thead>
                      <th><?php echo ASLang::get('role_name'); ?></th>
                      <th><?php echo ASLang::get('users_with_role'); ?></th>
                      <th><?php echo ASLang::get('action'); ?></th>
                  </thead>
              <?php foreach ($roles as $role): ?>
                  <?php $result = $db->select("SELECT COUNT(*) AS num FROM `as_users` WHERE `user_role` = :r", array( "r" => $role['role_id'])); ?>
                  <?php $usersWithThisRole = $result[0]['num']; ?>
                  <tr class="role-row">
                  	<td><?php echo htmlentities($role['role']); ?></td>
                  	<td><?php echo htmlentities($usersWithThisRole); ?></td>
                  	<td>
                  		<button type="button" class="btn btn-danger btn-sm" onclick="roles.deleteRole(this,<?php echo $role['role_id']; ?>);">
                  			<i class="icon-trash glyphicon glyphicon-trash"></i>
                            <?php echo ASLang::get('delete'); ?>
                  		</button>
                  	</td>
                  	
                  </tr>
              <?php endforeach; ?>
              </table>
          </div>

        </div>
    
    <?php include 'templates/footer.php'; ?>

    <script type="text/javascript" src="ASLibrary/js/asengine.js"></script>
    <script type="text/javascript" src="ASLibrary/js/roles.js"></script>
    <script type="text/javascript" src="ASLibrary/js/index.js"></script>
   	</body>
 </html>