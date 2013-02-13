//----------------------------------------------------------------------------------------
// patterns & practices - Smart Client Software Factory - Guidance Package
//
// This file was generated by the "Add Foundational Module" recipe.
//
// The LayoutView usercontrol defines a layout decoupled from the shell. 
// It provides a left and right workspace, menu bar, tool bar and status bar.
// These ui elements are added as extension sites.
//
// For more information see:
// ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/03-01-030-How_to_Create_a_Foundational_Module.htm
//
// Latest version of this Guidance Package: http://go.microsoft.com/fwlink/?LinkId=62182
//----------------------------------------------------------------------------------------

using System;
using System.Text;
using System.Windows.Forms;
using Sante.EMR.SmartClient.ConnectionMonitor.Constants;
using Microsoft.Practices.ObjectBuilder;

namespace Sante.EMR.SmartClient.Infrastructure.Module.ConnectionMonitor
{
    public partial class LayoutView : UserControl, ILayoutView
    {
        private LayoutViewPresenter _presenter;

        /// <summary>
        /// Initializes a new instance of the <see cref="T:LayoutView"/> class.
        /// </summary>
        public LayoutView()
        {
            InitializeComponent();
            this._moduleWorkspace.Name = WorkspaceNames.ConnectionMonitorLayout;

        }
    }
}
