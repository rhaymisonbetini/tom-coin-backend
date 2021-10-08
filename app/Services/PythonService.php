<?php

namespace App\Services;

class PythonService
{
    /**
     * @var string
     */
    protected $pythonBase = 'http://127.0.0.1:5000/';

    protected $getChain;
    /**
     * @var string
     */
    protected  $minBlock;
    /**
     * @var string
     */
    protected  $addTransaction;
    /**
     * @var string
     */
    protected $createNewBlock;

    public function __construct()
    {
        $this->getChain = $this->pythonBase . 'get_chain';
        $this->minBlock = $this->pythonBase . 'mine_block';
        $this->addTransaction = $this->pythonBase . 'add_transaction';
        $this->createNewBlock = $this->pythonBase . 'create-new-block';
    }

    public function minBlock(): string
    {
        return $this->minBlock;
    }
    public function getChain(): string
    {
        return $this->getChain;
    }
    public function addTransaction(): string
    {
        return $this->addTransaction;
    }
    public function createNewBlock(): string
    {
        return $this->createNewBlock;
    }
}
