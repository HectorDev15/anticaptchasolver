<?php

namespace AntiCaptchaSolver;

use AntiCaptchaSolver\Responses\BalanceResponse;
use AntiCaptchaSolver\Responses\QueueStatsResponse;
use AntiCaptchaSolver\Responses\TaskNoCaptchaResultResponse;
use AntiCaptchaSolver\Responses\TaskResponse;
use AntiCaptchaSolver\Responses\TaskResultResponse;
use AntiCaptchaSolver\Tasks\NoCaptchaProxyless;
use AntiCaptchaSolver\Tasks\ImageToTextTask;
use AntiCaptchaSolver\Tasks\Task;
use AntiCaptchaSolver\Traits\HelpersTrait;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

/**
 * 
 */
class Client
{
	use HelpersTrait;
    
    const ACTION_CREATE_TASK = 'createTask';
    const ACTION_GET_BALANCE = 'getBalance';
    const ACTION_GET_TASK_RESULT = 'getTaskResult';
    const ACTION_GET_QUEUE_STATS = 'getQueueStats';
    
    
    private $api_url = 'https://api.anti-captcha.com/';
    private $api_key;
    private $client;
    private $timeout = 30.0;
    private $language_pool = 'en';
    
    public function __construct(string $api_key, array $options = [])
    {
        $config = [
            'base_uri' => $this->api_url,
            'timeout' => $this->array_get($options, 'timeout') ?: $this->timeout
        ];
        
        $this->api_key = $api_key;
        $this->language_pool = $this->array_get($options, 'lang') ?: $this->language_pool;
        $this->client = new HttpClient($config);
    }

    /**
     * Makes api request
     *
     * @param string $action
     * @param array $params
     * @param array $options
     * @return ResponseInterface
     */
    private function requestApi(string $action, array $params = [], array $options = []): ResponseInterface
    {
        $default_params = [
            'clientKey' => $this->api_key,
        ];
        $request_params = array_merge($default_params, $params);
        $request = array_merge([
            'json' => $request_params,
        ], $options);

       return $this->client->post('/'.$action, $request);
    }

    /**
     * Creates a task for solving selected captcha type
     *
     * @param Task $task
     * @param string|null $soft_id
     * @return TaskResponse
     */
    public function createTask(Task $task, string $soft_id = null): TaskResponse
    {
        $request_params = [
            'task' => $task->getPropsAsArray(),
            'languagePool' => $this->language_pool,
            'softId' => $soft_id,
        ];
        
        return new TaskResponse($this->requestApi(static::ACTION_CREATE_TASK, $request_params));
    }

    /**
     * Retrieves account balance
     *
     * @return BalanceResponse
     */
    public function getBalance(): BalanceResponse
    {
        return new BalanceResponse($this->requestApi('getBalance'));
    }

    /**
     * Requests task result
     *
     * @param string $task_id
     * @param array $options
     * @return TaskResultResponse
     */
    public function getTaskResult(string $task_id, array $options = []): TaskResultResponse
    {
        $request_params = [
            'taskId' => $task_id,
        ];
        
        return new TaskResultResponse($this->requestApi(static::ACTION_GET_TASK_RESULT, $request_params, $options));
    }

    public function getNoCaptchaTaskResult(string $task_id, array $options = []): TaskNoCaptchaResultResponse
    {
        $request_params = [
            'taskId' => $task_id,
        ];
        
        return new TaskNoCaptchaResultResponse($this->requestApi(static::ACTION_GET_TASK_RESULT, $request_params, $options));
    }

    /**
     * Allows to define if it is suitable time to upload new task. Results are cached for 10 seconds
     *
     * @param int $queue_id
     * @return QueueStatsResponse
     */
    public function getQueueStats(int $queue_id): QueueStatsResponse
    {
        $request_params = [
            'queueId' => $queue_id
        ];
        
        return new QueueStatsResponse($this->requestApi(static::ACTION_GET_QUEUE_STATS, $request_params));
    }
    
    public function solveCaptcha(string $image_string)
    {
        $task = new ImageToTextTask($image_string);
        $taskResponse = $this->createTask($task);
        $stats = $this->getQueueStats(1);
        $delay = $stats->getSpeed() * 2;
        sleep($delay);
        
        return $this->getTaskResult($taskResponse->getTaskId())->getSolution()->getText();
    }

    public function solveNoCaptcha(array $options)
    {
        try {
            $task = new NoCaptchaProxyless($options);
            $taskResponse = $this->createTask($task);
            $stats = $this->getQueueStats(6);
            $delay = $stats->getSpeed() * 2;
            sleep($delay);
            do {
                $status = $this->getNoCaptchaTaskResult($taskResponse->getTaskId())->getStatus();
            } while ($status == 'processing');
            
            if ($status === 'ready') {
                return $this->getNoCaptchaTaskResult($taskResponse->getTaskId())->getSolution()->getGRecaptchaResponse();
            }else{
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function sendNoCaptcha(array $options)
    {
        try {
            $task = new NoCaptchaProxyless($options);
            $taskResponse = $this->createTask($task);
            if ($taskResponse->getTaskId() == 0) {
                return false;
            }
            return $taskResponse->getTaskId();
            
        } catch (\Exception $e) {
            return false;
        }
    }

    public function takeNoCaptcha(string $id)
    {
        try {
            $task = $this->getNoCaptchaTaskResult($id);
            if ($task->getStatus() === 'noFound') {
                return false;
            }

            $i = 0;
            
            do {
                if ($i > 0) {
                    sleep(25);
                }
                $status = $task->getStatus();
                $i++;
            } while ($status == 'processing' && $i <= 3);
            
            if ($status === 'ready') {
                return $task->getSolution()->getGRecaptchaResponse();
            }else{
                return false;
            }
            
        } catch (\Exception $e) {
            return false;
        }
    }
}