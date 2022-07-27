<?php

declare(strict_types=1);

namespace App\Service\Validation\Payload;

use App\Model\Model;

class Payload
{
    private $model;

    private array $errors = [];

    private array $validatorNames = [];

    public function reset(): Payload
    {
        $this->errors = [];
        $this->validatorNames = [];
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    public function addError($error): void
    {
        $this->errors[] = $error;
    }

    public function addValidatorName($name): void
    {
        $this->validatorNames[] = $name;
    }

    /**
     * @return array
     */
    public function getValidatorNames(): array
    {
        return $this->validatorNames;
    }
}
