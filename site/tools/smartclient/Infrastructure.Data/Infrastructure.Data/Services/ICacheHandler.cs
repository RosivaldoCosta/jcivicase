using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Data.Services
{
    public interface ICacheHandler
    {
        object GetData(string key);
        void FlushCache();
        bool AddData(string key, object value);
        bool SyncCache();
    }
}
