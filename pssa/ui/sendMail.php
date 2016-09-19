<?php 
require("business/User.php");
include("functions/fileHelper.php");
include("functions/mutationHelper.php");
include("pdf/class.ezpdf.php");

$reportFile=$_GET['reportFile'];
$graphFile=substr($reportFile, 0, strpos($reportFile, ".")).".json";
$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
if(!empty($_POST['name'])){
	$name=$_POST['name'];
	$institution=$_POST['institution'];
	$country=$_POST['country'];
	$mail=$_POST['mail'];
	
	$user=new User("",$name,$institution,$country,$mail);
	$user->insert();
	
	$to=$name."<".$mail.">";
	$separator = md5(time());
	$eol = PHP_EOL;
	$subject="PSSA report";
	$message="Dear ".$name."\n\n 
PSSA has generated the report of your analisys in a pdf file.\n
You can download the report in the link: http://pssa.itiud.org/reportFiles/".$reportFile."\n
You can also view the 'Force-Directed Graph' of your analysis in the link: http://pssa.itiud.org/index.php?pid=dWkvZ3JhcGhWaWV3ZXIucGhw&graphFile=".$graphFile."\n
This report will be available 15 days.\n\n		
Best regards.\n
ITI Research Group.\n
www.itiud.org";

/*	$file = "reportFiles/" . $reportFile;
	$file_size = filesize($file);
	$handle = fopen($file, "r");
	$content = fread($handle, $file_size);
	fclose($handle);
	$content = chunk_split(base64_encode($content));
*/	
	$headers = "FROM: ITI Research Group <iti@udistrital.edu.co>";
/*	$headers .= "MIME-Version: 1.0" . $eol;
	$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
	$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
	$headers .= "This is a MIME encoded message." . $eol . $eol;

	$headers .= "--" . $separator . $eol;
	$headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
	$headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
	$headers .= $message . $eol . $eol;
	
	$headers .= "--" . $separator . $eol;
	$headers .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
	$headers .= "Content-Transfer-Encoding: base64" . $eol;
	$headers .= "Content-Disposition: attachment" . $eol . $eol;
	$headers .= $content . $eol . $eol;
	$headers .= "--" . $separator . "--";
*/	
	mail($to,$subject,$message,$headers);
}
?>
<script>
function validate(){
	if(document.forms.form.mail.value!=""){
		var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
		if(document.forms.form.mail.value.match(mailformat)){ 
			$('#alert2').removeClass().addClass('hide');					
			$('#submit').removeClass().addClass('btn btn-<?php echo $color; ?>');							
		}else{  
			$('#alert2').removeClass().addClass('alert alert-danger');	
			$('#submit').removeClass().addClass('hide');	
		}  		
	}else{
		$('#alert2').removeClass().addClass('hide');					
		$('#submit').removeClass().addClass('hide');	
	}
	if(document.forms.form.name.value!="" &&
		document.forms.form.institution.value!="" &&
		document.forms.form.mail.value!="" &&
		document.forms.form.country.value!=-1){
		$('#alert').removeClass().addClass('hide');					
		$('#submit').removeClass().addClass('btn btn-<?php echo $color; ?>');	
	}else{
		$('#alert').removeClass().addClass('alert alert-danger');	
		$('#submit').removeClass().addClass('hide');	
	}
}
</script>
<div class="container">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="panel panel-<?php echo $color; ?>">
				<div class="panel-heading">
					<h3 class="panel-title">Send Report</h3>
				</div>
				<div class="panel-body">
				<?php if(empty($_POST['name'])){ ?>
					<form id="form" role="form" method="post" action="index.php?pid=<?php echo base64_encode("ui/sendMail.php") ?>&reportFile=<?php echo $reportFile ?>">
						<div class="form-group">
							<label>User Name</label>
							<input type="text" class="form-control" name="name" onkeyup="validate()" />
						</div>
						<div class="form-group">
							<label>Institution</label>
							<input type="text" class="form-control" name="institution" onkeyup="validate()" />
						</div>
						<div class="form-group">
							<label>Country</label>
							<select class="form-control" name="country" onchange="validate()">
								<option value="-1">Select Country</option>
								<?php
								for ($i = 0; $i < count($countries); $i++) {
									echo "<option value='". $countries[$i] ."'>". $countries[$i] ."</option>";
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Mail</label>
							<input type="email" class="form-control" name="mail" onkeyup="validate()" />
						</div>
						<div id='alert' class='alert alert-danger' role='alert'>Please fill all fields fo the form</div>					
						<div id='alert2' class='hide' role='alert'>Please insert a valid mail</div>					
						<button type="submit" class="hide" id="submit" name="submit">Send Report</button>
					</form>
				<?php }else{ ?>
					<div id='alert' class='alert alert-success' role='alert'>The report was correctly sent.<br>This report will be available 15 days.</div>					
					<strong>User Name:</strong> <?php echo $name ?><br >
					<strong>Institution:</strong> <?php echo $institution ?><br >
					<strong>Country:</strong> <?php echo $country ?><br >
					<strong>Mail:</strong> <?php echo $mail ?><br >
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>