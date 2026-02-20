<?php
namespace TheWebsiteGuy\FormWizard\Classes;

use Schema;
use TheWebsiteGuy\FormWizard\Models\Record;

class UnreadRecords
{
    public static function getTotal()
    {
        if (Schema::hasTable('thewebsiteguy_formwizard_records')) {
            $unread = Record::where('unread', 1)->count();
        }

        return (isset($unread) && $unread > 0) ? $unread : null;
    }
}
