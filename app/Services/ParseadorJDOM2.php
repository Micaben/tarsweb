<?php
namespace App\Services;
class ParseadorJDOM2
{
    protected $dom;
    protected $root;

    public function __construct($rootElement, $namespace)
    {
        $this->dom = new \DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        $this->root = $this->dom->createElementNS($namespace, $rootElement);
        $this->dom->appendChild($this->root);
    }

    public function raizAdicionarNS($namespaces)
    {
        foreach ($namespaces as $prefix => $namespace) {
            $this->root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:' . $prefix, $namespace);
        }
    }

    public function crearElementoConNS($element, $value, $prefix)
    {
        $namespaceURI = $this->root->lookupNamespaceURI($prefix);
        $newElement = $this->dom->createElementNS($namespaceURI, $prefix . ':' . $element, $value);
        return $newElement;
    }

    public function adicionarHijo($parent, $child)
    {
        $parent->appendChild($child);
    }

    public function guardarXml($path)
    {
        $this->dom->save($path);
    }

    public function adicionarRaiz($elemento)
    {
        $this->root->appendChild($elemento);
    }
}