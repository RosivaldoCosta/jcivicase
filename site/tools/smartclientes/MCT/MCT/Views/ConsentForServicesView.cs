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
using Sante.EMR.SmartClient.MCT.Constants;
using Microsoft.Practices.CompositeUI;
using Microsoft.Practices.CompositeUI.EventBroker;
using System.Diagnostics;

namespace Sante.EMR.SmartClient.MCT
{
    public partial class ConsentForServicesView : UserControl, IConsentForServicesView
    {
        public ConsentForServicesView()
        {
            InitializeComponent();
        }

        protected override void OnLoad(EventArgs e)
        {
            _presenter.OnViewReady();
            base.OnLoad(e);
        }

        private void toolStripButton1_Click(object sender, EventArgs e)
        {
            //_presenter.ShowSigPad();

            this.sigpad.Save();
            
            
        }
    }
}

