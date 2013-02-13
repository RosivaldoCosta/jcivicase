using System;
using System.Text;
using System.Threading;
using System.Collections.Generic;
using Microsoft.Practices.CompositeUI;
using Microsoft.Practices.SmartClient.ConnectionMonitor;
using Sante.EMR.SmartClient.Infrastructure.Interface.Constants;
using Sante.EMR.SmartClient.ConnectionMonitor.Constants;
using Sante.EMR.SmartClient.Infrastructure.Interface;
//using Sante.EMR.SmartClient.Infrastructure.Data.Services;
using Microsoft.Practices.CompositeUI.EventBroker;
using System.Windows.Forms;
using ConstantsAlias = Sante.EMR.SmartClient.Infrastructure.Interface.Constants;
using Sante.EMR.SmartClient.Infrastructure.Logger.Services;

namespace Sante.EMR.SmartClient.Infrastructure.Module.ConnectionMonitor.Services
{
    [Service(typeof(IConnectionMonitorService))]
    public class ConnectionMonitorService : IConnectionMonitorService
    {
        private Microsoft.Practices.SmartClient.ConnectionMonitor.ConnectionMonitor _networkMonitor;
        private Thread _connectedMonitorThread;
        private WorkItem _rootWorkItem;
        private volatile bool _running;
        EventArgs<string> _ev; // = new EventArgs<string>();
        //IDataAccessService _dataService;
        private ILoggerService _logger;

        public ConnectionMonitorService([ServiceDependency] WorkItem rootWorkItem, [ServiceDependency] ILoggerService logger)
        {
            _logger = logger;
            _networkMonitor = ConnectionMonitorFactory.CreateFromConfiguration();
            _networkMonitor.Connections[0].StateChanged += new EventHandler<Microsoft.Practices.SmartClient.ConnectionMonitor.StateChangedEventArgs>(ConnectionMonitorService_StateChanged);

            _rootWorkItem = rootWorkItem;
            _connectedMonitorThread = new Thread(new ThreadStart(StartConnectionMonitor));
            _connectedMonitorThread.Start();
            _running = true;
        
        }

        void ConnectionMonitorService_StateChanged(object sender, Microsoft.Practices.SmartClient.ConnectionMonitor.StateChangedEventArgs e)
        {
            if (_networkMonitor.Connections != null && _networkMonitor.Connections[0] != null)
            {
                if (_networkMonitor.Connections[0].IsConnected)
                {
                    _ev = new EventArgs<string>("Connected");

                    _rootWorkItem.EventTopics[Sante.EMR.SmartClient.ConnectionMonitor.Constants.EventTopicNames.StatusUpdate].Fire(this, _ev, _rootWorkItem, PublicationScope.WorkItem);
                }
                else
                {
                    _ev = new EventArgs<string>("Offline");
                    _rootWorkItem.EventTopics[Sante.EMR.SmartClient.ConnectionMonitor.Constants.EventTopicNames.StatusUpdate].Fire(this, _ev, _rootWorkItem, PublicationScope.WorkItem);

                }
            }
        }
        #region IConnectionMonitorService Members

        [EventSubscription(ConstantsAlias.EventTopicNames.Kill, ThreadOption.UserInterface)]
        public void KillConnectionMonitor(object sender, EventArgs<string> e)
        {
            _running = false;
            
            
        }

        private void StartConnectionMonitor()
        {
            EventArgs<string> ev = new EventArgs<string>("Network Connection Monitor Thread started...");

            try
            {
                _rootWorkItem.EventTopics[Sante.EMR.SmartClient.Infrastructure.Logger.Constants.EventTopicNames.WriteToLog].Fire(this, ev, _rootWorkItem, PublicationScope.WorkItem);

            }
            catch (InvalidOperationException ex)
            {
                string msg = ex.Message;
                _logger.Write(msg);
            }
            catch (Exception ex)
            {
                _logger.Write(ex.Message);
                _logger.Write(ex.StackTrace);
                throw ex;
            }

            EventArgs<string> e;// = new EventArgs<string>("Connected");

            while (_running)
            {
                

                if (_networkMonitor.Connections != null && _networkMonitor.Connections[0] != null)
                {
                    if (_networkMonitor.Connections[0].IsConnected)
                    {
                        e = new EventArgs<string>("Connected");

                        try
                        {
                            _rootWorkItem.EventTopics[Sante.EMR.SmartClient.ConnectionMonitor.Constants.EventTopicNames.StatusUpdate].Fire(this, e, _rootWorkItem, PublicationScope.WorkItem);
                        }
                        catch (InvalidOperationException ex)
                        {
                            _running = false;
                            ev = new EventArgs<string>("Aborting network connection monitor thread...");
                            _rootWorkItem.EventTopics[Sante.EMR.SmartClient.Infrastructure.Logger.Constants.EventTopicNames.WriteToLog].Fire(this, ev, _rootWorkItem, PublicationScope.WorkItem);
                            _logger.Write(ex.Message);
                        }

                        //_logger.Write("SmartClient is connected");
                    }
                    else
                    {
                        e = new EventArgs<string>("Offline");
                        _rootWorkItem.EventTopics[Sante.EMR.SmartClient.ConnectionMonitor.Constants.EventTopicNames.StatusUpdate].Fire(this, e, _rootWorkItem, PublicationScope.WorkItem);

                        ev = new EventArgs<string>("Network connection is currently offline...");
                        _rootWorkItem.EventTopics[Sante.EMR.SmartClient.Infrastructure.Logger.Constants.EventTopicNames.WriteToLog].Fire(this, ev, _rootWorkItem, PublicationScope.WorkItem);


                        _logger.Write("SmartClient is offline");
                    }
                }
            }

            ev = new EventArgs<string>("Aborting network connection monitor thread...");
            _rootWorkItem.EventTopics[Sante.EMR.SmartClient.Infrastructure.Logger.Constants.EventTopicNames.WriteToLog].Fire(this, ev, _rootWorkItem, PublicationScope.WorkItem);

        }

        public bool IsConnected()
        {
            if (_networkMonitor.Connections != null && _networkMonitor.Connections[0] != null)
                return _networkMonitor.Connections[0].IsConnected;
            else
                return false;
        }

        #endregion
    }
}
