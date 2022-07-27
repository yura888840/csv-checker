<?php

namespace App\Service\Validation\Pipeline\Stages;

use App\Service\Validation\Payload\Payload;

final class DeliveryTimeSample extends AbstractStage implements Stage
{
    protected function run(array $headers, array $row, array $rowData, Payload $payload): Payload
    {
        $status = '✅';
        if (
            isset($rowData['Delivery_time'])
            && (
                empty($rowData['Delivery_time'])
                || $rowData['Delivery_time'] === 'null'
            )
        ) {
            $status = '❌';
            $payload->addError('');
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
        return 'Column Delivery_time, if present, should not be null';
    }
}
