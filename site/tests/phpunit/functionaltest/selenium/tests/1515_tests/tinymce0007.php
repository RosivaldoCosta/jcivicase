<?php
// testing issue 17438 - Tiny not working with extended & compressed
// test 6 cases: Functionality=Advanced, Extended, Simple with
// Compressed = Yes and No  

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class TinyMCE0007 extends PHPUnit_Extensions_SeleniumTestCase
{
  function setUp()
  {
  	$cfg = new SeleniumConfig();
    $this->setBrowser($cfg->browser);
    $this->setBrowserUrl($cfg->host.$cfg->path);
  }

  function testMyTestCase()
  {

  	print("Start tinymce0007.php." . "\n");
  	$cfg = new SeleniumConfig();
  	$this->open("administrator/index.php");
    print("Log in back end." . "\n");
    $this->open($cfg->path."administrator");
    $this->type("modlgn_username", $cfg->username);
    $this->type("modlgn_passwd", $cfg->password);
    $this->click("//input[@value='Login']");
    $this->waitForPageToLoad("30000");
    print("**Functionality=Advanced & Compressed = No" . "\n");
    print("Plugin manager->TinyMCE" . "\n");
    $this->click("link=Plugin Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb11");
    $this->click("//td[@id='toolbar-edit']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("paramscompressed0");
    $this->select("paramsmode", "label=Advanced");
    print("Save parameter changes" . "\n");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    print("Load Article Manager->New article" . "\n");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that advanced icons are shown" . "\n");
    $this->assertTrue($this->isElementPresent("//a[@id='text_bullist']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_code']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_hr']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_charmap']/span"));
    print("Check that extended icons NOT shown" . "\n");
    $this->assertFalse($this->isElementPresent("//a[@id='text_insertdate']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_inserttime']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_forecolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_backcolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_visualchars']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_nonbreaking']/span"));
    print("Cancel editor" . "\n");
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    
    print("**Functionality=Advanced & Compressed = Yes" . "\n");
    print("Plugin manager->TinyMCE" . "\n");
    $this->click("link=Plugin Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb11");
    $this->click("//td[@id='toolbar-edit']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("paramscompressed1");
    $this->select("paramsmode", "label=Advanced");
    print("Save parameter changes" . "\n");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    print("Load Article Manager->New article" . "\n");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that advanced icons are shown" . "\n");
    $this->assertTrue($this->isElementPresent("//a[@id='text_bullist']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_code']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_hr']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_charmap']/span"));
    print("Check that extended icons NOT shown" . "\n");
    $this->assertFalse($this->isElementPresent("//a[@id='text_insertdate']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_inserttime']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_forecolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_backcolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_visualchars']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_nonbreaking']/span"));
    print("Cancel editor" . "\n");
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    
    print("**Functionality=Extended, Compressed Version=No" . "\n");
    print("Back to Plugin Manager->TinyMCE" . "\n");
    $this->click("link=Plugin Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb11");
    $this->click("//td[@id='toolbar-edit']/a/span");
    $this->waitForPageToLoad("30000");
    $this->select("paramsmode", "label=Extended");
    $this->click("paramscompressed0");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    print("Article Manager->New" . "\n");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that advanced icons are shown" . "\n");
    $this->assertTrue($this->isElementPresent("//a[@id='text_bullist']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_code']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_hr']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_charmap']/span"));
    print("Check that extended toolbar icons are shown" . "\n");
    $this->assertTrue($this->isElementPresent("//a[@id='text_insertdate']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_inserttime']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_forecolor_action']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_backcolor_action']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_visualchars']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_nonbreaking']/span"));
    print("Cancel edit session" . "\n");
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");

    print("**Functionality=Extended, Compressed Version=Yes" . "\n");
    print("Plugin Manager->TinyMCE" . "\n");
    $this->click("link=Plugin Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb11");
    $this->click("//td[@id='toolbar-edit']/a/span");
    $this->waitForPageToLoad("30000");
    $this->select("paramsmode", "label=Extended");
    $this->click("paramscompressed1");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    print("Back to Article Manager->New" . "\n");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that advanced icons are shown" . "\n");
    $this->assertTrue($this->isElementPresent("//a[@id='text_bullist']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_code']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_hr']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_charmap']/span"));
    print("Check that extended/compressed toolbar icons are shown" . "\n");
    $this->assertTrue($this->isElementPresent("//a[@id='text_cut']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_copy']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_paste']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_pastetext']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_pasteword']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_selectall']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_forecolor_action']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_backcolor_action']/span"));    print("Cancel editor" . "\n");
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    
    print("**Functionality=Simple, Compressed Version=No" . "\n");
    print("Back to Plugin Manager->TinyMCE" . "\n");
    $this->click("link=Plugin Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb11");
    $this->click("//td[@id='toolbar-edit']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("paramscompressed0");
    $this->select("paramsmode", "label=Simple");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that advanced icons are NOT shown" . "\n");
    $this->assertFalse($this->isElementPresent("//a[@id='text_bullist']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_code']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_hr']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_charmap']/span"));
    print("Check that extended icons NOT shown" . "\n");
    $this->assertFalse($this->isElementPresent("//a[@id='text_insertdate']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_inserttime']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_forecolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_backcolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_visualchars']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_nonbreaking']/span"));
    $this->click("//td[@id='toolbar-cancel']/a/span");
	$this->waitForPageToLoad("30000");
    print("**Functionality=Simple, Compressed Version=Yes" . "\n");
    print("Plugin Manager->TinyMCE" . "\n");
    $this->click("link=Plugin Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb11");
    $this->click("//td[@id='toolbar-edit']/a/span");
    $this->waitForPageToLoad("30000");
    $this->select("paramsmode", "label=Simple");
    $this->click("paramscompressed1");
    $this->click("//td[@id='toolbar-save']/a/span");
	$this->waitForPageToLoad("30000");
    print("Article Manager->New" . "\n");
    $this->click("link=Article Manager");
	$this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
	$this->waitForPageToLoad("30000");
    print("Check that advanced icons NOT present" . "\n");
    $this->assertFalse($this->isElementPresent("//a[@id='text_bullist']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_code']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_hr']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_charmap']/span"));
    print("Check that extended icons NOT shown" . "\n");
    $this->assertFalse($this->isElementPresent("//a[@id='text_insertdate']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_inserttime']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_forecolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_backcolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_visualchars']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_nonbreaking']/span"));
    $this->click("//td[@id='toolbar-cancel']/a/span");
	$this->waitForPageToLoad("30000");
    
    print("**Restore default conditions" . "\n");
    print("Back to Plugin manager->TinyMCE" . "\n");
    $this->click("link=Plugin Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb11");
    $this->click("//td[@id='toolbar-edit']/a/span");
    $this->waitForPageToLoad("30000");
    print("Select compressed = No" . "\n");
    $this->click("paramscompressed0");
    print("Select mode = Advanced" . "\n");
    $this->select("paramsmode", "label=Advanced");
    print("Save parameter changes" . "\n");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that default conditions are set" . "\n");
    print("Load Article Manager->New article" . "\n");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that advanced icons are shown" . "\n");
    $this->assertTrue($this->isElementPresent("//a[@id='text_bullist']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_code']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_hr']/span"));
    $this->assertTrue($this->isElementPresent("//a[@id='text_charmap']/span"));
    print("Check that extended icons NOT shown" . "\n");
    $this->assertFalse($this->isElementPresent("//a[@id='text_insertdate']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_inserttime']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_forecolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_backcolor_action']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_visualchars']/span"));
    $this->assertFalse($this->isElementPresent("//a[@id='text_nonbreaking']/span"));
    print("Cancel editor" . "\n");
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    
    print("Logout" . "\n");
    $this->click("link=Logout");
    print("Finished tinymce0007.php." . "\n");
  }
}
?>