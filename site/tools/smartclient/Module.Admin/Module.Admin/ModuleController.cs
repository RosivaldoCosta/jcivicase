//----------------------------------------------------------------------------------------
// patterns & practices - Smart Client Software Factory - Guidance Package
//
// This file was generated by the "Add Business Module" recipe.
//
// This class contains placeholder methods for the common module initialization 
// tasks, such as adding services, or user-interface element
//
// For more information see: 
// ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-08-060-Add_Business_Module_Next_Steps.htm
//
// Latest version of this Guidance Package: http://go.microsoft.com/fwlink/?LinkId=62182
//----------------------------------------------------------------------------------------

using System;
using System.Windows.Forms;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Microsoft.Practices.CompositeUI;
using Microsoft.Practices.CompositeUI.Commands;
using Sante.EMR.SmartClient.Module.Admin.Constants;

namespace Sante.EMR.SmartClient.Module.Admin
{
    public class ModuleController : WorkItemController
    {
        public override void Run()
        {
            AddServices();
            ExtendMenu();
            ExtendToolStrip();
            AddViews();
        }

        private void AddServices()
        {
            //TODO: add services provided by the Module. See: Add or AddNew method in 
            //		WorkItem.Services collection or see ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.2005Nov.cab/CAB/html/03-020-Adding%20Services.htm
        }

        private void ExtendMenu()
        {
            //TODO: add menu items here, normally by calling the "Add" method on
            //		on the WorkItem.UIExtensionSites collection. For an example 
            //		See: ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-04-340-Showing_UIElements.htm
            
            ToolStripMenuItem item = new ToolStripMenuItem(CommandNames.RefreshData);
            WorkItem.Commands[CommandNames.RefreshData].AddInvoker(item, "Click");
            
            WorkItem.UIExtensionSites[UIExtensionSiteNames.FileMenu].Add(item);

            
        }

        [CommandHandler(CommandNames.RefreshData)]
        public void OnRefreshData(object sender, EventArgs e)
        {
            MessageBox.Show("Refresh Data invoked");
            //ShowViewInWorkspace<OPSView>(WorkspaceNames.PrimaryWorkspace);
            //int i = 0;
        }
        private void ExtendToolStrip()
        {
            //TODO: add new items to the ToolStrip in the Shell. See the UIExtensionSites collection in the WorkItem. 
            //		See: ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-04-340-Showing_UIElements.htm
        }

        private void AddViews()
        {
            //TODO: create the Module views, add them to the WorkItem and show them in 
            //		a Workspace. See: ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/03-01-040-How_to_Add_a_View_with_a_Presenter.htm

            // To create and add a view you can customize the following sentence
            // SampleView view = ShowViewInWorkspace<SampleView>(WorkspaceNames.SampleWorkspace);

        }

        //TODO: Add CommandHandlers and/or Event Subscriptions
        //		See: ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-04-350-Registering_Commands.htm
        //		See: ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-04-320-Publishing_and_Subscribing_to_Events.htm
    }
}
