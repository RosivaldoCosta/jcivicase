using System;
using System.Text;
using System.Collections.Generic;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using Sante.EMR.SmartClient.Module.OPS;

namespace Sante.EMR.SmartClient.Module.OPS.Views
{
    /// <summary>
    /// Summary description for OPSCaseQueueViewPresenterTestFixture
    /// </summary>
    [TestClass]
    public class OPSCaseQueueViewPresenterTestFixture
    {
        public OPSCaseQueueViewPresenterTestFixture()
        {
        }

        #region Additional test attributes
        //
        // You can use the following additional attributes as you write your tests:
        //
        // Use ClassInitialize to run code before running the first test in the class
        // [ClassInitialize()]
        // public static void MyClassInitialize(TestContext testContext) { }
        //
        // Use ClassCleanup to run code after all tests in a class have run
        // [ClassCleanup()]
        // public static void MyClassCleanup() { }
        //
        // Use TestInitialize to run code before running each test 
        // [TestInitialize()]
        // public void MyTestInitialize() { }
        //
        // Use TestCleanup to run code after each test has run
        // [TestCleanup()]
        // public void MyTestCleanup() { }
        //
        #endregion
    }

    class MockOPSCaseQueueView 
    {

        #region IOPSCaseQueueView Members

      
        #endregion

        #region IOPSCaseQueueView Members


        public void ShowMessage(string message)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        #endregion

        #region IOPSCaseQueueView Members


        public string Filter
        {
            get { throw new Exception("The method or operation is not implemented."); }
        }

        #endregion
    }
}

