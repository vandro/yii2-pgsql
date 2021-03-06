yii2-pgsql
==============

Improved PostgreSQL schemas for Yii2.

Supports follow types for ActiveRecord models:
* `array`, issue [#7481](https://github.com/yiisoft/yii2/issues/7481)
* `json`, issue [#7481](https://github.com/yiisoft/yii2/issues/7481)
* [`composite`](docs/composite.md), https://www.postgresql.org/docs/current/static/rowtypes.html
* `domain`, https://www.postgresql.org/docs/current/static/sql-createdomain.html
* fixes type `bit`, issue [#7682](https://github.com/yiisoft/yii2/issues/7682)
* converts Postgres types `timestamp`, `date` and `time` to PHP type `\DateTime` and vice versa.

[![Latest Stable Version](https://poser.pugx.org/Tigrov/yii2-pgsql/v/stable)](https://packagist.org/packages/Tigrov/yii2-pgsql)
[![Build Status](https://travis-ci.org/Tigrov/yii2-pgsql.svg?branch=master)](https://travis-ci.org/Tigrov/yii2-pgsql)

Limitation
------------

When you use this extension you can't specify the PDO type by using an array: `[value, type]`,

e.g. `['name' => 'John', 'profile' => [$profile, \PDO::PARAM_LOB]]`.

See the issue [#7481](https://github.com/yiisoft/yii2/issues/7481)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist tigrov/yii2-pgsql
```

or add

```
"tigrov/yii2-pgsql": "~1.0"
```

to the require section of your `composer.json` file.

 
Configuration
-------------
Once the extension is installed, add following code to your application configuration:

```php
return [
    //...
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;dbname=<database>',
            'username' => 'postgres',
            'password' => '<password>',
            'schemaMap' => [
                'pgsql'=> 'tigrov\pgsql\Schema',
            ],
        ],
    ],
];
```

Specify the desired types for a table
```sql
CREATE TABLE public.model (
    id serial NOT NULL,
    attribute1 text[],
    attribute2 jsonb,
    attribute3 timestamp DEFAULT now(),
    CONSTRAINT model_pkey PRIMARY KEY (id)
);
```

Configure Model's rules
```php
/**
 * @property string[] $attribute1 array of string
 * @property array $attribute2 associative array or just array
 * @property integer|string|\DateTime $attribute3 for more information about the type see \Yii::$app->formatter->asDatetime()
 */
class Model extends ActiveRecord
{
    //...
    public function rules()
    {
        return [
            [['attribute1'], 'each', 'rule' => ['string']],
            [['attribute2', 'attribute3'], 'safe'],
        ];
    }
}
```
	
Usage
-----

You can then save array, json and timestamp types in database as follows:

```php
/**
 * @var ActiveRecord $model
 */
$model->attribute1 = ['some', 'values', 'of', 'array'];
$model->attribute2 = ['some' => 'values', 'of' => 'array'];
$model->attribute3 = new \DateTime('now');
$model->save();
```

and then use them in your code
```php
/**
 * @var ActiveRecord $model
 */
$model = Model::findOne($pk);
$model->attribute1; // is array
$model->attribute2; // is associative array (decoded json)
$model->attribute3; // is \DateTime
```

[Composite types](docs/composite.md)

License
-------

[MIT](LICENSE)
