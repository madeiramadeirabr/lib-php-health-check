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
        $this->processRunners();

        $dependencies = $this->cache->get($this->dependenciesKey);
        $basicInfo = $this->cache->get($this->basicInfoKey);

        return [
            'name' => $basicInfo['name'],
            'version' => $basicInfo['version'],
            'system' => $this->getSystemStatus(),
            'status' => $this->getHealthCheckStatus($dependencies),
            'timestamp' => date('Y-m-d H:i:s.u'),
            'dependencies' => $this->toArrayDependencies($dependencies)
        ];
    }

    private function processRunners()
    {
        $processedDependencies = [];
        $dependencies = $this->cache->get($this->dependenciesKey);

        foreach($dependencies as $dependency) {
            if ($dependency->getRunner()) {
                $dependency->setStatusByRunner();
            }

            $processedDependencies[] = $dependency;
        }

        $this->setDependencies($processedDependencies);
    }
    
    public function setHealthCheckBasicInfo(array $data)
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

        if (is_file('/proc/cpuinfo')) {
            $cpuInfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuInfo, $matches);
            $coreNumbers = count($matches[0]);
        }

        $cpuUtilization = sys_getloadavg()[0];

        return $cpuUtilization / $coreNumbers;
    }

    // adaptado de https://www.php.net/manual/en/function.memory-get-usage.php#120665
    private function getServerMemoryUsage()
        {
            $isWindows = stristr(PHP_OS, "win");
            if ($isWindows) {
                return $this->getWindowsMemoryUsage();
            } 
    
            return $this->getLinuxMemoryUsage();
    }
    
    private function getWindowsMemoryUsage()
    {

        $memory = [
            'total' => 0,
            'used' => 0
        ];

        // Get total physical memory (this is in bytes)
        $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
        @exec($cmd, $outputTotalPhysicalMemory);

        // Get free physical memory (this is in kibibytes!)
        $cmd = "wmic OS get FreePhysicalMemory";
        @exec($cmd, $outputFreePhysicalMemory);

        if ($outputTotalPhysicalMemory && $outputFreePhysicalMemory) {
            // Find total value
            foreach ($outputTotalPhysicalMemory as $line) {
                if ($line && preg_match("/^[0-9]+\$/", $line)) {
                    $memory['total'] = $line;
                    break;
                }
            }

            // Find free value
            foreach ($outputFreePhysicalMemory as $line) {
                if ($line && preg_match("/^[0-9]+\$/", $line)) {
                    $memory['used'] = $line;
                    break;
                }
            }
        }

        return $memory;
    }

    private function getLinuxMemoryUsage()
    {
        $memory = [
            'total' => 0,
            'used' => 0
        ];

        $total = 0;
        $free = 0;
        if (is_readable("/proc/meminfo")) {
            $stats = @file_get_contents("/proc/meminfo");

            if ($stats !== false) {
                $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                $stats = explode("\n", $stats);

                foreach ($stats as $statLine) {
                    $statLineData = explode(":", trim($statLine));
                    if (count($statLineData) == 2 && trim($statLineData[0]) == "MemTotal") {
                        $total = trim($statLineData[1]);
                        $total = explode(" ", $total);
                        $total = (int)$total[0];
                    }

                    if (count($statLineData) == 2 && trim($statLineData[0]) == "MemAvailable") {
                        $free = trim($statLineData[1]);
                        $free = explode(" ", $free);
                        $free = (int)$free[0];
                    }
                }
            }
        }
        
        $memory = [
            'total' => $total / 1000,
            'used' => ($total - $free) / 1000,
        ];

        return $memory;
    }


}
