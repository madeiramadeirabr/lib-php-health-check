<?php

namespace MadeiraMadeira\HealthCheck\Infra\Repositories;

use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;

class HealthCheck implements HealthCheckRepository
{
    private $cache;

    private $dependenciesKey = 'dependencies';

    private $basicInfoKey = 'basic-info';

    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    public function setDependencies(array $dependencies) 
    {
        $this->cache->set($this->dependenciesKey, $dependencies);
    }

    public function setDependencyStatus(string $dependencyName, string $status)
    {
        $dependencies = $this->cache->get($this->dependenciesKey);
        
        foreach ($dependencies as $dependency) {
            if ($dependency->getName() == $dependencyName) {
                $dependency->setStatus($status);
            }
        }

        $this->cache->set($this->dependenciesKey, $dependencies);
    }

    public function getHealthCheck()
    {
        $basicInfo = $this->cache->get($this->basicInfoKey);
        $dependencies = $this->cache->get($this->dependenciesKey);
        $status = $this->getHealthCheckStatus($dependencies);
        $system = $this->getSystemStatus();
        
        return [
            'name' => $basicInfo['name'],
            'version' => $basicInfo['version'],
            'system' => $system,
            'status' => $status,
            'timestamp' => date('Y-m-d H:i:s.u'),
            'dependencies' => $this->toArrayDependencies($dependencies)
        ];
    }

    public function setHealthCheckBasicInfo($data)
    {
        $this->cache->set($this->basicInfoKey, $data);
    }

    private function toArrayDependencies(array $dependencies)
    {
        $dependenciesList = [];

        foreach ($dependencies as $dependency) {
            $dependenciesList[] = $dependency->toArray();
        }

        return $dependenciesList;
    }

    private function getHealthCheckStatus(array $dependencies)
    {
        $status = Status::getHealthyStatus();

        foreach ($dependencies as $dependency) {
            if ($dependency->getOptional()) {
                continue;
            }

            if ($dependency->getStatus() == Status::getUnavailiableStatus()) {
                $status = Status::getUnavailiableStatus();
                break;
            }

            if ($dependency->getStatus() == Status::getUnhealthyStatus()) {
                $status = Status::getUnhealthyStatus();
            }
        }

        return $status;
    }

    private function getSystemStatus()
    {
        $systemStatus = [
            'cpu' => [
                'utilization' => $this->getCPUUtilization()
            ],
            'memory' => $this->getServerMemoryUsage()
        ];

        return $systemStatus;
    }

    private function getCPUUtilization()
    {
        $coreNumbers = 1;

        if(is_file('/proc/cpuinfo')) {
            $cpuInfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuInfo, $matches);
            $coreNumbers = count($matches[0]);
        }
    
        $cpuUtilization = sys_getloadavg()[0];
        
        return $cpuUtilization / $coreNumbers;
    }

    private function getServerMemoryUsage()
    {
        $free = shell_exec('free --mega');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
    
        $memory = explode(" ", $free_arr[1]);
        $memory = array_filter($memory);
        $memory = array_merge($memory);
    
        return [
            'total' => (int)$memory[1],
            'used' => (int)$memory[2]
        ];
    }
}
