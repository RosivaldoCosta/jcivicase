//----------------------------------------------------------------------------------------
// patterns & practices - Smart Client Software Factory - Guidance Package
//
// This file was generated by the "Add View" recipe.
//
// This class is the concrete implementation of a View in the Model-View-Presenter 
// pattern. Communication between the Presenter and this class is acheived through 
// an interface to facilitate separation and testability.
// Note that the Presenter generated by the same recipe, will automatically be created
// by CAB through [CreateNew] and bidirectional references will be added.
//
// For more information see:
// ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-09-010-ModelViewPresenter_MVP.htm
//
// Latest version of this Guidance Package: http://go.microsoft.com/fwlink/?LinkId=62182
//----------------------------------------------------------------------------------------

using System;
using System.Windows.Forms;
using Microsoft.Practices.CompositeUI.SmartParts;
using Microsoft.Practices.ObjectBuilder;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Sante.EMR.SmartClient.Infrastructure.Interface.View;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.MCT.Services;
using Sante.EMR.SmartClient.Infrastructure.Logger.Services;

namespace Sante.EMR.SmartClient.MCT
{
    public partial class MCTDispatchViewES : FormView, IMCTDispatchView
    {
        IWorkflowStateService _state;
        ILoggerService _logger;
        
        public MCTDispatchViewES([ServiceDependency] ILoggerService logger,
                                [ServiceDependency] IWorkflowStateService workflow)
        {
            InitializeComponent();
            _form = new MCTDispatch();
            _logger = logger;
            _state = workflow;
            _form.Depart = _state.Depart;
            _form.FormName = "MCT Dispatch";
            this.Date.Text = DateTime.Now.ToShortDateString();
            this.Time.Text = DateTime.Now.ToShortTimeString();
            
        }

        protected override void OnLoad(EventArgs e)
        {
            _presenter.OnViewReady();
            base.OnLoad(e);
        }

        private void SaveToolStripButton_Click(object sender, EventArgs e)
        {
            _form.tstamp = DateTime.Now;

            try
            {
                _form.SetFormFields(this.Controls);

                _presenter.SaveForm(_form);
                _presenter.ShowMsg("The form was successfully saved.");
                _presenter.OnCloseView();
            }
            catch (Exception ex)
            {
                _logger.Write(ex.Message);
                _logger.Write(ex.StackTrace);
                throw ex;
            }


        }
                
        private void tottime()
        {
            int hours;
            if (ArrivalDate.Value.Date == DispatchDate.Value.Date)
            {
                hours = (ArrivalTime.Value.Hour - DispatchTime.Value.Hour);
            }
            else
            {
                hours = Convert.ToInt32(((ArrivalDate.Value.Date.AddHours(ArrivalTime.Value.Hour)) - (DispatchDate.Value.Date.AddHours(DispatchTime.Value.Hour))).TotalHours);
            }
            if (hours < 0)
            {
                TotalTime.Text = "N/A";
            }
            else 
            {
                TotalTime.Text = hours.ToString() + ":00:00";
            }
        }

        private void DispatchDate_ValueChanged(object sender, EventArgs e)
        {
            tottime();
        }
        private void ArrivalDate_ValueChanged(object sender, EventArgs e)
        {
            tottime();
        }
        private void DispatchTime_ValueChanged(object sender, EventArgs e)
        {
            tottime();
        }
        private void ArrivalTime_ValueChanged(object sender, EventArgs e)
        {
            tottime();
        }
    }
}

