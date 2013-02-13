using System;
using System.Collections.Generic;
using System.Text;
using Microsoft.Practices.CompositeUI;
using Microsoft.Practices.EnterpriseLibrary.Logging;
using Microsoft.Practices.EnterpriseLibrary.Logging.Configuration;
using Microsoft.Practices.EnterpriseLibrary.Logging.ExtraInformation;
using Microsoft.Practices.EnterpriseLibrary.Logging.Filters;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.Infrastructure.Logger.Constants;
using Sante.EMR.SmartClient.Infrastructure.Interface;

namespace Sante.EMR.SmartClient.Infrastructure.Logger.Services
{
    [Service(typeof(ILoggerService))]
    public class LoggerService : ILoggerService
    {
        #region ILogger Members
        // Creates and fills the log entry with user information
        LogEntry _logEntry; // = new LogEntry();
        
        WorkItem _rootWorkItem;
        private StringBuilder sbLog = new StringBuilder();

        public LoggerService([ServiceDependency] WorkItem rootWorkItem)
        {
            _rootWorkItem = rootWorkItem;
            _logEntry = new LogEntry();
            
        }

        public string Log
        {
            get { return sbLog.ToString(); }

        }

        [EventSubscription(EventTopicNames.WriteToLog, ThreadOption.Background)]
        public void Write(object sender, EventArgs<string> e)
        {
            LogEntry(e.Data);
            sbLog.AppendLine(e.Data);
        }

        #endregion

        /// <summary>
        /// 
        /// </summary>
        /// <param name="msg"></param>
        private void LogEntry(string msg){
            // Writes the log entry.
            _logEntry.Message = msg;
            Microsoft.Practices.EnterpriseLibrary.Logging.Logger.Write(_logEntry);

        }

        #region ILoggerService Members

        public void Write(string msg)
        {
            LogEntry(msg);
        }

        #endregion

        #region ILoggerService Members


        public void Write(LogEvent p, string p_2)
        {
            LogEntry(p_2);
        }

        #endregion
    }
}
