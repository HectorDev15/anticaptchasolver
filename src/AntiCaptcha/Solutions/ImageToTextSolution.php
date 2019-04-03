<?php

namespace AntiCaptchaSolver\Solutions;

use AntiCaptchaSolver\Traits\HelpersTrait;

/**
 * Class ImageToTextSolution
 * @package AntiCaptcha\Solutions
 */
class ImageToTextSolution extends Solution
{
    use HelpersTrait;

    /**
     * Captcha answer
     * @var string|null
     */
    protected $text;

    /**
     * Web address where captcha file can be downloaded. Available withing 48 hours after task creation
     * @var string|null
     */
    protected $url;

    /**
     * ImageToTextSolution constructor.
     * @param array $response
     */
    function __construct(array $response)
    {
        $this->text = $this->array_get($response, 'text');
        $this->url = $this->array_get($response, 'url');
    }

    /**
     * Captcha answer
     * 
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Web address where captcha file can be downloaded. Available withing 48 hours after task creation
     * 
     * @return string
     */
    public function getUrl(): string 
    {
        return $this->url;
    }
}