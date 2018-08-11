<?php

namespace Tests\Console\Commands;

use App\Console\Commands\CardInformation;
use App\Models\User;
use Mockery;
use Tests\TestCase;
use Carbon\Carbon;


class CardInformationTest extends TestCase
{
    public function testGetInformartion()
    {
        $command = Mockery::mock(CardInformation::class . '[ask]', function ($mock) {
            $mock->shouldReceive('ask')->andReturn('4523490000002634^JOSE C RIBEIRO^2012355564245|4523490000002634=2012355564245');
        });

        $this->assertTrue($command->handle());
    }

    public function testErrorTrackNotFound()
    {
        $command = Mockery::mock(CardInformation::class . '[ask]', function ($mock) {
            $mock->shouldReceive('ask')->andReturn('');
        });

        $this->assertFalse($command->handle());
    }

    public function testErrorInvalidDate()
    {
        $command = Mockery::mock(CardInformation::class . '[ask]', function ($mock) {          
            $mock->shouldReceive('ask')->andReturn('4523490000002634^JOSE C RIBEIRO^ABCD355564245|4523490000002634=ABCD355564245');
        });

        $this->assertFalse($command->handle());
    }

     public function testErrorExperiedDate()
    {
        $command = Mockery::mock(CardInformation::class . '[ask]', function ($mock) {
            $lastYear = Carbon::now()->subYears(1)->format('ym');            
            $mock->shouldReceive('ask')->andReturn('4523490000002634^JOSE C RIBEIRO^'.$lastYear.'355564245|4523490000002634='.$lastYear.'355564245');
        });

        $this->assertFalse($command->handle());
    }

    public function testErrorInvalidPan()
    {
        $command = Mockery::mock(CardInformation::class . '[ask]', function ($mock) {
            $mock->shouldReceive('ask')->andReturn('5523490000002634^JOSE C RIBEIRO^2012355564245|5523490000002634=2012355564245');
        });

        $this->assertFalse($command->handle());
    }

    public function testErrorEvenServiceNumber()
    {
        $command = Mockery::mock(CardInformation::class . '[ask]', function ($mock) {
            $mock->shouldReceive('ask')->andReturn('4523490000002634^JOSE C RIBEIRO^2012354564245|4523490000002634=2012354564245');
        });

        $this->assertFalse($command->handle());
    }

    public function testGetInformartionWithPinWithManyAttempts()
    {
        $command = Mockery::mock(CardInformation::class . '[ask]', function ($mock) {
            $mock->shouldReceive('ask')->once()->andReturn('4523490000002634^JOSE C RIBEIRO^2012355164245|4523490000002634=2012355164245');
            $mock->shouldReceive('ask')->once()->andReturn('TESTE');
            $mock->shouldReceive('ask')->once()->andReturn('123456');
            $mock->shouldReceive('ask')->once()->andReturn('1234');
        });

        $this->assertTrue($command->handle());
    }
}
