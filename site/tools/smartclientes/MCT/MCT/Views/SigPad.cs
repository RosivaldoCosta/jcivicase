using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Drawing;
using System.Data;
using System.Text;
using System.Windows.Forms;
using Microsoft.Practices.CompositeUI.SmartParts;
using Microsoft.Practices.ObjectBuilder;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Module.Services;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.MCT.Services;


namespace Sante.EMR.SmartClient.MCT.Views
{

    public partial class SigPad : UserControl, ISigPad, ISmartPartInfoProvider
    {
        public SigPad()
        {
            InitializeComponent();
        }

        public SigPad([ServiceDependency] IOutboxService outbox,
                                    [ServiceDependency] IWorkflowStateService workflow)
        {
            //_outbox = outbox;
            //_workflow = workflow;
            InitializeComponent();
        }

        protected override void OnLoad(EventArgs e)
        {
            _presenter.OnViewReady();
            base.OnLoad(e);
        }

        #region ISmartPartInfoProvider Members

        public ISmartPartInfo GetSmartPartInfo(Type smartPartInfoType)
        {
            throw new NotImplementedException();
        }

        #endregion
    }
}
