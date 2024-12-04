<?php

class CustomFieldOption
{
    private string $key;
    private string $value;
    private bool|null $isSelected;

    public function __construct(string $key, string $value, bool|null $isSelected = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->isSelected = $isSelected;
    }

    public function getKey()
    {
        return $this->key;
    }
    public function setKey($value): CustomFieldOption
    {
        $this->key = $value;
        return $this;
    }
    public function getValue(): string
    {
        return $this->value;
    }
    public function setValue($value): CustomFieldOption
    {
        $this->value = $value;
        return $this;
    }
    public function getIsSelected(): bool|null
    {
        return $this->isSelected;
    }
    public function setIsSelected($isSelected): CustomFieldOption
    {
        $this->isSelected = $isSelected;
        return $this;
    }
    public function __toString()
    {
        return $this->getKey() . " |" . $this->getValue();
    }
}