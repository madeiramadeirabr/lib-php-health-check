<?php 

namespace MadeiraMadeira\HealthCheck\Core\Entities;

class Dependency
{
    private $name;

    private $kind;

    private $status;

    private $isInternal;

    private $isOptional;

    public function __construct($args)
    {
        $this->setName($args['name']);
        $this->setKind($args['kind']);
        $this->setInternal($args['internal']);
        $this->setOptional($args['optional']);
        $this->setStatus(Status::getHealthyStatus());

        if (!empty($args['status']) && Status::isValidStatus($args['status'])) {
            $this->setStatus($args['status']);
        }
    
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setKind(string $kind)
    {
        $this->kind = $kind;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function setInternal(bool $isInternal)
    {
        $this->isInternal = $isInternal;
    }

    public function setOptional(bool $isOptional)
    {
        $this->isOptional = $isOptional;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function getInternal()
    {
        return $this->isInternal;
    }
    
    public function getOptional()
    {
        return $this->isOptional;
    }

    public function getStatus()
    {
        return $this->status;
    }
    
    public function toArray()
    {
        return [
            'name' => $this->name,
            'kind' => $this->kind,
            'status' => $this->status,
            'internal' => $this->isInternal,
            'optional' => $this->isOptional
        ];
    }

}