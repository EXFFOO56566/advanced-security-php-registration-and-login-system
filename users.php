<?php 
include 'templates/header.php';

 if(!$user->isAdmin())
    header("Location: index.php");
 ?>

<link rel="stylesheet" href="assets/css/dataTables.bootstrap.css"/>
    
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
                  <li class="active">
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
          <div class="span9 users-wrapper">
              <a class="btn btn-primary" href="javascript:void(0);" 
                  onclick="users.showAddUserModal()" > 
                  <i class="icon-user icon-white glyphicon glyphicon-user"></i>
                  <?php echo ASLang::get('add_user'); ?>
              </a>
              <?php //user_role 3 => admin ?>
              <?php $users = $db->select("SELECT * FROM `as_users` WHERE `user_role` != '3' ORDER BY `register_date` DESC"); ?>
              <table cellpadding="0" cellspacing="0" border="0" class="table table-striped users-table" id="users-list" width="100%">
                  <thead>
                  <th><?php echo ASLang::get('username'); ?></th>
                  <th><?php echo ASLang::get('email'); ?></th>
                  <th><?php echo ASLang::get('register_date'); ?></th>
<!--                  <th>--><?php //echo ASLang::get('last_login'); ?><!--</th>-->
                  <th><?php echo ASLang::get('confirmed'); ?></th>
                  <th><?php echo ASLang::get('action'); ?></th>
                  </thead>
                  <?php foreach ($users as $user): ?>
                      <?php $tmpUser = new ASUser($user['user_id']); ?>
                      <?php $userRole = $tmpUser->getRole(); ?>
                      <tr class="user-row">
                          <td><?php echo htmlentities($user['username']); ?></td>
                          <td><?php echo htmlentities($user['email']); ?></td>
                          <td><?php echo $user['register_date']; ?></td>
