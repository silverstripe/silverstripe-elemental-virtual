<?php

namespace SilverStripe\ElementalVirtual\Tests;

use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\Models\ElementalArea;
use DNADesign\Elemental\Tests\Src\TestElement;
use DNADesign\Elemental\Tests\Src\TestPage;
use SilverStripe\ElementalVirtual\Model\ElementVirtual;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;

class BaseElementExtensionTests extends SapphireTest
{
    protected static $fixture_file = 'BaseElementExtensionTest.yml';

    protected static $use_draft_site = true;

    protected static $extra_dataobjects = [
        TestElement::class,
        TestPage::class
    ];

    public function testVirtualElementAnchor()
    {
        Config::modify()->set(BaseElement::class, 'disable_pretty_anchor_name', true);

        $element = $this->objFromFixture(ElementVirtual::class, 'virtual1');
        $linked = $this->objFromFixture(TestElement::class, 'element1');

        $this->assertEquals('e'. $linked->ID, $element->getAnchor());
    }

    public function testUpdateCmsFields()
    {
        $linked = $this->objFromFixture(TestElement::class, 'element1');

        // should show that this element has virtual clones
        $list = $linked->getCMSFields()->dataFieldByName('VirtualClones')->getList();

        $this->assertEquals(1, $list->count());
        $this->assertEquals('test-page', $list->First()->LinkedElement()->getPage()->URLSegment);
    }
}
