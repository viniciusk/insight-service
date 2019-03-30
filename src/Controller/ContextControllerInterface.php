<?php

namespace InsightService\Controller;


interface ContextControllerInterface
{
    /**
     * @return Controller
     */
    public function getParentController(): Controller;
}
