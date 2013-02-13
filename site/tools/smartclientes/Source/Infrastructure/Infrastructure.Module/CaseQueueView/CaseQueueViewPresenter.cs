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
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Microsoft.Practices.CompositeUI.SmartParts;
using Sante.EMR.SmartClient.Infrastructure.Data.Services;

namespace Sante.EMR.SmartClient.Infrastructure.Module
{
    public abstract partial class CaseQueueViewPresenter : Presenter<ICaseQueueView>
    {
        protected CaseQueue _caseQueue;
        private static int nextCaseQueueNumber = 1;
        protected IDataAccessService _db;

        public CaseQueueViewPresenter([ServiceDependency] IDataAccessService dataacessservice) 
        {
            _db = dataacessservice;
            _caseQueue = CreateCaseQueue();
           
        }
        /// <summary>
        /// This method is a placeholder that will be called by the view when it has been loaded.
        /// </summary>
        public override void OnViewReady()
        {
            View.BindToCaseQueue(_caseQueue);
            base.OnViewReady();
        }

        protected virtual CaseQueue CreateCaseQueue()
        {
            CaseQueue queue = _db.RetrieveCaseQueue(DataAccessType.All);
				

            //queue.Id = nextCaseQueueNumber++;

            //for (int i = 1; i <= 3; i++)
            //{
            //    Case item = new Case();
            //    item.CaseNumber = i;// "One Microsoft Way, Redmond, WA, US";
            //    item.DateClosed = DateTime.Today;
            //    item.DateOpened = DateTime.Today; // (short)i;
            //    item.NextTask = "Schedule UCC";
            //    item.NextTaskDate = DateTime.Today; // String.Format("Item {0} description", i);
            //    item.Status = Status.Active;
            //    queue.Add(item);
            //}

            return queue;

        }


        /// <summary>
        /// Close the view
        /// </summary>
        public void OnCloseView()
        {
            base.CloseView();
        }

        public virtual void Search()
        {
            System.Windows.Forms.MessageBox.Show("The method or operation is not implemented.");
        
        }

        public virtual void CreateNewCase()
        {
            System.Windows.Forms.MessageBox.Show("The method or operation is not implemented.");
        }

        public ISmartPartInfo GetSmartPartInfo(Type smartPartInfoType)
        {
            ISmartPartInfo result =
            (ISmartPartInfo)Activator.CreateInstance(smartPartInfoType);
            result.Title = "Case List";//String.Format("Case List #{0}", this._caseQueue.Id);
            //result.Description = "Select this to select and ship the next order from the queue.";

            return result;

        }
    }
}

