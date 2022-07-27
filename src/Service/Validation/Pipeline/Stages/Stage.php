<?php

namespace App\Service\Validation\Pipeline\Stages;

use App\Service\Validation\Payload\Payload;

interface Stage
{
    public function __invoke(Payload $payload);

    public function supports(): bool;

    public function getName(): string;
}
