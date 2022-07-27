<?php

namespace App\Service\Validation\Pipeline\Stages;

use App\Model\CSVRow;
use App\Service\Validation\Payload\Payload;

abstract class AbstractStage
{
    public function __invoke(Payload $payload): Payload
    {
        //$payload->addValidatorName($this->getName());
        /** @var CSVRow $mode */
        $model = $payload->getModel();

        $row = $model->getRow();
        $headers = $model->getHeaders();
        $rowData = array_combine($headers, $row);

        return $this->run($headers, $row, $rowData ,$payload);
    }

    abstract protected function run(array $headers, array $row, array $rowData, Payload $payload): Payload;

    abstract protected function getName(): string;
}
