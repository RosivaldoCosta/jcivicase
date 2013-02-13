using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Logger.Services
{
    public enum LogEvent
    {
        INFO,
        ERROR,
        EVENT
    }


    public interface ILoggerService
    {
        void Write(string msg);
        string Log { get;}

        void Write(LogEvent p, string p_2);
    }
}
