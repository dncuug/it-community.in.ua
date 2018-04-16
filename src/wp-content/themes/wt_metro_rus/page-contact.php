<?php
session_start();
/**
 * Template Name: Контакты
 * Description: A Page Template to display contact form with captcha and jQuery validation.
 *
 * @package  WellThemes
 * @file     page-contact.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
	$name_error = '';
	$email_error = '';
	$message_error = '';
	$captcha_error = '';
	
if(isset($_POST['wt_submit'])) {

		//validate sender name
		if(trim($_POST['sender_name']) === '') {
			$name_error = 'Please enter your name.';
			$has_error = true;
		} else {
			$name = trim($_POST['sender_name']);
		}
		
		//validate sender email
		if(trim($_POST['sender_email']) === '')  {
			$email_error = 'Please enter your email address.';
			$has_error = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['sender_email']))) {
			$email_error = 'Please enter a valid email address.';
			$has_error = true;
		} else {
			$email = trim($_POST['sender_email']);
		}
		
		//validate message
		if(trim($_POST['message_text']) === '') {
			$message_error = 'Please enter a message.';
			$has_error = true;
		} else {
			if(function_exists('stripslashes')) {
				$message = stripslashes(trim($_POST['message_text']));
			} else {
				$message = trim($_POST['message_text']);
			}
		}
		
		//validate captcha
		$captcha_real = strtolower($_SESSION['wt_captcha']);
		$captcha_submitted = strtolower($_POST['captcha_code']);
		
		if ($captcha_submitted == ''){
			$captcha_error =  "Please enter the verification code.";
			$has_error = true;		
		} else if( $captcha_submitted != $captcha_real ) {
			$captcha_error = "Please enter code correctly.";
			$has_error = true;	
		}
		
		//if no error, send email.
		if(!isset($has_error)) {
			
			$email_to = wt_get_option('wt_contact_email');		
			$subject = wt_get_option('wt_contact_subject');	
			
			if (!isset($email_to) || ($email_to == '') ){
				$email_to = get_option('admin_email');				
			}
			
			if (!isset($subject) || ($subject == '') ){
				$subject = 'Contact Message From '.$name;			
			}

			$body = "Name: $name \n\nEmail: $email \n\nComments: $message";
			$headers = 'From: '.$name.' <'.$email_to.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			mail($email_to, $subject, $body, $headers);
			$email_sent = true;
		}
	
	} 

?>

<?php get_header(); ?>

	<script type="text/javascript">
	<!--//--><![CDATA[//><!--
		jQuery(document).ready(function() {
			jQuery('form#wt_contact_form').submit(function() {
			jQuery('form#wt_contact_form .error').remove();
			var hasError = false;
			jQuery('.requiredField').each(function() {
			if(jQuery.trim(jQuery(this).val()) == '') {
									
					if(jQuery(this).hasClass('name_field')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Ваше имя.', 'wellthemes'); ?></span>');
					}
					
					if(jQuery(this).hasClass('title_field')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter message title.', 'wellthemes'); ?></span>');
					}
					
					if(jQuery(this).hasClass('email')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Ваш email.', 'wellthemes'); ?></span>');
					}
					
					if(jQuery(this).hasClass('message_field')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter your message.', 'wellthemes'); ?></span>');
					}
					
					if(jQuery(this).hasClass('captcha_field')) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter the security code.', 'wellthemes'); ?></span>');
					}
				
					jQuery(this).addClass('inputError');
					hasError = true;
				} else if(jQuery(this).hasClass('email')) {
					var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
					if(!emailReg.test(jQuery.trim(jQuery(this).val()))) {
						jQuery(this).parent().append('<span class="error"><?php _e('Please enter valid email', 'wellthemes'); ?> </span>');
						jQuery(this).addClass('inputError');
						hasError = true;
					}
				}
			});
						
			if(hasError) {
				return false;
			} else{
				return true;
			}						
			});
		});
	//-->!]]>
	</script>	
	
	<section id="primary">
		<div id="content" class="contact-page role="main">
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			
			</header><!-- /entry-header -->
			
			<?php
				$wt_contact_address = wt_get_option( 'wt_contact_address' );
				
			?>
						<div class="contact-text">		
				<?php while ( have_posts() ) : the_post(); ?>			
					<?php the_content(); ?>			
				<?php endwhile; // end of the loop. ?>					
			</div><!-- /contact-text -->
		
			<div class="contact-wrap">
				<div class="contact-form">						
					<?php if(isset($email_sent) && $email_sent == true) { ?>				
						<div class="msgbox msgbox-success"><?php _e('<strong>Thank you.</strong> Your email was sent successfully.', 'wellthemes') ?></div>	
					<?php } else { ?>
	
					<?php if(isset($has_error)) { ?>
						<div class="msgbox msgbox-error"><?php _e('Please correct the following errors and try again.', 'wellthemes') ?></div>
						<?php } ?>
	
						<form action="<?php $_SERVER['PHP_SELF']; ?>" id="wt_contact_form" method="post">
						
						<div class="row">
							
							<div class="row-half">	
								<div class="field">
									<label for="sender_name"><?php _e('Name', 'wellthemes') ?><span class="required">required </span></label>
									<input type="text" class="text name_field requiredField" name="sender_name" id="sender_name" placeholder="Ваше имя и фамилия" value="<?php if(isset($_POST['sender_name'])) echo $_POST['sender_name'];?>" />
									<?php if($name_error != '') { ?>
										<span class="error"><?php echo $name_error; ?></span>  
									<?php } ?>
								</div>
							</div>
						
							<div class="row-half right">
								<div class="field">
									<label for="sender_email"><?php _e('Email', 'wellthemes') ?><span class="required">required </span></label>
									<input type="text" class="text requiredField email" name="sender_email" id="sender_email" placeholder="Для связи с Вами" value="<?php if(isset($_POST['sender_email']))  echo $_POST['sender_email'];?>" />
									<?php if($email_error != '') { ?>
										<span class="error"><?php echo $email_error; ?></span> 
									<?php } ?>	
								</div>
							</div>
							
						</div><!-- /row -->
						
						<div class="row">
							<div class="field message-field">
								<label for="message_title"><?php _e('Message title', 'wellthemes') ?><span class="required">required </span></label>
								<input type="text" class="text title_field requiredField" name="message_title" id="message_title" placeholder="О чем бы вы хотели бы спросить?" value="<?php if(isset($_POST['message_title'])) echo $_POST['message_title'];?>" />
								<?php if($name_error != '') { ?>
									<span class="error"><?php echo $message_error; ?></span>  
								<?php } ?>
							</div>
						</div>
						
						<div class="row">
							<div class="field textarea-field">		
								<label for="message_text"><?php _e('Write your message below', 'wellthemes') ?><span class="required">required </span></label>
								<textarea class="textarea message_field requiredField" name="message_text" id="message_text" placeholder="Ваш вопрос"><?php if(isset($_POST['message_text'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['message_text']); } else { echo $_POST['message_text']; } } ?></textarea>
																
								<?php if($message_error != '') { ?>
									<span class="error"><?php echo $message_error; ?></span> 
								<?php } ?>				
							</div>	
						</div>						
						
						<div class="row">
							
							<div class="field">
								
								<label for="captcha_code"><?php _e('Verification Code', 'wellthemes') ?></label>
								
								<div class="row-half">
									<input type="text" class="text requiredField captcha_field" name="captcha_code" id="captcha_code" value="<?php if(isset($_POST['captcha_code'])) echo $_POST['captcha_code'];?>" />
								</div>
								
								<div class="row-half right">
									<span class="captcha">
										<div class="captcha-image"><img src="<?php echo get_template_directory_uri(); ?>/framework/captcha/captcha.php?width=305&height=50&characters=5" /></div>
									</span>
								</div>
								
								<?php if($captcha_error != '') { ?>
										<span class="error"><?php echo $captcha_error; ?></span>  
								<?php } ?>
							</div>
						</div>
						
						<div class="row">
							<div class="field">
								<input type="submit" name="wt_submit" value="Отправить сообщение" class="button" />
							</div>
						</div>					
				</form>
	
			<?php } ?>
	
		</div><!-- /contact-form -->
		
			
	</div><!-- /contact-form-wrap -->
	
	
	
		</div><!-- /content -->
	</section><!-- /primary -->
<?php get_sidebar('left'); ?>
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>