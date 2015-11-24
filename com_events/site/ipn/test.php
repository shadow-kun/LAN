<?php 
	if((isset($result->id) == true && ($pAmount == $paypalAmount) && (strcasecmp($_POST['business'], $pEmail) == 0) && (strcasecmp($_POST['mc_currency'], $pCurrency) == 0)))
	{
		echo 'yay';
	}
	else
	{
		echo 'boo';
	}
					
?>