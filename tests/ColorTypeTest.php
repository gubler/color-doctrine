<?php

namespace Gubler\Color\Doctrine\Test;

use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Gubler\Color\Color;
use Gubler\Color\Doctrine\ColorType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ColorTypeTest extends TestCase
{
    private MockObject $platform;
    private ColorType|Type $type;

    public static function setUpBeforeClass(): void
    {
        if (class_exists(Type::class)) {
            Type::addType('color', ColorType::class);
        }
    }

    protected function setUp(): void
    {
        $this->platform = $this->getPlatformMock();
        $this->platform->expects($this->any())
            ->method('getGuidTypeDeclarationSQL')
            ->willReturn('DUMMYVARCHAR()');

        $this->type = Type::getType('color');
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToDatabaseValue
     */
    public function testColorConvertsToDatabaseValue(): void
    {
        $color = new Color('#ffffff');

        $expected = 'rgba(255, 255, 255, 1)';
        $actual = $this->type->convertToDatabaseValue($color, $this->platform);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToDatabaseValue
     */
    public function testInvalidColorConversionForDatabaseValue(): void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToDatabaseValue('abcdefg', $this->platform);
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToDatabaseValue
     */
    public function testNullConversionForDatabaseValue(): void
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToPHPValue
     */
    public function testColorConvertsToPHPValue(): void
    {
        $color = $this->type->convertToPHPValue('rgba(255, 255, 255, 1.0)', $this->platform);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals('#FFFFFF', $color->hex());
        $this->assertEquals('rgba(255, 255, 255, 1)', (string) $color);
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToPHPValue
     */
    public function testInvalidColorConversionForPHPValue(): void
    {
        $this->expectException(ConversionException::class);
        $this->type->convertToPHPValue('abcdefg', $this->platform);
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToPHPValue
     */
    public function testNullConversionForPHPValue(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToPHPValue
     */
    public function testReturnValueIfColorForPHPValue(): void
    {
        $color = new Color('#ffffff');
        $this->assertSame($color, $this->type->convertToPHPValue($color, $this->platform));
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::getName
     */
    public function testGetName(): void
    {
        $this->assertEquals('color', $this->type->getName());
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::getSqlDeclaration
     */
    public function testGetGuidTypeDeclarationSQL(): void
    {
        $this->assertEquals('DUMMYVARCHAR()', $this->type->getSqlDeclaration(array('length' => 30), $this->platform));
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::requiresSQLCommentHint
     */
    public function testRequiresSQLCommentHint(): void
    {
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }

    private function getPlatformMock(): MockObject
    {
        return $this->getMockBuilder(AbstractPlatform::class)
            ->setMethods(array('getGuidTypeDeclarationSQL'))
            ->getMockForAbstractClass();
    }
}
