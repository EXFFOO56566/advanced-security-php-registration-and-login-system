<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Install | Advanced Security </title>
        <link rel='stylesheet' href='../assets/css/bootstrap.min2.css' type='text/css' media='all' />
        <link rel='stylesheet' href='bootstrap-wizard.css' type='text/css' media='all' />
        <link rel='stylesheet' href='install.css' type='text/css' media='all' />
        <script type="text/javascript" src="../assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="../assets/js/bootstrap.min2.js"></script>
        <script type="text/javascript" src="bootstrap-wizard.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="wizard" id="as-wizard">

                <h1>Advanced Security Installation</h1>
                
                <div class="wizard-card welcome-screen" data-cardname="welcome">
                    <h3>Welcome!</h3>
                    
                    <p>This wizard will guide you through few step installation process.</p>

                    <p>Hang on! Before you continue, please make sure that:</p>
                    <ul>
                        <li><strong>PDO MySQL extension is enabled</strong> in your php.ini</li>
                        <li><strong>Folder ASEngine is writable</strong> (permissions are set to 777)</li>
                    </ul>
                    <p>
                        When this installation process is finished, you and your website users will be able to login/register immediately! <br /> <br />
                        Yes, it's that simple.
                    </p>
                    <br />
                    <p>Before you continue, please select bootstrap version:</p>
                    <br />
                     <div class="btn-group bootstrap-version" data-toggle="buttons-radio">
                        <input type="hidden" name="bootstrap" class="btn-group-value" value="2" />
                        <button type="button" name="bootstrap" value="2" rel="bv1" class="btn btn-success active">Bootstrap 2.3.2</button>
                        <button type="button" name="bootstrap" value="3" rel="bv2" class="btn" >Bootstrap 3</button>
                    </div>
                    
                </div>
                

                <div class="wizard-card" data-cardname="site_info">
                    <h3>Site info</h3>
                    
                    <label for="website_name">Website name</label>
                    <input type="text" class="input-xlarge" name="website_name" id="website_name" />
                    <span class="help-block">Your website name.</span>

                    <label for="website_domain">Website Domain</label>
                    <input type="text" class="input-xlarge" name="website_domain" id="website_domain" value="<?php echo $_SERVER['HTTP_HOST']; ?>" />
                    <span class="help-block">
                        Your website domain (if script doesn't guess it correctly). If you are installing this script in subfolder, <strong>DON'T</strong> write path to that subfolder here!
                        So, just your website domain like google.com or codecanyon.com.
                    </span>

                </div>

                <div class="wizard-card" data-cardname="database">
                    <h3>Database info</h3>

                    <label for="db_host">Database host</label>
                    <input type="text" class="input-xlarge" id="db_host" name="db_host">
                    <span class="help-block">Database host. Usually you should enter localhost or mysql.</span>

                    <label for="db_user">Database user</label>
                    <input type="text" class="input-xlarge" id="db_user" name="db_user">
                    <span class="help-block">Your database username.</span>

                    <label for="db_pass">Database password</label>
                    <input type="text" class="input-xlarge" id="db_pass" name="db_pass">
                    <span class="help-block">Database password for entered username.</span>

                    <label for="db_name">Database name</label>
                    <input type="text" class="input-xlarge" id="db_name" name="db_name">
                    <span class="help-block">Name of database where AS tables should be created.</span>
                </div>

                <div class="wizard-card" data-cardname="session">
                    <h3>Session Configuration</h3>
                    
                    <label>Session secure</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="session_secure" class="btn-group-value"  value="false"/>
                        <button type="button" name="session_secure" value="true"  class="btn">Yes</button>
                        <button type="button" name="session_secure" value="false" class="btn btn-danger active" >No</button>
                    </div>
                    <span class="help-block">Select <strong>Yes</strong> if you are using HTTPS.</span>
                    
                    <label>Session HTTP Only</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="session_http_only" class="btn-group-value" value="true" />
                        <button type="button" name="session_http_only" value="true"  class="btn btn-success active">Yes</button>
                        <button type="button" name="session_http_only" value="false" class="btn" >No</button>
                    </div>
                    <span class="help-block">Prevent JavaScript to access your session cookie and protect you from XSS attack. Recommended: <strong>Yes</strong></span>
                    
                     <label>Session Regenerate Id</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="session_regenerate_id" class="btn-group-value" value="true" />
                        <button type="button" name="session_regenerate_id" value="true"  class="btn btn-success active">Yes</button>
                        <button type="button" name="session_regenerate_id" value="false" class="btn" >No</button>
                    </div>
                    <span class="help-block">Force session to regenerate id every time. Recommended: <strong>Yes</strong></span>
                    
                     <label>Session Use Only Cookies</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="session_use_only_cookies" class="btn-group-value" value="1" />
                        <button type="button" name="session_use_only_cookies" value="1"  class="btn btn-success active">Yes</button>
                        <button type="button" name="session_use_only_cookies" value="0" class="btn" >No</button>
                    </div>
                    <span class="help-block">Enabling this setting prevents attacks involved passing session ids in URLs. Recommended: <strong>Yes</strong></span>
                </div>
                
                <div class="wizard-card" data-cardname="login">
                    <h3>Login Configuration</h3>
                    
                    <label for="login_fingerprint">Fingerprint</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="login_fingerprint" class="btn-group-value" value="true"/>
                        <button type="button" name="login_fingerprint" value="true"  class="btn btn-success active">Yes</button>
                        <button type="button" name="login_fingerprint" value="false" class="btn" >No</button>
                    </div>
                    <span class="help-block">
                        If you select <strong>Yes</strong>, every time when user is logged in, hash function will generate string
                        based on your IP Address and your browser name, and store it inside $_SESSION. This will prevent someone 
                        to steal your session. <br />
                        <strong>Note: </strong> It can cause problems if user IP address changes very often. <br />
                        Recommended: <strong>Yes</strong>
                    </span>
                    
                    <label for="max_login_attempts">Max Login Attempts</label>
                    <div class="input-prepend">
                      <span class="add-on">#</span>
                      <input type="text" class="input-small" name="login_max_login_attempts" id="max_login_attempts" value="5" />
                    </div>
                    <span class="help-block">
                        Number of login attempts before IP address is blocked for current day. <br />
                        Prevent <strong>brute force</strong> attacks.  
                    </span>

                    <label for="redirect_after_login">Redirect After Login</label>
                    <div class="input-prepend">
                        <input type="text" class="input-xlarge" name="redirect_after_login" id="redirect_after_login" value="index.php" />
                    </div>
                    <span class="help-block">Page where user will be redirected after success login.</span>
                </div>
                
                <div class="wizard-card password-encription" data-cardname="password">
                    <h3>Password Encryption</h3>
                    
                    <div class="alert alert-success" id="choice-wrapper-bcrypt">
                        <div class="radio">
                              <input type="radio" name="encryption" id="encryption-bcrypt" value="bcrypt" checked>
                              Bcrypt
                        </div>
                        <br />
                        <span class="help-block">
                            Bcrypt is a key derivation function for passwords designed by Niels Provos and David Mazières, 
                            based on the Blowfish cipher, and presented at USENIX in 1999. <br />
                            <strong>Note:</strong> This method can be really slow if you choose cost greater than 15. <br />
                            It's <strong>recommended</strong> to choose cost between <strong>10</strong> and <strong>15</strong> 
                            to make balance between speed and security.<br />
                            Higher cost - slower but more secure.
                        </span>
                        <p>Cost</p>
                        <select class="form-control" name="bcrypt_cost">
                            <?php for($i=4; $i<=31; $i++){

                                    $i<10 ? $value = "0".$i : $value = $i; 
                                    $i == 13 ? $selected = " selected " : $selected = "";
                                    echo "<option value='$value' $selected>$value</option>";
                                }
                             ?>
                          </select>
                    </div>
                    
                    <div class="alert alert-error" id="choice-wrapper-sha">
                        <div class="radio">
                              <input type="radio" name="encryption" id="encryption-sha512" value="sha512">
                              SHA512
                        </div>  
                        <br />
                        <span class="help-block">
                            SHA-512 is one of cryptographic hash functions that belong to SHA2 family, designed by the U.S. National Security Agency (NSA) 
                            and published in 2001 by the NIST as a U.S. Federal Information Processing Standard. No security flaws identified. <br />
                             <strong>Note:</strong> This is very fast hash function, so if your priority is speed, this one you should choose. <br />
                             Its <strong>recommended</strong> to select number of iterations between <strong>30000</strong> and <strong>60000</strong>. <br />
                             More iterations - slower but more secure.
                        </span>
                        <p>Iterations</p>
                        <select class="form-control" name="sha512_iterations">
                            <?php for($i=5000; $i<=100000; $i+= 5000){
                                $i == 25000 ? $selected = " selected " : $selected = "";
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                          </select>
                    </div>
                </div>

                <div class="wizard-card" data-cardname="email">
                    <h3>Email Configuration</h3>

                    <label for="mailer">Mailer</label>
                    <select name="mailer" id="mailer">
                        <option value="mail">PHP mail()</option>
                        <option value="smtp">SMTP</option>
                    </select>

                    <div id="smtp-wrapper" style="display: none;">
                        <label for="smtp_host">SMTP Host</label>
                        <input type="text" class="input-xlarge" id="smtp_host" name="smtp_host">

                        <label for="smtp_port">SMTP Port</label>
                        <input type="text" class="input-xlarge" id="smtp_port" name="smtp_port">

                        <label for="smtp_username">SMTP Username</label>
                        <input type="text" class="input-xlarge" id="smtp_username" name="smtp_username">

                        <label for="smtp_password">SMTP Password</label>
                        <input type="text" class="input-xlarge" id="smtp_password" name="smtp_password">

                        <label for="smtp_enc">SMTP Encryption</label>
                        <input type="text" class="input-xlarge" id="smtp_enc" name="smtp_enc">
                        <span class="help-block">
                            Some servers require encryption (tls or ssl) set. Set it if your host requires that.
                        </span>
                    </div>
                </div>

                <div class="wizard-card" data-cardname="social">
                    <h3>Social Login</h3>

                    <label>Twitter Login</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="twitter_login" class="btn-group-value" id="tw-login" value="true" />
                        <button type="button" name="twitter_login" value="true" id="tw-enabled"  class="btn btn-success active">Enabled</button>
                        <button type="button" name="twitter_login" value="false" id="tw-disabled" class="btn" >Disabled</button>
                    </div>
                    <div class="tw-fields">
                        <label for="tw_key">Key</label>
                        <input type="text" class="input-xlarge" id="tw_key" name="tw_key">

                        <label for="tw_secret">Secret</label>
                        <input type="text" class="input-xlarge" id="tw_secret" name="tw_secret">
                    </div>

                    <label>Facebook Login</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="facebook_login" class="btn-group-value"  id="fb-login" value="true" />
                        <button type="button" name="facebook_login" value="true" id="fb-enabled"  class="btn btn-success active">Enabled</button>
                        <button type="button" name="facebook_login" value="false" id="fb-disabled" class="btn" >Disabled</button>
                    </div>
                    <div class="fb-fields">
                        <label for="fb_id">ID</label>
                        <input type="text" class="input-xlarge" id="fb_id" name="fb_id">

                        <label for="fb_secret">Secret</label>
                        <input type="text" class="input-xlarge" id="fb_secret" name="fb_secret">
                    </div>

                    <label>Google+ Login</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="google_login" class="btn-group-value"  id="gp-login" value="true" />
                        <button type="button" name="google_login" value="true" id="gp-enabled"  class="btn btn-success active">Enabled</button>
                        <button type="button" name="google_login" value="false" id="gp-disabled" class="btn" >Disabled</button>
                    </div>
                    <div class="gp-fields">
                        <label for="gp_id">ID</label>
                        <input type="text" class="input-xlarge" id="gp_id" name="gp_id">

                        <label for="gp_secret">Secret</label>
                        <input type="text" class="input-xlarge" id="gp_secret" name="gp_secret">
                    </div>
                </div>

                <div class="wizard-card" data-cardname="misc">
                    <h3>Miscellaneous</h3>

                    <label>Mail Confirmation Required</label>
                    <div class="btn-group" data-toggle="buttons-radio">
                        <input type="hidden" name="mail_confirm_required" class="btn-group-value" value="true" />
                        <button type="button" name="mail_confirm_required" value="true" class="btn btn-success active">Yes</button>
                        <button type="button" name="mail_confirm_required" value="false" class="btn" >No</button>
                    </div>
                    <div class="help-block">Is mail confirmation required for new users, after they register.</div>

                    <label for="prk_life">Password Reset Key Life</label>
                    <input type="text" name="prk_life" id="prk_life"/>
                    <div class="help-block">
                        How long will password reset key be valid after someone request password reset link?
                        <strong>(Integer that represent minutes)</strong>
                    </div>
                </div>
                
                <div class="wizard-card" data-cardname="install" id="finish-card">
                    <h3>Install</h3>
                    <p>Congratulations!</p>
                    <p>Advanced Security System is ready to be installed.</p>
                    <p>Click <strong>Install</strong> button to install it.</p>

                    <br/><br/>

                    <p>
                        <strong>Note:</strong> Installation shouldn't take more than few secs (usually about 1-2 secs).
                        If install button stays disabled for more than few seconds, check your browser's console to see
                        what is possible error returned from server.
                    </p>
                </div>

            </div>
	</div>
        
        <a data-controls-modal="as-wizard" data-backdrop="static" data-keyboard="false" href="#"></a>
        <script type="text/javascript" src="install.js"></script>
    </body>
</html>