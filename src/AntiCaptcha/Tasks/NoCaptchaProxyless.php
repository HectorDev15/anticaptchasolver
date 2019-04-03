<?php

namespace AntiCaptchaSolver\Tasks;

use AntiCaptchaSolver\Tasks\Task;

class NoCaptchaProxyless extends Task{

    protected $websiteURL;
    protected $website_key;
    //protected $website_s_token;

    /**
     * NoCaptchaTaskProxyless constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->type = self::NO_CAPTCHA_TASK_PROXYLESS; 
        $this->websiteURL = $this->array_get($options, 'websiteURL');
        $this->websiteKey = $this->array_get($options, 'websiteKey');
        //$this->website_s_token = $this->array_get($options, 'websiteSToken'); //Optional
    }
    
}
