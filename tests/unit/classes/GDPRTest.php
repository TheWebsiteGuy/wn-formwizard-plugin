<?php
namespace TheWebsiteGuy\FormWizard\Tests\Classes;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TheWebsiteGuy\FormWizard\Classes\GDPR;
use TheWebsiteGuy\FormWizard\Models\Record;
use TheWebsiteGuy\FormWizard\Models\Settings;
use System\Classes\PluginManager;
use System\Tests\Bootstrap\PluginTestCase;

class GDPRTest extends PluginTestCase
{

    // use RefreshDatabase;

    private $_record;

    public function setUp(): void
    {
        parent::setUp();
        PluginManager::instance()->bootAll(true);
        Record::unguard();
        $this->_record = Record::create([
            'group' => 'test group',
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20),
        ]);
    }

    /**
     * @testdox GDPR cleanup disabled
     */
    public function testCleanRecordsDisabled()
    {
        Settings::set([
            'gdpr_enable' => false,
            'gdpr_days' => 10,
        ]);
        $gdpr = new GDPR();
        $gdpr->cleanRecords();
        $totalRecords = Record::all()->count();
        $this->assertEquals(1, $totalRecords);
    }

    /**
     * @testdox GDPR cleanup with older records
     */
    public function testCleanRecordsWithOlder()
    {
        Settings::set([
            'gdpr_enable' => true,
            'gdpr_days' => 10,
        ]);
        $gdpr = new GDPR();
        $gdpr->cleanRecords();
        $totalRecords = Record::all()->count();
        $this->assertEquals(0, $totalRecords);
    }

    /**
     * @testdox GDPR cleanup without older records
     */
    public function testCleanRecordsWithoutOlder()
    {
        Settings::set([
            'gdpr_enable' => true,
            'gdpr_days' => 30,
        ]);
        $gdpr = new GDPR();
        $gdpr->cleanRecords();
        $totalRecords = Record::all()->count();
        $this->assertEquals(1, $totalRecords);
    }

    /**
     * @testdox GDPR cleanup with invalid days parameter
     */
    public function testCleanRecordsInvalidDays()
    {
        $this->expectException(\October\Rain\Database\ModelException::class);
        Settings::set([
            'gdpr_enable' => true,
            'gdpr_days' => 'INVALID',
        ]);
    }

}
