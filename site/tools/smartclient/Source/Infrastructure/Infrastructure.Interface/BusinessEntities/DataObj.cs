using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class DataObj
    {
        private DateTime _tstamp;

        public DateTime Tstamp
        {
            get { return _tstamp; }
            set { _tstamp = value; }
        }
    }
}
