<?php
/*
File: pi.md_spam_me_not.php
------------------------------------------------------------------------
Purpose: Encode email addresses to make it harder for spammers to harvest them.
========================================================================
*/

$plugin_info = array(
			'pi_name'	       => 'MD Spam Me Not',
			'pi_version'     => '1.0.5',
			'pi_author'      => 'Ryan Masuga',
			'pi_author_url'  => 'http://masugadesign.com/',
			'pi_description' => 'Obfuscates email in a way that still works when Javascript is disabled.',
			'pi_usage'			 => Md_spam_me_not::usage()
		);

class Md_spam_me_not {

  var $return_data = "";
  var $hiddenemail = "";
  var $show = "";
	
	function md_spam_me_not()
	{
			$this->EE =& get_instance();

			$email   = $this->EE->TMPL->fetch_param('email'); //REQURIED
			$mode    = $this->EE->TMPL->fetch_param('mode'); //OPTIONAL - Default of 1
			$text    = $this->EE->TMPL->fetch_param('text'); //OPTIONAL - defaults to email address
			$title   = $this->EE->TMPL->fetch_param('title'); //OPTIONAL
			$subject = $this->EE->TMPL->fetch_param('subject'); //OPTIONAL
			$class   = $this->EE->TMPL->fetch_param('class'); //OPTIONAL
			$mailto  = $this->EE->TMPL->fetch_param('mailto'); //OPTIONAL
			
			if(!$email)
		  {
			  return;
			}
			
			if ($mode == ""){$mode = "1";}
			
			$encodedString = "";
			$originalLength = strlen($email);
			
			for ( $i = 0; $i < $originalLength; $i++) {
			if ($mode == 3) $mode = rand(1,2);
			switch ($mode) {
				case 1: // Decimal code
					$encodedString .= "&#" . ord($email[$i]) . ";";
					break;
				case 2: // Hexadecimal code
					$encodedString .= "&#x" . dechex(ord($email[$i])) . ";";
					break;
				default:
					//return "ERROR: wrong encoding mode.";
			}
		}
		
		
		if($mailto != '') 
		{
		  $hiddenemail = $encodedString;
		}
		else
		{
  		$show = ($text=="") ? $encodedString : $text;
		
  		$myclass = '';
  		if($class != '') $myclass = " class=\"" . $class . "\"";
		
  		$subject_line = '';
  		if($subject != '') $subject_line = "?subject=$subject";
				
  		$title_attr = '';
  		if($title != '') $title_attr = " title=\"" . $title . "\"";
		
  		$hiddenemail = "<a href=\"mailto:$encodedString$subject_line\" $myclass $title_attr>$show</a>";

		}
		$this->return_data = $hiddenemail;
		
	}
    
	// ----------------------------------------
	//  Plugin Usage
	// ----------------------------------------

	function usage()
	{
		ob_start(); 
		?>
			Place the following tag in any of your templates:

		{exp:md_spam_me_not email=""}

		PARAMETERS: 
		The tag has seven parameters:

		1. email - The email address to obfuscate. [REQUIRED]
		2. mode - 1 or 2. 1 = decimal mode and 2 = Hexadecimal [OPTIONAL, defaults to 1]
		3. text - Text to display if different than the email address [OPTIONAL, defaults to showing the email address]
		4. title - [OPTIONAL]
		5. class - [OPTIONAL]
		6. subject - [OPTIONAL]
		7. mailto - [OPTIONAL] Set this to "off" and every other parameter will be ignored - it will return only the email address.

		Example usage (full):  {exp:md_spam_me_not email="ryan@masugadesign.com" mode="1" title="My Title" class="nocion email" subject="EE Inquiry" text="Email Ryan for more info."}

		Example usage (basic):  {exp:md_spam_me_not email="ryan@masugadesign.com"}

		Example usage (mailto): {exp:md_spam_me_not email="ryan@masugadesign.com" mailto="off"}


		<?php
		$buffer = ob_get_contents();
	
		ob_end_clean(); 

		return $buffer;
	}


}
// END CLASS

/* End of file pi.md_spam_me_not.php */