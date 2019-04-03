<?php

namespace AntiCaptchaSolver\Responses;

use AntiCaptchaSolver\Solutions\ImageToTextSolution;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TaskResultResponse
 * @package AntiCaptcha\Responses
 */
class TaskResultResponse extends Response
{
    /**
     * Task status processing|ready
     * 
     * @var string|null
     */
    protected $status;

    /**
     * Task result data
     * 
     * @var ImageToTextSolution|null
     */
    protected $solution;

    /**
     * Task cost in USD
     * 
     * @var float
     */
    protected $cost;

    /**
     * IP from which the task was created
     * 
     * @var string|null
     */
    protected $ip;

    /**
     * UNIX Timestamp of task creation
     * 
     * @var int
     */
    protected $create_time;

    /**
     * UNIX Timestamp of task completion
     * @var int
     */
    protected $end_time;

    /**
     * Number of workers who tried to complete your task
     * @var int
     */
    protected $solve_count;

    /**
     * TaskResultResponse constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
        $this->status = $this->array_get($this->response_body, 'status');
        $this->cost = (double) $this->array_get($this->response_body, 'cost');
        $this->ip = $this->array_get($this->response_body, 'ip');
        $this->create_time = (integer) $this->array_get($this->response_body, 'createTime');
        $this->end_time = (integer) $this->array_get($this->response_body, 'endTime');
        $this->solve_count = (integer) $this->array_get($this->response_body, 'solveCount');
        $this->solution = $this->status == 'ready' 
            ? new ImageToTextSolution($this->array_get($this->response_body, 'solution')) 
            : null;
    }

    /**
     * Status of the task
     * 
     * processing - task is not ready yet
     * ready - task complete, solution object can be found in solution property
     * 
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Task result data
     * 
     * @return ImageToTextSolution|null
     */
    public function getSolution(): ?ImageToTextSolution
    {
        return $this->solution;
    }

    /**
     * Task cost in USD
     * 
     * @return double
     */
    public function getCost(): double 
    {
        return $this->cost;
    }

    /**
     * IP from which the task was created
     * 
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * UNIX Timestamp of task creation
     * 
     * @return int
     */
    public function getCreateTime(): int 
    {
        return $this->create_time;
    }

    /**
     * UNIX Timestamp of task completion
     * 
     * @return int
     */
    public function getEndTime(): int 
    {
        return $this->end_time;
    }

    /**
     * Number of workers who tried to complete your task
     * 
     * @return int
     */
    public function getSolveCount(): int
    {
        return $this->solve_count;
    }
}