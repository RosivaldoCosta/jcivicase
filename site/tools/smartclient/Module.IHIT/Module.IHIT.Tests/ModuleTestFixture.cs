using Microsoft.VisualStudio.TestTools.UnitTesting;
using System;
using System.Text;
using System.Collections.Generic;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using System.Collections;
using Sante.EMR.SmartClient.Module.IHIT.Tests.Support;

namespace Sante.EMR.SmartClient.Module.IHIT.Tests
{
    /// <summary>
    /// Summary description for ModuleTestFixture
    /// </summary>
    [TestClass]
    public class ModuleTestFixture
    {

        [TestMethod]
        public void OnLoadCreateModuleController()
        {
            TestableRootWorkItem rootWorkItem = new TestableRootWorkItem();
            Module moduleInitializer = new Module(rootWorkItem);

            moduleInitializer.Load();

            ICollection<ControlledWorkItem<ModuleController>> controllers =
                rootWorkItem.WorkItems.FindByType<ControlledWorkItem<ModuleController>>();
            Assert.AreEqual(1, controllers.Count);
        }
    }
}
