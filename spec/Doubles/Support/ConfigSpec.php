<?php

namespace spec\Humweb\SlackPipe\Doubles\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{
    function let() {
        $this->beConstructedWith('test-cfg');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Humweb\SlackPipe\Doubles\Support\Config');
    }

    function it_can_write_and_read_file()
    {
        $t = ['token' => '1234'];

        $this->write($t)->shouldNotBe(false);
        $this->clear();
        $this->getData()->shouldBe([]);
        $this->read()->shouldBe($t);
        $this->has('token')->shouldBe(true);
    }
}
