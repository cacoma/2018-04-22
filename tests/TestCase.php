<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function sign_in_htaccess()
    {
        $this->get('https://cacoma:pascal@cacoma.tk');
    }

    public function log_in_user()   
    {
        $this->be($user = factory('App\User')->create());
    }
}
