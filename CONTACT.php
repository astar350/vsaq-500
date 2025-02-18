<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__.'/Exception.php';
require __DIR__.'/PHPMailer.php';
require __DIR__.'/SMTP.php';

function validateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form2')
{
   $mailto = 'info@vsaq.ca';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Website form';
   $message = 'Demande provenant du site web vsaq.ca';
   $success_url = './Succes.html';
   $error_url = './Erreur.html';
   $eol = "\r\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response", "h-captcha-response");
   if (!empty($_POST['phone']))
   {
      $error .= "Spam detected.\n<br>";
      throw new Exception($error);
   }

   $mail = new PHPMailer(true);
   try
   {
      $mail->IsSMTP();
      $mail->Host = 'smtp.dreamhost.com';
      $mail->Port = 465;
      $mail->SMTPAuth = true;
      $mail->Username = 'vsaq@vsaq.ca';
      $mail->Password = 'Tq84yms#o!%Cg1V9';
      $mail->SMTPSecure = 'ssl';
      $mail->Subject = stripslashes($subject);
      $mail->From = $mailfrom;
      $mail->FromName = $mailfrom;
      $mailto_array = explode(",", $mailto);
      for ($i = 0; $i < count($mailto_array); $i++)
      {
         if(trim($mailto_array[$i]) != "")
         {
            $mail->AddAddress($mailto_array[$i], "");
         }
      }
      if (!validateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $sender_domain = substr($mailfrom, strpos($mailfrom, '@') + 1);
      if (!checkdnsrr($sender_domain, "MX"))
      {
         if (!(checkdnsrr($sender_domain, "A")) or !(checkdnsrr($sender_domain, "AAAA")))
         {
            $error .= "No email can be sent to the specified domain (" . $sender_domain . ").\n<br>";
            throw new Exception($error);
         }
      }
      $mail->AddReplyTo($mailfrom);
      foreach ($_POST as $key => $value)
      {
         if (preg_match('/www\.|http:|https:/i', $value))
         {
            $error .= "URLs are not allowed!\n<br>";
            throw new Exception($error);
            break;
         }
      }
      if (!empty($error))
      {
         throw new Exception($error);
      }

      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
         }
      }
      $mail->CharSet = 'UTF-8';
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
            if (is_array($_FILES[$key]['name']))
            {
               $count = count($_FILES[$key]['name']);
               for ($file = 0; $file < $count; $file++)
               {
                  if ($_FILES[$key]['error'][$file] == 0)
                  {
                     $mail->AddAttachment($_FILES[$key]['tmp_name'][$file], $_FILES[$key]['name'][$file]);
                  }
               }
            }
            else
            {
               if ($_FILES[$key]['error'] == 0)
               {
                  $mail->AddAttachment($_FILES[$key]['tmp_name'], $_FILES[$key]['name']);
               }
            }
         }
      }
      $mail->WordWrap = 80;
      $mail->Body = $message;
      $mail->Send();
      header('Location: '.$success_url);
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>CONTACT</title>
<meta name="generator" content="WYSIWYG Web Builder 19 Trial Version - https://www.wysiwygwebbuilder.com">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="fontawesome6.min.css" rel="stylesheet">
<link href="vsaq-121.css" rel="stylesheet">
<link href="CONTACT.css" rel="stylesheet">
<script src="jquery-3.7.1.min.js"></script>
<script src="wwb19.min.js"></script>
</head>
<body>
<div id="container">
<input type="submit" id="Button1" onclick="ShowObject('popupForm', 1);return false;" name="" value="Contactez-nous">
<a href="https://www.wysiwygwebbuilder.com" target="_blank"><img src="images/builtwithwwb19.png" alt="WYSIWYG Web Builder" style="position:absolute;left:970px;top:1886px;margin: 0;border-width:0;z-index:250" width="16" height="16"></a>
<div id="wb_Image1">
<img src="images/Screen_Shot_12-31-24_at_10.14_PM-removebg-preview.png" id="Image1" alt="" width="169" height="138"></div>
<div id="wb_Image2">
<img src="images/Screen_Shot_12-31-24_at_10.14_PM-removebg-preview.png" id="Image2" alt="" width="169" height="138"></div>
<div id="wb_Image4">
<a href="https://vsaq.square.site"><img src="images/BOUTIQUE ROUGE-Photoroom.png" id="Image4" alt="" width="388" height="45"></a></div>
<div id="wb_Image5">
<img src="images/CONTACT-Photoroom.png" id="Image5" alt="" width="517" height="60"></div>
<div id="wb_linksIcon1">
<a href="./index.html"><div id="linksIcon1"><i class="fa fa-brands fa-facebook"></i></div></a></div>
<div id="wb_linksIcon4">
<a href="./index.html"><div id="linksIcon4"><i class="fa fa-brands fa-twitter"></i></div></a></div>
<div id="wb_linksIcon2">
<div id="linksIcon2"><i class="fa fa-brands fa-instagram"></i></div></div>
<div id="wb_Text1">
<span style="color:#000000;"><strong>NAVIGUEZ</strong></span></div>
<div id="wb_Text2">
<span style="color:#000000;"><strong>SUIVEZ-NOUS</strong></span></div>
<div id="wb_Text3">
<span style="color:#000000;"><strong>COMPAGNIE</strong></span></div>
<div id="wb_CssMenu1">
<ul id="CssMenu1" role="menubar" class="nav">
<li role="menuitem" class="nav-item firstmain"><a class="nav-link" href="./index.html" target="_self">Home</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="./FESTILLANT.html" target="_self">Festilant</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="./VENDOME.html" target="_self">Vendôme</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="./ENNIUS.html" target="_self">Ennius</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="./FUENTEAMOR.html" target="_self">Fuenteamor</a>
</li>
</ul>
</div>
<div id="wb_CssMenu2">
<ul id="CssMenu2" role="menubar" class="nav">
<li role="menuitem" class="nav-item firstmain"><a class="nav-link" href="./index.html" target="_self">Facebook</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="" target="_self">Instagram</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="" target="_self">Twitter</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="" target="_self">YouTube</a>
</li>
</ul>
</div>
<div id="wb_linksMenu4">
<ul id="linksMenu4" role="menubar" class="nav">
<li role="menuitem" class="nav-item firstmain"><a class="nav-link" href="./A-PROPOS.html" target="_self">À&nbsp;propos</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="https://vsaq.square.site" target="_self">Boutique</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="./NOUVELLES.html" target="_self">Nouvelles</a>
</li>
<li role="menuitem" class="nav-item"><a class="nav-link" href="./CONTACT.php" target="_self">Contact</a>
</li>
</ul>
</div>
<div id="wb_Icon1">
<div id="Icon1"><i class="fa fa-brands fa-youtube-square"></i></div></div>
<div id="wb_Text5">
<span style="color:#000000;"><strong>Copyright © 2024 VSAQ. <br>Tout Droits Réservés.&nbsp; All Rights Reserved</strong></span></div>
<div id="wb_Form2">
<form name="contact" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="Form2">
<input type="hidden" name="formid" value="form2">
<input type="text" name="phone" value="" id="phone" title="phone">
<label for="Icon2" id="Label4">Nom</label>
<input type="text" id="Editbox3" name="Nom" value="" spellcheck="false">
<label for="" id="Label5">Courriel</label>
<input type="submit" id="Button4" name="" value="Envoyer">
<input type="reset" id="Button5" name="reset" value="Reset">
<textarea name="Message" id="TextArea2" rows="5" cols="52" spellcheck="false"></textarea>
<input type="text" id="Editbox4" name="Courriel" value="" spellcheck="false">
<label for="" id="Label6">Message</label>
</form>
</div>
<div id="wb_Text4">
<p><a href="tel:514-325-8510">514-325-8510</a></p></div>
<div id="wb_Icon2">
<a href="tel:514-325-8510"><div id="Icon2"><i class="fa fa-phone"></i></div></a></div>
<div id="wb_ResponsiveMenu1">
<label class="toggle" for="ResponsiveMenu1-submenu" id="ResponsiveMenu1-title">        MENU <span id="ResponsiveMenu1-icon"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></span></label>
<input type="checkbox" id="ResponsiveMenu1-submenu">
<ul class="ResponsiveMenu1" id="ResponsiveMenu1" role="menu">
<li role="menuitem"><a href="./index.html" class="nav-link">HOME</a></li>
<li role="menuitem"><a href="./FESTILLANT.html" class="nav-link">FESTILLANT</a></li>
<li role="menuitem"><a href="./VENDOME.html" class="nav-link">VENDÔME</a></li>
<li role="menuitem"><a href="./ENNIUS.html" class="nav-link">ENNIUS</a></li>
<li role="menuitem"><a href="./FUENTEAMOR.html" class="nav-link">FUENTEAMOR</a></li>
<li role="menuitem"><a href="./CONTACT.php" class="active">CONTACT</a></li>
</ul>
</div>
<div id="wb_Image3">
<a href="http://vsaq-2.square.site/"><img src="images/vsaq-6-removebg-preview.png" id="Image3" alt="" width="612" height="231"></a></div>
</div>
</body>
</html>