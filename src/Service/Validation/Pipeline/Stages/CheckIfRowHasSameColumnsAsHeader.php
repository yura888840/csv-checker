<?php

namespace App\Service\Validation\Pipeline\Stages;

use App\Service\Validation\Payload\Payload;

final class CheckIfRowHasSameColumnsAsHeader extends AbstractStage implements Stage
{
    protected function run(array $headers, array $row, array $rowData, Payload $payload): Payload
    {
        $status = '✅';
        if (count($row) !== count($headers)) {
            $payload->addError('Number of coumn does not header counter');
            $status = '❌';
        }
        $payload->addValidatorName($this->getName() . ' ' . $status);

        return $payload;
    }

    public function supports(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return 'Rows should contain the same number of column as specified in header';
    }
}
