<?php

namespace App\Service\Validation\Pipeline\Stages;

use App\Service\Validation\Payload\Payload;

final class ShippingTypeExample extends AbstractStage implements Stage
{

    protected function run(array $headers, array $row, array $rowData, Payload $payload): Payload
    {
        if (isset($rowData['Shipping_type']) && $rowData['Shipping_type'] === 'paket') {
            $payload->addError('');

            $status = '❌';
        } else {
            $status = '✅';
        }
        $payload->addValidatorName(sprintf('%s %s', $this->getName(), $status));

        return $payload;
    }

    public function supports(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return 'Shipping_type, if present, should not be paket';
    }
}
