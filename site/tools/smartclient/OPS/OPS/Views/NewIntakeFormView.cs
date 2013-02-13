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
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Microsoft.Practices.CompositeUI;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.OPS.Constants;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Logger.Services;
using System.Reflection;
using Sante.EMR.SmartClient.MCT.Services;
 
namespace Sante.EMR.SmartClient.OPS
{
    public partial class NewIntakeFormView : UserControl, INewIntakeFormView
    {
        [EventPublication(EventTopicNames.SaveForm, PublicationScope.Global)]
        public event EventHandler<EventArgs> SaveForm;
    
        private Intake _form;
        private Case _case;
        private string _date;
        private string _time;
        private ILoggerService _logger;
        private IWorkflowStateService _state;

        public NewIntakeFormView([ServiceDependency] ILoggerService logger,
                                [ServiceDependency] IWorkflowStateService state)
        {
            InitializeComponent();
            _logger = logger;
            _state = state;
            _date = DateTime.Now.ToShortDateString();
            _time = DateTime.Now.ToShortTimeString();
            Date.Text = _date.ToString();
            Time.Text = _time.ToString();
            _form = new Intake(); //IntakeFormFactory.CreateIntakeForm();
            _form.Date = DateTime.Now;
            _form.Time = _time; //.ToString();
            _case = CaseFactory.CreateCase();
            
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
              
                _form.CaseNumber = Convert.ToInt32(this.UserGeneratedCaseNumber.Text);
                _form.Dob = this.Dob.Value;
                _form.tstamp = DateTime.Now;
                _form.Depart = _state.Depart;
                _form.Active = 1;
                _form.FormName = "Intake Form";
                _case.UserGeneratedCaseNumber = _form.CaseNumber;
                _case.Forms.Add(_form);
               
               
                _presenter.SaveCase(_case);
                _presenter.ShowMsg("The Intake form was successfully saved.");
                _presenter.OnCloseView();
            }
            catch (FormatException ex)
            {
                MessageBox.Show("Please enter a valid case number");
            }
        }
      
       
      
        protected virtual void OnSaveForm(EventArgs eventArgs)
        {
            if (SaveForm != null)
            {
                SaveForm(this, eventArgs);
            }
        }

    

        //private void GunType2_TextChanged(object sender, EventArgs e)
        //{
        //    this.GunType.Text = this.GunType2.Text;
        //}

        private void Financial_Unemployed_CheckedChanged(object sender, EventArgs e)
        {

        }
    }
}

