using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class Telephone : Form
    {
        private Int64 _TelephoneID = Convert.ToInt64(DateTime.Now.TimeOfDay.TotalMilliseconds);
        private string _personContacted; //[PersonContacted] [varchar](50) NULL,
        private string _org; //[Org] [varchar](50) NULL,
        private string _phone; //[Phone] [varchar](10) NULL,
        private string _notes; //[Notes] [varchar](1000) NULL,
        private string _nextTask; //[NextTask] [varchar](50) NULL,
        private string _call; //[call] [varchar](20) NULL,
        private string _employeeID;

        public  string Employee
        {
            get { return _employeeID; }
            set { _employeeID = value; }
        }


        public string call
        {
            get
            {
                return _call;
            }
            set
            {
                _call = value;
            }
        }
        public string NextTask
        {
            get
            {
                return _nextTask;
            }
            set
            {
                _nextTask = value;
            }
        }
        public string Notes
        {
            get
            {
                return _notes;
            }
            set
            {
                _notes = value;
            }
        }
        public string Org
        {
            get
            {
                return _org;
            }
            set
            {
                _org = value;
            }
        }
        public string PersonContacted
        {
            get
            {
                return _personContacted;
            }
            set
            {
                _personContacted = value;
            }
        }
        public string Phone
        {
            get
            {
                return _phone;
            }
            set
            {
                _phone = value;
            }
        }
        public Int64 TelephoneID
        {
            get
            {
                return _TelephoneID;
            }
            set
            {
                _TelephoneID = value;
            }
        }

    }
}
