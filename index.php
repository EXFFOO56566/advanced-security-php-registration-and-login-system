<?php
include 'templates/header.php';
?>
        
            <!-- Left nav
            ================================================== -->
            <div class="row">
              <div class="span3 bs-docs-sidebar">
                <ul class="nav nav-list bs-docs-sidenav">
                  <li class="active">
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
              <div class="span9">
                    
                    <div class="comments">
                            <h3 id="comments-title">
                              <?php echo ASLang::get('comments_wall'); ?> 
                              <small><?php echo ASLang::get('last_7_posts'); ?></small>
                            </h3>
                            <div class="comments-comments">
                                <?php $ASComment = new ASComment(); ?>
                                <?php $comments = $ASComment->getComments(); ?>
                                <?php foreach($comments as $comment): ?>
                                 <blockquote>
                                    <p><?php echo htmlentities( stripslashes($comment['comment']) ); ?></p>
                                    <small>
                                        <?php echo htmlentities($comment['posted_by_name']);  ?> 
                                        <em> <?php echo ASLang::get('at'); ?> <?php echo $comment['post_time']; ?></em></small>
                                </blockquote>
                                <?php endforeach; ?>
                            </div>
                    </div>
                
                    <?php if($user->getRole() != 'user'): ?>
                    <div class="leave-comment">
                        <div class="control-group form-group">
                            <h5><?php echo ASLang::get('leave_comment'); ?></h5>
                            <div class="controls">
                                <textarea class="form-control" id="comment-text"></textarea>
                            </div>
                        </div>
                        <div class="control-group form-group">
                             <div class="controls">
                                <button class="btn btn-success" id="comment">
                                  <?php echo ASLang::get('comment'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <p><?php echo ASLang::get('you_cant_post'); ?></p>
                    <?php endif; ?>
                    
        
              </div>
            </div>
        
    <?php include 'templates/footer.php'; ?>

    <script src="ASLibrary/js/asengine.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="ASLibrary/js/index.js" charset="utf-8"></script>

    
  </body>
</html>
