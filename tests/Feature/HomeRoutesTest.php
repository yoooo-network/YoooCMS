<?php

use CodeIgniter\Test\FeatureTestCase;

final class HomeRoutesTest extends FeatureTestCase
{
    public function testRootAndLocalizedHomePagesRenderLandingView(): void
    {
        $this->call('get', '/')->assertStatus(200)->assertSee('Search Engine for Male, Female, Trans & Gay Escorts.');
        $this->call('get', '/en')->assertStatus(200)->assertSee('Search Engine for Male, Female, Trans & Gay Escorts.');
        $this->call('get', '/hi')->assertStatus(200)->assertSee('Search Engine for Male, Female, Trans & Gay Escorts.');
    }
}
