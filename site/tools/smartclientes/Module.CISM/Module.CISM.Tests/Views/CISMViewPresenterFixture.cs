using System;
using System.Text;
using System.Collections.Generic;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using Sante.EMR.SmartClient.Module.CISM;

namespace Sante.EMR.SmartClient.Module.CISM.Views
{
    /// <summary>
    /// Summary description for CISMViewPresenterTestFixture
    /// </summary>
    [TestClass]
    public class CISMViewPresenterTestFixture
    {
        public CISMViewPresenterTestFixture()
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

    class MockCISMView : ICISMView
    {

        #region ICaseQueueView Members

        public void BindToCaseQueue(Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.CaseQueue queue)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        public void ShowMessage(string message)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        public string Filter
        {
            get { throw new Exception("The method or operation is not implemented."); }
        }

        #endregion
    }
}

