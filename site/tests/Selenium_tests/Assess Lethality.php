<?php

require_once '/usr/share/php/PHPUnit/Extensions/SeleniumTestCase.php';

class Assess_Lethality extends PHPUnit_Extensions_SeleniumTestCase
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
        $this->click("link=Assess Lethality");
        $this->waitForElementPresent("custom_182_-1");
        sleep(2);
        $this->click("custom_182_-1");
//        $this->click("ui-datepicker-month", 'label=May');
//        sleep(10);
//        $this->select("//div[@id='ui-datapicker-div']/div/div/select/option[5]");

        $this->click('//div[7]/div/a/span');
        $this->click("link=15");
        $this->type("custom_182_-1_time", '08:08');
        $this->type("custom_183_-1", 'test');
        $this->select("status_id", 'label=Scheduled');
        $this->click("_qf_Activity_upload");

        $this->waitForElementPresent("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->click("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->waitForElementPresent("//*/input[contains (@name, 'custom_182')]");
//       //  $all_input = $this->getAllFields();
//
            try
                {


                $this->assertEquals("07/15/2011", $this->getValue("//input[contains (@id, 'custom_182')]"));

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
            $this->assertEquals("08:08AM", $this->getValue("//*[contains(@id, 'custom_182') and contains(@id, 'time')]"));
                }

            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());

                }
             try
                {
                  sleep(10);

                $this->assertEquals("test", $this->getValue("//*[contains (@id, 'custom_183')]"));
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
        $this->waitForElementPresent("//*/input[contains (@name, 'custom_182')]");

        $this->click("//*[contains(@id, 'custom_182')]");
        $this->click("link=22");
       // $this->click("link=clear");
        $this->waitForElementPresent("//*[contains(@id, 'custom_182') and contains(@id, 'time')]");
        $this->type("//*[contains(@id, 'custom_182') and contains(@id, 'time')]", '12:18');
        $this->waitForElementPresent("//*[contains(@id, 'custom_183')]");
        $this->type("//*[contains(@id, 'custom_183')]", 'test123');
        $this->select("status_id", 'label=Completed');

        $this->waitForElementPresent('status_id', "label=Completed");
        sleep(10);
        $this->click("_qf_Activity_upload");
        $this->waitForElementPresent("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->click("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->waitForElementPresent("//*/input[contains (@name, 'custom_182')]");

            try
                {

                $this->assertEquals("07/22/2011", $this->getValue("//*[contains (@id, 'custom_182')]"));

                }
            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());

                }
            try
                {
                $this->assertEquals('12:18PM', $this->getValue("//*[contains(@id, 'custom_182') and contains(@id, 'time')]"));
                }

            catch (PHPUnit_Framework_AssertionFailedError $e)
                {

                array_push($this->verificationErrors, $e->toString());
                }


             try
                    {

                $this->assertEquals("test123", $this->getValue("//*[contains (@id, 'custom_183')]"));

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
