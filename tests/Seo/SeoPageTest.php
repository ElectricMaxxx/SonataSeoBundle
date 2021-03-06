<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\SeoBundle\Tests\Seo;

use PHPUnit\Framework\TestCase;
use Sonata\SeoBundle\Seo\SeoPage;

class SeoPageTest extends TestCase
{
    public function testAddMeta(): void
    {
        $page = new SeoPage();
        $page->addMeta('property', 'foo', 'bar');

        $expected = [
            'http-equiv' => [],
            'name' => [],
            'schema' => [],
            'charset' => [],
            'property' => ['foo' => ['bar', []]],
        ];

        $this->assertEquals($expected, $page->getMetas());
    }

    public function testOverrideMetas(): void
    {
        $page = new SeoPage();
        $page->setMetas(['property' => ['foo' => 'bar', 'foo2' => ['bar2', []]]]);

        $expected = [
            'property' => ['foo' => ['bar', []], 'foo2' => ['bar2', []]],
        ];

        $this->assertEquals($expected, $page->getMetas());
    }

    public function testRemoveMeta(): void
    {
        $page = new SeoPage();
        $page->setMetas(['property' => ['foo' => 'bar', 'foo2' => ['bar2', []]]]);
        $page->removeMeta('property', 'foo');

        $this->assertFalse($page->hasMeta('property', 'foo'));
    }

    public function testInvalidMetas(): void
    {
        $this->expectException(\RuntimeException::class);

        $page = new SeoPage();
        $page->setMetas([
            'type' => 'not an array',
        ]);
    }

    public function testHtmlAttributes(): void
    {
        $page = new SeoPage();
        $page->setHtmlAttributes(['key1' => 'value1']);
        $page->addHtmlAttributes('key2', 'value2');

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $this->assertEquals($expected, $page->getHtmlAttributes());

        $this->assertTrue($page->hasHtmlAttribute('key2'));
        $page->removeHtmlAttributes('key2');
        $this->assertFalse($page->hasHtmlAttribute('key2'));
    }

    public function testHeadAttributes(): void
    {
        $page = new SeoPage();
        $page->setHeadAttributes(['head1' => 'value1']);
        $page->addHeadAttribute('head2', 'value2');

        $expected = [
            'head1' => 'value1',
            'head2' => 'value2',
        ];

        $this->assertEquals($expected, $page->getHeadAttributes());

        $this->assertTrue($page->hasHeadAttribute('head1'));
        $page->removeHeadAttribute('head1');
        $this->assertFalse($page->hasHeadAttribute('head1'));
    }

    public function testSetTitle(): void
    {
        $page = new SeoPage();
        $page->setTitle('My title');

        $this->assertEquals('My title', $page->getTitle());
    }

    public function testAddTitle(): void
    {
        $page = new SeoPage();
        $page->setTitle('My title');
        $page->setSeparator(' - ');
        $page->addTitle('Additional title');

        $this->assertEquals('Additional title - My title', $page->getTitle());
    }

    public function testLinkCanonical(): void
    {
        $page = new SeoPage();
        $page->setLinkCanonical('http://example.com');

        $this->assertEquals('http://example.com', $page->getLinkCanonical());

        $page->removeLinkCanonical();
        $this->assertEquals('', $page->getLinkCanonical());
    }

    public function testLangAlternates(): void
    {
        $page = new SeoPage();
        $page->setLangAlternates(['http://example.com/' => 'x-default']);
        $page->addLangAlternate('http://example.com/en-us', 'en-us');

        $expected = [
            'http://example.com/' => 'x-default',
            'http://example.com/en-us' => 'en-us',
        ];

        $this->assertEquals($expected, $page->getLangAlternates());

        $this->assertTrue($page->hasLangAlternate('http://example.com/'));
        $page->removeLangAlternate('http://example.com/');
        $this->assertFalse($page->hasLangAlternate('http://example.com/'));
    }

    /**
     * The hasMeta() should return true for a defined meta, false otherwise.
     */
    public function testHasMeta(): void
    {
        $page = new SeoPage();
        $page->addMeta('property', 'test', []);

        $this->assertTrue($page->hasMeta('property', 'test'));
        $this->assertFalse($page->hasMeta('property', 'fake'));
    }

    public function testSetSeparator(): void
    {
        $page = new SeoPage();

        $this->assertInstanceOf('Sonata\SeoBundle\Seo\SeoPage', $page->setSeparator('-'));
    }
}
