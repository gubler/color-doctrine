<?php
namespace Gubler\Color\Doctrine;

use Doctrine\DBAL\Types\Type;
use Gubler\Color\Color;

class ColorTypeTest extends \PHPUnit_Framework_TestCase
{
    private $platform;
    /** @var ColorType */
    private $type;

    public static function setUpBeforeClass()
    {
        if (class_exists('Doctrine\\DBAL\\Types\\Type')) {
            Type::addType('color', 'Gubler\Color\Doctrine\ColorType');
        }
    }

    protected function setUp()
    {
        $this->platform = $this->getPlatformMock();
        $this->platform->expects($this->any())
            ->method('getGuidTypeDeclarationSQL')
            ->will($this->returnValue('DUMMYVARCHAR()'));

        $this->type = Type::getType('color');
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToDatabaseValue
     */
    public function testColorConvertsToDatabaseValue()
    {
        $color = new Color('#ffffff');

        $expected = 'rgba(255, 255, 255, 1)';
        $actual = $this->type->convertToDatabaseValue($color, $this->platform);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToDatabaseValue
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testInvalidColorConversionForDatabaseValue()
    {
        $this->type->convertToDatabaseValue('abcdefg', $this->platform);
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToDatabaseValue
     */
    public function testNullConversionForDatabaseValue()
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToPHPValue
     */
    public function testColorConvertsToPHPValue()
    {
        $color = $this->type->convertToPHPValue('rgba(255, 255, 255, 1.0)', $this->platform);
        $this->assertInstanceOf('Gubler\Color\Color', $color);
        $this->assertEquals('#FFFFFF', $color->hex());
        $this->assertEquals('rgba(255, 255, 255, 1)', (string) $color);
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToPHPValue
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testInvalidColorConversionForPHPValue()
    {
        $this->type->convertToPHPValue('abcdefg', $this->platform);
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToPHPValue
     */
    public function testNullConversionForPHPValue()
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::convertToPHPValue
     */
    public function testReturnValueIfColorForPHPValue()
    {
        $color = new Color('#ffffff');
        $this->assertSame($color, $this->type->convertToPHPValue($color, $this->platform));
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::getName
     */
    public function testGetName()
    {
        $this->assertEquals('color', $this->type->getName());
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::getSqlDeclaration
     */
    public function testGetGuidTypeDeclarationSQL()
    {
        $this->assertEquals('DUMMYVARCHAR()', $this->type->getSqlDeclaration(array('length' => 30), $this->platform));
    }

    /**
     * @covers \Gubler\Color\Doctrine\ColorType::requiresSQLCommentHint
     */
    public function testRequiresSQLCommentHint()
    {
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getPlatformMock()
    {
        return $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')
            ->setMethods(array('getGuidTypeDeclarationSQL'))
            ->getMockForAbstractClass();
    }
}
