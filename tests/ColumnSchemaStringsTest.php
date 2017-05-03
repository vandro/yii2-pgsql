<?php

namespace tigrov\tests\unit\pgsql;

use tigrov\pgsql\ColumnSchema;

class ColumnSchemaStringsTest extends AbstractColumnSchemaArrayTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->fixture = new ColumnSchema([
            'name' => 'strings',
            'allowNull' => true,
            'type' => 'string',
            'phpType' => 'string',
            'dbType' => 'varchar',
            'defaultValue' => NULL,
            'enumValues' => NULL,
            'size' => NULL,
            'precision' => NULL,
            'scale' => NULL,
            'isPrimaryKey' => false,
            'unsigned' => false,
            'comment' => NULL,
            'dimension' => 1,
            'delimiter' => ',',
        ]);
    }

    public function valuesProvider()
    {
        return [
            ['{""}', ['']],
            ['{"string1","str\\\\in\\"g2","str,ing3"}', ['string1','str\\in"g2','str,ing3']],
            ['{"null","NULL",NULL}', ['null','NULL',null]],
        ];
    }

    public function testAdditionalPhpTypecast()
    {
        $this->assertEquals(['string'], $this->fixture->phpTypecast('{string}'));
        $this->assertEquals(['string1', ',', 'string3'], $this->fixture->phpTypecast('{string1,",",string3}'));
    }
}