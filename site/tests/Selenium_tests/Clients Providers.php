<?php

require_once '/usr/share/php/PHPUnit/Extensions/SeleniumTestCase.php';

class Clients_Providers extends PHPUnit_Extensions_SeleniumTestCase
{
    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '/home/denis/calculator/selenium_test/Error_screan/Screan';
    protected $screenshotUrl = 'http://localhost/screenshots';



    public function setUp()
    {
        $this->setBrowser('*googlechrome');
        $this->setBrowserUrl('http://ehr.santegroup.org/');

    }
  public function testcharlik()
    {
        $this->open("/staging/administrator/index.php");
        $this->type("username", 'bwt');
        $this->type("passwd",'p@ssw0rd');
        $this->click("css=input[type=submit]");
        $this->waitForElementPresent("APmenu-SearchClients");
        $this->click("APmenu-SearchClients");
        $this->waitForElementPresent("_qf_Custom_refresh");
        sleep(2);
        $this->type("sort_name",'topko');
        $this->click("_qf_Custom_refresh");
        $this->waitForElementPresent("link=700");
        $this->click("link=700");
        $this->waitForElementPresent("css=#tasks_show > a > img.action-icon");
        $this->click("css=#tasks_show > a > img.action-icon");
        $this->click("link=Client's Providers");
        $this->waitForElementPresent("custom_198_-1");
        sleep(2);
        $this->click("custom_198_-1");
        $this->click('//div[7]/div/a/span');
        $this->click("link=13");
        $this->type("custom_198_-1_time", '11:11');
        $this->type("custom_199_-1", 'ytrewq');
        $this->select("status_id", 'label=Scheduled');
        $this->click("_qf_Activity_upload");

        $this->waitForElementPresent("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->click("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->waitForElementPresent("//*/input[contains (@name, 'custom_198')]");
       //  $all_input = $this->getAllFields();

            try
                {


                $this->assertEquals("07/13/2011", $this->getValue("//input[contains (@id, 'custom_198')]"));

                }
            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());

                }
//    foreach ($all_input as $v)
//        {
//                if (preg_match("/custom_198_([0-9]*)_time/", $v, $match))
//           {
//            try
//                {
//
//                $this->assertEquals("11:11AM", $this->getValue("//*/input[@id='$match[0]']"));
//
//                }


             try
                {
            $this->assertEquals("11:11AM", $this->getValue("//*[contains(@id, 'custom_198') and contains(@id, 'time')]"));
                }

            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());

                }
             try
                {
                  sleep(10);

                $this->assertEquals("ytrewq", $this->getValue("//*[contains (@id, 'custom_199')]"));
        sleep(10);
                }
            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());

                }
                 try
                {
                  sleep(10);

                $this->assertEquals("9", $this->getValue("name=status_id"));
         sleep(10);
                }
            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());

                }

        sleep(10);
        $this->click("_qf_Activity_cancel");
        sleep(10);
        $this->waitForElementPresent("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->click("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->waitForElementPresent("//*/input[contains (@name, 'custom_198')]");

        $this->click("//*[contains(@id, 'custom_198')]");
        $this->click("link=20");
       // $this->click("link=clear");
        $this->waitForElementPresent("//*[contains(@id, 'custom_198') and contains(@id, 'time')]");
        $this->type("//*[contains(@id, 'custom_198') and contains(@id, 'time')]", '03:25');
        $this->waitForElementPresent("//*[contains(@id, 'custom_199')]");
        $this->type("//*[contains(@id, 'custom_199')]", 'qwerty');
        $this->select("status_id", 'label=Completed');

        $this->waitForElementPresent('status_id', "label=Completed");
        sleep(10);
        $this->click("_qf_Activity_upload");
        $this->waitForElementPresent("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
         $this->click("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->waitForElementPresent("//*/input[contains (@name, 'custom_198')]");

            try
                {

                $this->assertEquals("07/20/2011", $this->getValue("//*[contains (@id, 'custom_198')]"));

                }
            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());

                }
            try
                {
                $this->assertEquals('03:25AM', $this->getValue("//*[contains(@id, 'custom_198') and contains(@id, 'time')]"));
                }

            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());
                }


             try
                    {

                $this->assertEquals("qwerty", $this->getValue("//*[contains (@id, 'custom_199')]"));

                     }
            catch (PHPUnit_Framework_AssertionFailedError $e)
                        {
                array_push($this->verificationErrors, $e->toString());

                        }
               try
                {
         sleep(10);

                $this->assertEquals("2", $this->getValue("name=status_id"));
         sleep(10);
                }
            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());

                }
         sleep(10);
        $this->click("_qf_Activity_cancel");
         sleep(10);
        $this->waitForElementPresent("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");

         }
}

