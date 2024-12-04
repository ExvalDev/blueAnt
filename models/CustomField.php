<?php

class CustomField
{
    private int $id;
    private string $name;
    private string $type;
    private array $options;
    private string|null $value;

    public function __construct(int $id, string $name, string $type, array $options = [], string|null $value = null)
    {

        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
        $this->value = $value;

    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId($id): CustomField
    {
        $this->id = $id;
        return $this;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): CustomField
    {
        $this->name = $name;
        return $this;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function setType(string $type): CustomField
    {
        $this->type = $type;
        return $this;
    }
    public function getOptions(): array
    {
        return $this->options;
    }
    public function setOptions(array $options): CustomField
    {
        $this->options = $options;
        return $this;
    }
    public function getValue(): string
    {
        return $this->value;
    }
    public function setValue(string $value): CustomField
    {
        $this->value = $value;
        return $this;
    }
    public function __toString(): string
    {
        return strval($this->id) . ' | ' . $this->name . ' | ' . $this->options;
    }

}