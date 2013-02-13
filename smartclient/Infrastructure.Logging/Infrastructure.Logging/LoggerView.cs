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
using Sante.EMR.SmartClient.Infrastructure.Logger.Constants;
using Microsoft.Practices.ObjectBuilder;
using System.IO;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Infrastructure.Logger.Services;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Microsoft.Practices.CompositeUI.EventBroker;

namespace Sante.EMR.SmartClient.Infrastructure.Logger
{
    public partial class LoggerView : UserControl, ILoggerView
    {
        private LoggerViewPresenter _presenter;
        private readonly string _FILEPATH = AppDomain.CurrentDomain.BaseDirectory + @"\trace.log";
        private ILoggerService _logger;

        /// <summary>
        /// Initializes a new instance of the <see cref="T:LayoutView"/> class.
        /// </summary>
        public LoggerView([ServiceDependency] ILoggerService logger)
        {
            InitializeComponent();
           // this._moduleWorkspace.Name = WorkspaceNames.InfrastructureLoggingLayout;
            _logger = logger;
        }

        //[EventSubscription(EventTopicNames.WriteToLog, ThreadOption.Background)]
        //public void Write(object sender, EventArgs<string> e)
        //{


        //    LogEntry(e.Data);
        //}

        private void LoggerView_Load(object sender, EventArgs e)
        {
           // FileStream fileStream = new FileStream(_FILEPATH, FileMode.Open);
            try
            {
                //StreamReader streamReader = new StreamReader(fileStream);
                //string text = streamReader.ReadToEnd();

                //// read from file or write to file

                richTextBox.AppendText(_logger.Log);
                //StreamWriter writer = new StreamWriter(response.Body);
                //writer.Write(text);
                //streamReader.Close();
                //writer.Flush();

                //return true;
            }
            catch (Exception ex)
            {
                throw ex;
            }
            //finally
            //{
            //    fileStream.Close();
            //}

        }
    }
}
