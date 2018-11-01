<?php
/*
    qmvc - A small but powerful MVC framework written in PHP.
    Copyright (C) 2016 ThrDev
    
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class Ajaxer extends Module {
    public function setup($setup) {
        return $this->render($setup);
    }
    
    private function render($values) {
        ob_start();
        ?>
        <script>
            jQuery(function() {
                var verified = false;
                var failed = false;
                var failedid = "";
                var successid = "";
                var error = false;
                var allowsubmitagain = true;
                
                jQuery("form#<?php echo $values["form"]; ?>").submit(function(e) {
                    e.preventDefault();
                    if(!allowsubmitagain) {
                        return false;
                    }
                    //check values.
                    var form = jQuery(this);
                    if(!verified) {
                        jQuery(this).find("input, textarea").each(function() {
                        <?php foreach($values["required"] as $field) { ?>
                            if(jQuery(this).attr("name") == "<?php echo $field; ?>" && jQuery(this).val().length <= 0) { 
                                if(!error) ajaxer_error(form, "Please fill out all the required fields."); 
                                var field = jQuery(this); 
                                <?php echo $values['failed']; ?>; 
                                error = true;
                            }
                        <?php } ?>
                        }); 
                        
                        if(error) {
                            error = false;
                            return;
                        }
                        verified = true;
                    }
                    
                    if(verified) {
                        //ajax post form.
                        jQuery.post("<?php echo $values["url"]; ?>", jQuery(this).serialize(), function(data) {
                            data = JSON.parse(data);
                            if(data.error) {
                                //let them know it failed.
                                ajaxer_error(form, data.error_msg);
                                <?php if(isset($values['onerror'])) echo $values['onerror']; ?>
                            } else {
                                allowsubmitagain = false;
                                ajaxer_success(form, data.msg);
                                <?php if(array_key_exists("redirect", $values) && $values['redirect'] != false) { ?>
                                    setTimeout(function() {
                                        window.location.href="<?php echo $values["redirect"]; ?>";
                                    }, 5000);
                                <?php } else { ?>
                                    allowsubmitagain = true;
                                    verified = false;
                                    //make sure to remove success message when they resubmit?
                                <?php } ?>
                            }
                        });
                    }
                    verified = false;
                    return false;
                });
                
                jQuery("form#<?php echo $values["form"]; ?> input").click(function(e) {
                    if(failedid != "") {
                        jQuery("#"+failedid).slideUp("fast");
                    }
                    jQuery(this).removeAttr("style"); 
                });
                
                var generatestr = function() {
                    var text = "";
                    var abc = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    for( var i=0; i < 5; i++ )
                        text += abc.charAt(Math.floor(Math.random() * abc.length));
                    return text;
                }
                
                var ajaxer_error = function(form, errmsg) {
                    if(failedid != "") jQuery("#"+failedid).slideUp('fast', function() { jQuery(this).remove(); });
                    if(successid != "") jQuery("#"+successid).slideUp('fast', function() { jQuery(this).remove(); });
                    failedid = generatestr();
                    //slideup any existing fields.
                    form.prepend('<div id="'+failedid+'" class="<?php echo $values["error"]; ?>" style="display: none;" role="alert">'+errmsg+'</div>');
                    jQuery("#"+failedid).slideDown('fast');
                }
                
                var ajaxer_success = function(form, msg) {
                    if(failedid != "") jQuery("#"+failedid).slideUp('fast', function() { jQuery(this).remove(); });
                    if(successid != "") jQuery("#"+successid).slideUp('fast', function() { jQuery(this).remove(); });
                    successid = generatestr();
                    form.prepend('<div id="'+successid+'" class="<?php echo $values["success"]; ?>" style="display: none;" role="alert">'+msg+'</div>');
                    jQuery("#"+successid).slideDown('fast');
                }
            });
        </script>
        <?php
        return ob_get_clean();
    }
}