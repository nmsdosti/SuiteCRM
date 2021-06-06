<?php

use SuiteCRM\Tests\SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

class SavedSearchTest extends SuitePHPUnitFrameworkTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        global $current_user;
        get_sugar_config_defaults();
        $current_user = BeanFactory::newBean('Users');
    }

    public function testSavedSearch()
    {
        // Execute the constructor and check for the Object type and  attributes
        $savedSearch = BeanFactory::newBean('SavedSearch');

        self::assertInstanceOf('SavedSearch', $savedSearch);
        self::assertInstanceOf('SugarBean', $savedSearch);

        self::assertAttributeEquals('saved_search', 'table_name', $savedSearch);
        self::assertAttributeEquals('SavedSearch', 'module_dir', $savedSearch);
        self::assertAttributeEquals('SavedSearch', 'object_name', $savedSearch);

        //test with parameters
        $savedSearch = new SavedSearch(array('id', 'name'), 'id', 'ASC');

        self::assertAttributeEquals(array('id', 'name'), 'columns', $savedSearch);
        self::assertAttributeEquals('id', 'orderBy', $savedSearch);
        self::assertAttributeEquals('ASC', 'sortOrder', $savedSearch);
    }

    public function testgetForm()
    {
        $result = (new SavedSearch(array('id', 'name'), 'id', 'ASC'))->getForm('Leads');

        self::assertGreaterThan(0, strlen($result));
    }

    public function testgetSelect()
    {
        $result = (new SavedSearch(array('id', 'name'), 'id', 'ASC'))->getSelect('Leads');

        self::assertGreaterThan(0, strlen($result));
    }

//    public function testMain()
//    {
//        $savedSearch = BeanFactory::newBean('SavedSearch');
//
//        $savedSearch->name = 'test';
//        $savedSearch->search_module = 'Leads';
//        $savedSearch->save();
//
//        //test for record ID to verify that record is saved
//        $this->assertTrue(isset($savedSearch->id));
//        $this->assertEquals(36, strlen($savedSearch->id));
//
//
//        // Where is the unit test?
//        // Where is the main method?
//        // Why is this combined?
//        // TODO: TASK: UNDEFINED - build the tests for the following methods.
//        $this->markTestIncomplete('');
    ////        //test handleSave method
    ////        $this->handleSaveAndRetrieveSavedSearch($savedSearch->id);
    ////
    ////        //test returnSavedSearch method
    ////        $this->returnSavedSearch($savedSearch->id);
    ////
    ////        //test returnSavedSearchContents method
    ////        $this->returnSavedSearchContents($savedSearch->id);
    ////
    ////        //test handleDelete method
    ////        $this->handleDelete($savedSearch->id);
//    }

    public function handleSaveAndRetrieveSavedSearch($id)
    {
        $savedSearch = BeanFactory::newBean('SavedSearch');
        $searchModuleBean = BeanFactory::newBean('Leads');

        $_REQUEST['search_module'] = 'Leads';
        $_REQUEST['description'] = 'test description';
        $_REQUEST['test_content'] = 'test text';

        $expected = array('search_module' => 'Leads', 'description' => 'test description', 'test_content' => 'test text', 'advanced' => true);

        //execute the method and then retrieve back to verify contents attribute
        $savedSearch->handleSave('', false, false, $id, $searchModuleBean);
        $savedSearch->retrieveSavedSearch($id);
        self::assertSame($expected, $savedSearch->contents);
    }

    public function handleDelete($id)
    {
        $savedSearch = BeanFactory::newBean('SavedSearch');

        $savedSearch->handleDelete($id);

        $result = $savedSearch->retrieve($id);
        self::assertEquals(null, $result);
    }

    public function returnSavedSearch($id)
    {
        $savedSearch = BeanFactory::newBean('SavedSearch');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $savedSearch->returnSavedSearch($id);
            self::assertTrue(true);
        } catch (Exception $e) {
            self::fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function returnSavedSearchContents($id)
    {
        $savedSearch = BeanFactory::newBean('SavedSearch');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $savedSearch->returnSavedSearchContents($id);
            self::assertTrue(true);
        } catch (Exception $e) {
            self::fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testhandleRedirect()
    {
        $savedSearch = BeanFactory::newBean('SavedSearch');

        $search_query = '&orderBy=&sortOrder=&query=&searchFormTab=&showSSDIV=';

        //$savedSearch->handleRedirect("Leads", $search_query, 1, 'true');
        self::markTestIncomplete('method uses die');
    }

    public function testfill_in_additional_list_fields()
    {
        $savedSearch = BeanFactory::newBean('SavedSearch');

        $savedSearch->assigned_user_id = 1;
        $savedSearch->contents = array('search_module' => 'Leads');

        $savedSearch->fill_in_additional_list_fields();

        self::assertEquals('Leads', $savedSearch->search_module);
        self::assertEquals('Administrator', $savedSearch->assigned_user_name);
    }

    public function testpopulateRequest()
    {
        $savedSearch = BeanFactory::newBean('SavedSearch');

        $savedSearch->contents = array('search_module' => 'Accounts',
                                        'description' => 'test text',
                                        'test_content' => 'some content',
                                        'advanced' => true, );

        $savedSearch->populateRequest();

        // verify that Request parameters are set
        self::assertEquals('Accounts', $_REQUEST['search_module']);
        self::assertEquals('test text', $_REQUEST['description']);
        self::assertEquals('some content', $_REQUEST['test_content']);
    }
}
