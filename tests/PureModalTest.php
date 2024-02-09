<?php

namespace LeKoala\PureModal\Test;

use LeKoala\PureModal\PureModal;
use LeKoala\PureModal\PureModalAction;
use SilverStripe\Control\Controller;
use SilverStripe\Dev\SapphireTest;

class PureModalTest extends SapphireTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $controller = Controller::curr();
        $controller->config()->set('url_segment', 'test_controller');
    }

    /**
     * Run some basic stuff to make sure we don't get obvious issues
     *
     * @return void
     */
    public function testPureModal(): void
    {
        $inst = new PureModal("test", "test title", "some content");
        $this->assertEquals($inst->getOverlayTriggersClose(), PureModal::getOverlayTriggersCloseConfig());
        $this->assertIsBool($inst->getOverlayTriggersClose());
        $this->assertArrayHasKey('onclick', $inst->getAttributes());
        $newContent = "updated content";
        $inst->setContent($newContent);
        $this->assertEquals($newContent, $inst->getContent());

        $render = (string)PureModal::renderDialog(Controller::curr());
        $this->assertIsString($render);

        $link = $inst->getControllerLink("testaction");
        $this->assertTrue(str_contains($link, 'testaction'));
    }

    /**
     * Run some basic stuff to make sure we don't get obvious issues
     *
     * @return void
     */
    public function testPureModalAction(): void
    {
        $inst = new PureModalAction("test", "test title");
        $this->assertIsBool($inst->getOverlayTriggersClose());
        $this->assertArrayHasKey('onclick', $inst->getAttributes());
    }
}
