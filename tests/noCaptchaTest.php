<?php

namespace AntiCaptchaSolver\Tests;

use AntiCaptchaSolver\Client;
use PHPUnit\Framework\TestCase;

/**
 * 
 */
class noCaptchaTest extends TestCase
{
	
	public function testNoCaptchaApi()
	{

		$api = readline("Your Key: ");
		$url = readline("Site Url: ");
		$siteKey = readline("Site key: ");

		$client = new Client($api);
		$options = 
			[
                'websiteURL' => $url,
                'websiteKey' => $siteKey,
            ];
        $result = $client->sendNoCaptcha($options);

		echo $result;
		
	}
}