<!--                          <td>--><?php //echo $user['last_login']; ?><!--</td>-->
                          <td>
                              <?php echo $user['confirmed'] == "Y"
                                  ? "<p class='text-success'>" . ASLang::get('yes') . "</p>"
                                  : "<p class='text-error'>" . ASLang::get('no') . "</p>"
                              ?>
                          </td>
                          <td>
                              <div class="btn-group">
                                  <a  class="btn <?php echo $user['banned'] == 'Y' ? 'btn-danger' : 'btn-primary'; ?> btn-user"
                                      href="javascript:void(0);"
                                      onclick="users.roleChanger(this,<?php echo $user['user_id'];  ?>);">

                                      <i class="icon-user icon-white glyphicon glyphicon-user"></i>
                                      <span class="user-role"><?php echo ucfirst($userRole); ?></span>
                                  </a>
                                  <a class="btn <?php echo $user['banned'] == 'Y' ? 'btn-danger' : 'btn-primary'; ?> dropdown-toggle" data-toggle="dropdown" href="#">
                                      <span class="caret"></span>
                                  </a>
                                  <ul class="dropdown-menu">
                                      <li>
                                          <a href="javascript:void(0);"
                                             onclick="users.editUser(<?php echo $user['user_id']; ?>);">
                                              <i class="icon-edit glyphicon glyphicon-edit"></i>
                                              <?php echo ASLang::get('edit'); ?>
                                          </a>
                                      </li>
                                      <li>
                                          <a href="javascript:void(0);"
                                             onclick="users.displayInfo(<?php echo $user['user_id']; ?>);">
                                              <i class="icon-pencil glyphicon glyphicon-pencil"></i>
                                              <?php echo ASLang::get('details'); ?>
                                          </a>
                                      </li>

                                      <?php if ( $user['banned'] == 'Y' ): ?>
                                          <li>
                                              <a href="javascript:void(0);"
                                                 onclick="users.unbanUser(this,<?php echo $user['user_id'];  ?>);">
                                                  <i class="icon-ban-circle glyphicon glyphicon-ban-circle"></i>
                                                  <span><?php echo ASLang::get('unban'); ?></span>
                                              </a>
                                          </li>
                                      <?php else: ?>
                                          <li>
                                              <a href="javascript:void(0);"
                                                 onclick="users.banUser(this,<?php echo $user['user_id'];  ?>);">
                                                  <i class="icon-ban-circle glyphicon glyphicon-ban-circle"></i>
                                                  <span><?php echo ASLang::get('ban'); ?></span>
                                              </a>
                                          </li>
                                      <?php endif; ?>

                                      <li>
                                          <a href="javascript:void(0);"
                                             onclick="users.deleteUser(this,<?php echo $user['user_id'];  ?>);">
                                              <i class="icon-trash glyphicon glyphicon-trash"></i>
                                              <?php echo ASLang::get('delete'); ?>
                                          </a>
                                      </li>

                                      <li class="divider"></li>

                                      <li>
                                          <a href="javascript:void(0);"
                                             onclick="users.roleChanger(this,<?php echo $user['user_id'];  ?>);">
                                              <i class="i"></i> <?php echo ASLang::get('change_role'); ?></a>
                                      </li>
                                  </ul>
                              </div>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              </table>
          </div>
        </div>
    
    <?php include 'templates/footer.php'; ?>
        
        <div class="modal" id="modal-user-details" style="display: none;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="modal-username">
                    <?php echo ASLang::get('loading'); ?>
                  </h4>
                </div>
                <div class="modal-body" id="details-body">
                  <dl class="dl-horizontal">
                    <dt title="<?php echo ASLang::get('email'); ?>"><?php echo ASLang::get('email'); ?></dt>
                    <dd id="modal-email"></dd>
                    <dt title="<?php echo ASLang::get('first_name'); ?>"><?php echo ASLang::get('first_name'); ?></dt>
                    <dd id="modal-first-name"></dd>
                    <dt title="<?php echo ASLang::get('last_name'); ?>"><?php echo ASLang::get('last_name'); ?></dt>
                    <dd id="modal-last-name"></dd>
                    <dt title="<?php echo ASLang::get('address'); ?>"><?php echo ASLang::get('address'); ?></dt>
                    <dd id="modal-address"></dd>
                    <dt title="<?php echo ASLang::get('phone'); ?>"><?php echo ASLang::get('phone'); ?></dt>
                    <dd id="modal-phone"></dd>
                    <dt title="<?php echo ASLang::get('last_login'); ?>"><?php echo ASLang::get('last_login'); ?></dt>
                    <dd id="modal-last-login"></dd>
                  </dl>
                </div>
                  <div align="center" id="ajax-loading"><img src="assets/img/ajax_loader.gif" /></div>
                <div class="modal-footer">
                  <a href="javascript:void(0);" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">
                    <?php echo ASLang::get('ok'); ?>
                  </a>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->

           <div class="modal <?php echo BOOTSTRAP_VERSION == 2 ? "hide" : "fade" ?>" id="modal-change-role">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="modal-username">
                    <?php echo ASLang::get('pick_user_role'); ?>
                  </h4>
                </div>
                <div class="modal-body" id="details-body">
                    <?php $roles = $db->select("SELECT * FROM `as_user_roles` WHERE `role_id` <> '3'"); ?>
                    <?php if(count($roles) > 0): ?>
                      <p><?php echo ASLang::get('select_role'); ?>:</p>
                      <select id="select-user-role" class="form-control" style="width: 100%;">
                      <?php foreach($roles as $role): ?>
                          <option value="<?php echo $role['role_id']; ?>">
                            <?php echo htmlentities(ucfirst($role['role'])); ?>
                          </option>
                      <?php endforeach; ?>
                      </select>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                  <a href="javascript:void(0);" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                    <?php echo ASLang::get('cancel'); ?>
                  </a>
                  <a href="javascript:void(0);" class="btn btn-primary" id="change-role-button" data-dismiss="modal" aria-hidden="true">
                      <?php echo ASLang::get('ok'); ?>
                  </a>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->



          <div class="modal" id="modal-add-edit-user" style="display: none;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="modal-username">
                    <?php echo ASLang::get('add_user'); ?>
                  </h4>
                </div>
                <div class="modal-body" id="details-body">
                    <form class="form-horizontal" id="add-user-form">
                      <input type="hidden" id="adduser-userId" />
                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-email">
                          <?php echo ASLang::get('email'); ?>
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-email" name="adduser-email" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>

                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-username">
                          <?php echo ASLang::get('username'); ?>
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-username" name="adduser-username" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>

                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-password">
                          <?php echo ASLang::get('password'); ?>
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-password" name="adduser-password" type="password" class="input-xlarge form-control" >
                        </div>
                      </div>

                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-confirm_password">
                          <?php echo ASLang::get('repeat_password'); ?>
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-confirm_password" name="adduser-confirm_password" type="password" class="input-xlarge form-control" >
                        </div>
                      </div>
                      <hr>
                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-first_name">
                          <?php echo ASLang::get('first_name'); ?>
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-first_name" name="adduser-first_name" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>
                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-last_name">
                          <?php echo ASLang::get('last_name'); ?>
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-last_name" name="adduser-last_name" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>
                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-address">
                          <?php echo ASLang::get('address'); ?>
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-address" name="adduser-address" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>
                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-phone">
                          <?php echo ASLang::get('phone'); ?>
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-phone" name="adduser-phone" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>
                  </form>
                </div>
                <div align="center" class="ajax-loading"><img src="assets/img/ajax_loader.gif" /></div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                      <?php echo ASLang::get('cancel'); ?>
                    </a>
                    <a href="javascript:void(0);" id="btn-add-user" class="btn btn-primary">
                      <?php echo ASLang::get('add'); ?>
                    </a>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->

        
        
        <script type="text/javascript" src="assets/js/sha512.js"></script>
        <script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="assets/js/dataTables.bootstrap.js"></script>
        <?php if(BOOTSTRAP_VERSION == 2): ?>
            <script type="text/javascript" src="assets/js/dataTables.bootstrap2.js"></script>
        <?php else: ?>
            <script type="text/javascript" src="assets/js/dataTables.bootstrap3.js"></script>
        <?php endif; ?>
        <script src="ASLibrary/js/asengine.js" type="text/javascript" charset="utf-8"></script>
        <script src="ASLibrary/js/users.js" type="text/javascript" charset="utf-8"></script>
        <script src="ASLibrary/js/index.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#users-list').dataTable();
    } );
</script>
  </body>
</html>
