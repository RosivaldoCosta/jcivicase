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
using Sante.EMR.SmartClient.Infrastructure.Interface.Constants;

namespace Sante.EMR.SmartClient.UCC
{
    public partial class UCCView : UserControl, IUCCView, ISmartPartInfoProvider
    {
        public UCCView()
        {
            InitializeComponent();
        }

        protected override void OnLoad(EventArgs e)
        {
            _presenter.OnViewReady();
            base.OnLoad(e);
        }

        public ISmartPartInfo GetSmartPartInfo(Type smartPartInfoType)
        {
            return _presenter.GetSmartPartInfo(smartPartInfoType);// throw new Exception("The method or operation is not implemented.");
        }

        private void UCCView_Load(object sender, EventArgs e)
        {
            CenterButtons(this.Controls);


        }

        private void CenterButtons(ControlCollection controlCollection)
        {
            int result;

            int Y = Coordinates.LocationY;
            foreach (Control c in controlCollection)
            {

                if (c is Button)
                {
                    Button b = (Button)c;
                    int newX = Math.DivRem((Parent.Width - b.Width), 2, out result);

                    b.Location = new System.Drawing.Point(newX, Y);
                    Y = Y + b.Height;
                }
                else
                {
                    CenterButtons(c.Controls);
                }

            }

        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void AssesTreatmentButton_Click(object sender, EventArgs e)
        {
            _presenter.ShowFormWithNoCaseSelection(EventTopicNames.ShowNewAssessmentTreatmentUCC);
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void MCTDispatchButton_Click(object sender, EventArgs e)
        {
            _presenter.ShowFormWithNoCaseSelection(EventTopicNames.ShowMedicalEval);
        }


        /// <summary>
        /// 
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void ConsentServicesButton_Click(object sender, EventArgs e)
        {
            _presenter.ShowForm(EventTopicNames.ShowInformedConsentForm);
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void LethalityButton_Click(object sender, EventArgs e)
        {
            _presenter.ShowFormWithNoCaseSelection(EventTopicNames.ShowNotesForm);
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void AuthorizeReleaseButton_Click(object sender, EventArgs e)
        {
            _presenter.ShowForm(EventTopicNames.ShowHumanRightsForm);
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void button2_Click(object sender, EventArgs e)
        {
            _presenter.ShowForm(EventTopicNames.ShowNewAuthorizationToRelease);
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void PrivacyPracticesButton_Click(object sender, EventArgs e)
        {
            _presenter.ShowForm(EventTopicNames.ShowPrivacyPracticesForm);
        }

        private void InformedConsentForMed_Click(object sender, EventArgs e)
        {
            _presenter.ShowForm(EventTopicNames.ShowInformedMedicalConsent);
        }
    }
}

