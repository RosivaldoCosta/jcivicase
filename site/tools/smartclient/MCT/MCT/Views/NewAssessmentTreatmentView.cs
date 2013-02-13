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
    public partial class NewAssessmentTreatmentView : FormView, INewAssessmentTreatmentView
    {
        private IWorkflowStateService _state;
        private ILoggerService _logger;
        public NewAssessmentTreatmentView([ServiceDependency] ILoggerService logger,
            [ServiceDependency] IWorkflowStateService workflow)
        {
            InitializeComponent();
            _logger = logger;
            _state = workflow;
            _form = new MCTAssessmentAndTreatment();
            
            _form.Depart = _state.Depart;
            _form.FormName = "Assessment And Treatment";
            

        }

        protected override void OnLoad(EventArgs e)
        {
            _presenter.OnViewReady();
            base.OnLoad(e);
        }

        private void SaveToolStripButton_Click(object sender, EventArgs e)
        {
            try
            {
                _form.SetFormFields(this.Controls);
                _form.tstamp = DateTime.Now;
               

                _presenter.SaveForm(_form);
            }
            catch (Exception ex)
            {
                _logger.Write(ex.Message);
                _logger.Write(ex.StackTrace);

                MessageBox.Show("There was an error while saving the document. Please check the log");
            }

            _presenter.ShowMessage("Form was successfuly save");
            _presenter.OnCloseView();
        }
    }
}
