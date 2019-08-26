<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        // Only add this line if you wish to use chrome driver installed in workspace container.
        // You might want to read the file path from env file.
        static::useChromedriver('/usr/local/bin/chromedriver');

        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

        if (env('USE_SELENIUM', 'false') == 'true') {
            return RemoteWebDriver::create(
                'http://selenium:4444/wd/hub', DesiredCapabilities::chrome()
            );
        } else {
            return RemoteWebDriver::create(
                'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
                )
            );
        }
    }
}

