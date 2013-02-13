using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class Task
    {
        public int Completed
        {
            get
            {
                return _completed; // throw new NotImplementedException();
            }
            set
            {
                _completed = value; // throw new NotImplementedException();
            }
        }
        private int _completed;
        private DateTime _setDate;
        private string _comments;
        private DateTime _dateDue;
        private string _initial;
        private DateTime _dateCompleted;
        private string _status;
        private string _name;
        private string _apptStatus;
        private int _casenumber;

        public int Owner
        {
            get
            {
                return _casenumber;
            }

            set
            {
            	_casenumber = value;
            }
        }

        public string AppointmentStatus
        {
            get
            {
                return _apptStatus;
            }

            set
            {
            	_apptStatus = value;
            }
        }

        public string NextTask
        {
            get
            {
                return _name;
            }

            set
            {
            	_name = value;
            }
        }

        
        public DateTime DateCompleted
        {
            get
            {
                return _dateCompleted;
            }

            set
            {
            	_dateCompleted = value;
            }
        }

        public string Initial
        {
            get
            {
                return _initial;
            }

            set
            {
            	_initial = value;
            }
        }

        public DateTime DateDue
        {
            get
            {
                return _dateDue;
            }

            set
            {
            	_dateDue = value;
            }
        }

        private DateTime _timeDue;
        public DateTime TimeDue
        {
            get
            {
                return _timeDue;
            }

            set
            {
                _timeDue = value;
            }
        }


        public DateTime SetDate
        {
            get
            {
                return _setDate;
            }

            set
            {
            	_setDate = value;
            }
        }

        public string Comments
        {
            get
            {
                return _comments;
            }

            set
            {
            	_comments = value;
            }
        }


        
    }
}
