# gubler/color-doctrine

The gubler/color-doctrine package provides the ability to use
[gubler/color][gubler-color] as a [Doctrine field type][doctrine-field-type].

## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. Run
the following command to install the package and add it as a requirement to
your project's `composer.json`:

```bash
composer require gubler/color-doctrine
```

## Examples

To configure Doctrine to use gubler/color as a field type, you'll need to set up
the following in your bootstrap:

``` php
\Doctrine\DBAL\Types\Type::addType('color', 'Gubler\Color\Doctrine\ColorType');
```

In Symfony:
 ``` yaml
# app/config/config.yml
doctrine:
    dbal:
        types:
            color: Gubler\Color\Doctrine\ColorType
```

Then, in your models, you may annotate properties by setting the `@Column`
type to `color`. Doctrine will handle the rest.

``` php
/**
 * @Entity
 * @Table(name="label")
 */
class label
{
    /**
     * @var \Gubler\Color\Color
     *
     * @Column(type="color")
     */
    protected $color;

    public function getColor(): \Gubler\Color\Color
    {
        return $this->color;
    }
}
```

If you use the XML Mapping instead of PHP annotations.

``` XML
<color name="color" column="color" type="color"/>
```

### More Information

For more information on getting started with Doctrine, check out the "[Getting
Started with Doctrine][doctrine-getting-started]" tutorial.

## Thanks

Huge thanks to [ramsey/uuid-doctrine](https://github.com/ramsey/uuid-doctrine).
This project is based off of that package.

## Copyright and License

The gubler/color-doctrine library is copyright © [Daryl Gubler](https://dev88.co/) and
licensed for use under the MIT License (MIT). Please see [LICENSE][] for more
information.


[gubler-color]: https://github.com/gubler/color
[doctrine-field-type]: http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html
[packagist]: https://packagist.org/packages/gubler/color-doctrine
[composer]: http://getcomposer.org/
