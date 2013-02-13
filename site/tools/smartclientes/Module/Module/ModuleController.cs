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
using Sante.EMR.SmartClient.Module.Constants;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.Module.Services;

namespace Sante.EMR.SmartClient.Module
{
    public class ModuleController : WorkItemController
    {
        ToolStripProgressBar _progressBar;

        public override void Run()
        {
            AddServices();
            ExtendMenu();
            ExtendToolStrip();
            AddViews();
            ExtendStatusStrip();
        }


        private void AddServices()
        {
           //WorkItem.Services.AddNew<ISerializationService>();
        }

        private void ExtendMenu()
        {
            //TODO: add menu items here, normally by calling the "Add" method on
            //		on the WorkItem.UIExtensionSites collection. For an example 
            //		See: ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-04-340-Showing_UIElements.htm
        }

        private void ExtendStatusStrip()
        {
            UIExtensionSite status = WorkItem.UIExtensionSites[UIExtensionSiteNames.MainStatus];
            _progressBar = new ToolStripProgressBar();
            _progressBar.Style = ProgressBarStyle.Marquee;
            _progressBar.Visible = false;
            status.Add(_progressBar);
        }


        private void ExtendToolStrip()
        {
            this.RegisterLaunchPoint(Properties.Resources.ModuleTitle, Properties.Resources.OutboxIcon, CommandNames.SaveForm);
        }

        private void AddViews()
        {
            //TODO: create the Module views, add them to the WorkItem and show them in 
            //		a Workspace. See: ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/03-01-040-How_to_Add_a_View_with_a_Presenter.htm

            // To create and add a view you can customize the following sentence
            // SampleView view = ShowViewInWorkspace<SampleView>(WorkspaceNames.SampleWorkspace);

        }

        [CommandHandler(CommandNames.SaveForm)]
        public void OnShowOutbox(object sender, EventArgs e)
        {

            ShowViewInWorkspace<OutboxView>(WorkspaceNames.PrimaryWorkspace);
        }

        [EventSubscription(EventTopicNames.LongProcess, ThreadOption.UserInterface)]
        public void OnLongProcessStarted(object sender, EventArgs<bool> eventArgs)
        {
            Cursor.Current = eventArgs.Data ? Cursors.WaitCursor : Cursors.Default;
            _progressBar.Visible = eventArgs.Data;
        }
    }
}
