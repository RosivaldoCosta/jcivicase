using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Drawing;
using System.Data;
using System.Text;
using System.Windows.Forms;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
 
namespace Sante.EMR.SmartClient.Infrastructure.Interface.View
{
    public partial class FormView : UserControl
    {
        protected DateTime _date;
        protected DateTime _time;
        protected Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Form _form;

        public FormView()
        {
            InitializeComponent();
            _date = DateTime.Now;
            _time = DateTime.Now;
            
        }
    }
}
