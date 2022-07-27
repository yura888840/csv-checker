<?php

declare(strict_types=1);

namespace App\Service\Validation\Pipeline;

use App\Service\Validation\Payload\Payload;
use League\Pipeline\Pipeline;

class PipelineValidator
{
    private Pipeline $pipeline;

    private bool $valid = true;
    /**
     * @var mixed
     */
    private $result;

    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function validate(Payload $payload): self
    {
        /** @var Payload $result */
        $this->result = $this->pipeline->process($payload);

        if (!$this->result->isValid()) {
            $this->valid = false;
        }

        return $this;
    }

    public function reset(): PipelineValidator
    {
        $this->valid = true;
        return $this;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getErrors()
    {
        return $this->result->getErrors();
    }
}
