//----------------------------------------------------------------------------------------
// patterns & practices - Smart Client Software Factory - Guidance Package
//
// This file was generated by the "Add View" recipe.
//
// A presenter calls methods of a view to update the information that the view displays. 
// The view exposes its methods through an interface definition, and the presenter contains
// a reference to the view interface. This allows you to test the presenter with different 
// implementations of a view (for example, a mock view).
//
// For more information see:
// ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-09-010-ModelViewPresenter_MVP.htm
//
// Latest version of this Guidance Package: http://go.microsoft.com/fwlink/?LinkId=62182
//----------------------------------------------------------------------------------------

using System;
using Microsoft.Practices.ObjectBuilder;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Infrastructure.Interface;

using Microsoft.Practices.CompositeUI.SmartParts;
using Sante.EMR.SmartClient.OPS.Constants;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.MCT.Services;

namespace Sante.EMR.SmartClient.OPS
{
    public partial class OPSViewPresenter : Presenter<IOPSView>
    {
        IWorkflowStateService _state;
         
        public OPSViewPresenter([ServiceDependency] IWorkflowStateService workflow)
        {
            _state = workflow;
        }

        /// <summary>
        /// This method is a placeholder that will be called by the view when it has been loaded.
        /// </summary>
        public override void OnViewReady()
        {
            base.OnViewReady();
            _state.Depart = "OPS";

        }

        /// <summary>
        /// Close the view
        /// </summary>
        public void OnCloseView()
        {
            base.CloseView();
        }

        public ISmartPartInfo GetSmartPartInfo(Type smartPartInfoType)
        {
            ISmartPartInfo result =
            (ISmartPartInfo)Activator.CreateInstance(smartPartInfoType);
            result.Title = Properties.Resources.ModuleTitle;//String.Format("Case List #{0}", this._caseQueue.Id);
            result.Description = "Select a OPS form to fill out.";

            return result;

        }

        /// <summary>
        /// 
        /// </summary>
        internal void ShowNewIntakeForm()
        {
            EventArgs<string> e = new EventArgs<string>(string.Empty);
            this.WorkItem.EventTopics[EventTopicNames.ShowIntakeForm].Fire(View, e, WorkItem, PublicationScope.WorkItem);
        }

        internal void ShowNewIntakeESForm()
        {
            EventArgs<string> e = new EventArgs<string>(string.Empty);
            this.WorkItem.EventTopics[EventTopicNames.ShowIntakeFormES].Fire(View, e, WorkItem, PublicationScope.WorkItem);
        }

        internal void ShowNewIntakePGForm()
        {
            EventArgs<string> e = new EventArgs<string>(string.Empty);
            this.WorkItem.EventTopics[EventTopicNames.ShowIntakeFormPG].Fire(View, e, WorkItem, PublicationScope.WorkItem);
        }

        internal void ShowNewLethalityForm()
        {
            EventArgs<string> e = new EventArgs<string>(string.Empty);
            this.WorkItem.EventTopics[EventTopicNames.ShowNewLethalityForm].Fire(View, e, WorkItem, PublicationScope.WorkItem);
        }

        internal void ShowNewContactForm()
        {
            EventArgs<string> e = new EventArgs<string>(string.Empty);
            this.WorkItem.EventTopics[EventTopicNames.ShowOPSTelephoneContact].Fire(View, e, WorkItem, PublicationScope.WorkItem);
        }
    }
}

