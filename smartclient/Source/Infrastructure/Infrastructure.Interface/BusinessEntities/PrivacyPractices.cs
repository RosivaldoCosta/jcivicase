using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class PrivacyPractices: Form
    {
        
        private string _signature;
        private DateTime _sigdate;

        public string Signature
        {
            get
            {
                return _signature;
            }

            set
            {
            	_signature = value;
            }
        }

        public DateTime SigDate
        {
            get
            {
                return _sigdate;
            }

            set
            {
            	_sigdate = value;
            }
        }
    }
}
