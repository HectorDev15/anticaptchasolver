<?php

namespace AntiCaptchaSolver\Responses;

use Psr\Http\Message\ResponseInterface;

/**
 * Class TaskResponse
 * @package AntiCaptcha\Responses
 */
class TaskResponse extends Response
{
    /**
     * Task ID for future use in getTaskResult method
     * 
     * @var int
     */
    protected $task_id;

    /**
     * TaskResponse constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
        $this->task_id = (integer) $this->array_get($this->response_body, 'taskId');
    }

    /**
     * Returns task ID for future use in getTaskResult method
     * 
     * @return int
     */
    public function getTaskId(): int 
    {
        return $this->task_id;
    }
}