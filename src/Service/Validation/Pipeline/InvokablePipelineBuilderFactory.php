<?php

namespace App\Service\Validation\Pipeline;

use App\Service\Validation\Pipeline\Stages\Stage;
use League\Pipeline\PipelineBuilder;
use League\Pipeline\PipelineInterface;

class InvokablePipelineBuilderFactory
{
    private iterable $stages;

    public function __construct(iterable $stages)
    {
        $this->stages = $stages;
    }

    public function buildPipe(): PipelineInterface
    {
        $pipelineBuilder = new PipelineBuilder();

        /** @var Stage $stage */
        foreach ($this->stages as $stage) {
            $pipelineBuilder->add($stage);
            $pipelineBuilder->add($stage);
        }

        return $pipelineBuilder->build();
    }

    public function __invoke(): PipelineInterface
    {
        return $this->buildPipe();
    }
}
