using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Module.ConnectionMonitor.Services
{
    public interface IConnectionMonitorService
    {
        bool IsConnected();
    }
}
