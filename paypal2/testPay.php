<?php
	/* Teting Paypal connection
	 *
	 */
	require_once 'Pay.php';
	
	function testSimplePay(){
		$simplePay = new Pay();
		$response = $simplePay->simplePay();
		$this->assertEquals("Success",$response->responseEnvelope->ack);
		$this->assertNotNull($response->payKey);
	}
	
	
	testSimplePay();
?>
	