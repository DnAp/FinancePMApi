<?php


namespace DnAp\FinancePmApi\Parser;


use DnAp\FinancePmApi\Dto\Category;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\AbstractNode;

class CategoryListParser
{
    /**
     * @var Dom
     */
    protected $dom;

    public function __construct(string $html)
    {
        $this->dom = new Dom();
        $this->dom->loadStr($html);
    }

    /**
     * @return Category[]
     */
    public function parse()
    {
        $elements = $this->dom->find('.Content .CategoryLine');
        $categories = [];
        /** @var AbstractNode $element */
        foreach ($elements as $element) {
            /** @var AbstractNode $a */
            $a = $element->find('> .CategoryName a');
            $category = new Category();
            $category->name = $a->text;
            parse_str(parse_url($a->href, PHP_URL_QUERY), $prams);
            $category->id = (int)$prams['p_id'];
            $parent = $element->getParent();
            if ($parent->class === 'ExpandCollapseFolder') {
                preg_match('/[0-9]+$/', $parent->__get('id'), $matches);
                $category->parentId = (int)$matches[0];
            }

            $categories[] = $category;
        }
        return $categories;
    }
}