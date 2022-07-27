<?php

namespace App\Service\Validation;

use App\Model\CSVRow;
use App\Service\Validation\Payload\Payload;
use App\Service\Validation\Pipeline\InvokablePipelineBuilderFactory;
use App\Service\Validation\Pipeline\PipelineValidator;
use RuntimeException;

class CsvFileValidationService
{
    private PipelineValidator $pipelineValidator;

    public function setPipelineValidator(PipelineValidator $pipelineValidator): CsvFileValidationService
    {
        $this->pipelineValidator = $pipelineValidator;
        return $this;
    }

    public function validateCSVFile(string $filePath): array
    {
        if (($handle = @fopen($filePath, 'rb')) === false) {
            throw new RuntimeException('Error opening data file');
        }

        $headers = [];
        $rows = [];
        $invalidRowsNums = [];
        $rowNumber = 0;

        $payload = new Payload();

        while (($row = fgetcsv($handle, 5000, ';')) !== false) {
            if (empty($headers)) {
                $headers = $row;

                continue;
            }

            $rowNumber++;
            $payload->reset()->setModel(new CSVRow($headers, $row));

            if (!$this->pipelineValidator->reset()->validate($payload)->isValid()) {
                $invalidRowsNums[] = $rowNumber;
            }

            $rows[] = $row;
        }

        return [
            //@todo error messages
            'headers' => $headers,
            'rows' => $rows,
            'invalid' => $invalidRowsNums,
            'invalidCount' => count($invalidRowsNums),
            'validations' => $payload->getValidatorNames(),
        ];
    }
}
