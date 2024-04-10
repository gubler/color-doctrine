<?php

namespace Gubler\Color\Doctrine\Test;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Gubler\Color\Color;
use Gubler\Color\Doctrine\ColorType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ColorType::class)]
class ColorTypeTest extends TestCase
{
    private AbstractPlatform&MockObject $platform;
    private ColorType $type;

    public static function setUpBeforeClass(): void
    {
        if (class_exists(Type::class)) {
            Type::addType('color', ColorType::class);
        }
    }

    protected function setUp(): void
    {
        $this->platform = $this->getPlatformMock();
        $this->platform
            ->method('getGuidTypeDeclarationSQL')
            ->willReturn('DUMMYVARCHAR()');

        /** @var ColorType $type */
        $type = Type::getType('color');
        $this->type = $type;
    }

    public function testColorConvertsToDatabaseValue(): void
    {
        $color = new Color('#ffffff');

        $expected = 'rgba(255, 255, 255, 1)';
        $actual = $this->type->convertToDatabaseValue($color, $this->platform);

        $this->assertEquals($expected, $actual);
    }

    public function testInvalidColorConversionForDatabaseValue(): void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToDatabaseValue('abcdefg', $this->platform);
    }

    public function testNullConversionForDatabaseValue(): void
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    public function testColorConvertsToPHPValue(): void
    {
        $color = $this->type->convertToPHPValue('rgba(255, 255, 255, 1.0)', $this->platform);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals('#FFFFFF', $color->hex());
        $this->assertEquals('rgba(255, 255, 255, 1)', (string) $color);
    }

    public function testInvalidColorConversionForPHPValue(): void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToPHPValue('abcdefg', $this->platform);
    }

    public function testNullConversionForPHPValue(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    public function testReturnValueIfColorForPHPValue(): void
    {
        $color = new Color('#ffffff');
        $this->assertSame($color, $this->type->convertToPHPValue($color, $this->platform));
    }

    public function testGetName(): void
    {
        $this->assertEquals('color', $this->type->getName());
    }

    public function testGetGuidTypeDeclarationSQL(): void
    {
        $this->assertEquals('DUMMYVARCHAR()', $this->type->getSqlDeclaration(['length' => 30], $this->platform));
    }

    public function testRequiresSQLCommentHint(): void
    {
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }

    private function getPlatformMock(): AbstractPlatform&MockObject
    {
        return $this->getMockBuilder(AbstractPlatform::class)
            ->getMock();
    }
}
