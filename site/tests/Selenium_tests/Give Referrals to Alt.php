<?php

require_once '/usr/share/php/PHPUnit/Extensions/SeleniumTestCase.php';

class Give_Referrals_to_Alt extends PHPUnit_Extensions_SeleniumTestCase
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
        $this->click("link=Give Referrals to Alt");
        $this->waitForElementPresent("custom_197_-1");
        sleep(2);
        $this->click("custom_197_-1");
        $this->type("custom_197_-1", 'test comment');
        $this->select("status_id", 'label=Scheduled');
        $this->click("_qf_Activity_upload");

        $this->waitForElementPresent("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->click("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->waitForElementPresent("//*[contains (@id, 'custom_197')]");

             try
                {
                  sleep(10);

                $this->assertEquals("test comment", $this->getValue("//*[contains (@id, 'custom_197')]"));
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
        $this->waitForElementPresent("//*[contains (@name, 'custom_197')]");
        $this->waitForElementPresent("//*[contains(@id, 'custom_197')]");
        $this->type("//*[contains(@id, 'custom_197')]", 'test123');
        $this->select("status_id", 'label=Completed');
        $this->waitForElementPresent('status_id', "label=Completed");
        sleep(10);
        $this->click("_qf_Activity_upload");
        $this->waitForElementPresent("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->click("//*/table[@id='activities-selector']/*/tr[1]/td[8]/div/a[1]");
        $this->waitForElementPresent("//*[contains (@name, 'custom_197')]");
  
             try
                    {

                $this->assertEquals("test123", $this->getValue("//*[contains (@id, 'custom_197')]"));

                     }
            catch (PHPUnit_Framework_AssertionFailedError $e)
                        {
                array_push($this->verificationErrors, $e->toString());

                        }
               try
                {

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