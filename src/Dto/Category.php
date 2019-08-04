<?php


namespace DnAp\FinancePmApi\Dto;


class Category
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var int|null */
    public $parentId;

    public function __toString()
    {
        return $this->id . ' ' . $this->name . ($this->parentId !== null ? ' > ' . $this->parentId : '');
    }


}