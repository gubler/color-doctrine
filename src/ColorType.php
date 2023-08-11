<?php
/**
 * This file is part of the gubler/color-doctrine library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Daryl Gubler <http://dev88.co>
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @see https://packagist.org/packages/gubler/color-doctrine Packagist
 * @see https://github.com/gubler/color-doctrine GitHub
 */

namespace Gubler\Color\Doctrine;

use Gubler\Color\Color;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Field type mapping for the Doctrine Database Abstraction Layer (DBAL).
 *
 * Color fields will be stored as a string in the database and converted back to
 * the Color value object when querying.
 */
class ColorType extends Type
{
    /**
     * @var string
     */
    public const NAME = 'color';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Color
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Color) {
            return $value;
        }

        try {
            $color = new Color($value);
        } catch (\Exception) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $color;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Color) {
            return (string) $value;
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
