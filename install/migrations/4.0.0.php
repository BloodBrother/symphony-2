<?php

/**
 * @package install
 */
final class migration_400 extends Migration
{
    public function getVersion()
    {
        return '4.0.0';
    }

    public function preUpdateNotes()
    {
        return [
            'Symphony <code>4.0.0</code> is a major release and breaks some APIs. ' .
            'Most of the changes are about the UI, so while extension keeps working, ' .
            'their look and feel might not be on par.',
        ];
    }
}
