using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class Lethality : Form
    {
        private int _lethalityID;
        private string _pfirstName;
        private string _plastname;
        private string _patientID;
        private DateTime _date;
        private string _time;
        private int _planselect;
        private int _method;
        private int _accessibility;
        private int _calleralone;
        private int _drugalcoholuse;
        private int _priorattempts;
        private int _familyattemptscompletions;
        private int _depressionsymptons;
        private int _support;
        private int _total1;
        private int _total2;
        private int _total3;
        private int _score;
        private string _comments;
        private string _employee;
        private string _ability;
        private string _feelsjustified;
        private string _consequences;
        private string _mostrecent;
        private string _mostserious;
        private string _alternatives;
        private string _mostlethal;


        public string Ability
        {
            get { return _ability; }
            set { _ability = value; }
        }

        public string Consequences
        {
            get { return _consequences; }
            set { _consequences = value; }
        }

        public string MostLethal
        {
            get { return _mostlethal; }
            set { _mostlethal = value; }
        }

        public string Alternatives
        {
            get { return _alternatives; }
            set { _alternatives = value; }
        }


        public string MostSerious
        {
            get { return _mostserious; }
            set { _mostserious = value; }
        }

        public string MostRecent
        {
            get { return _mostrecent; }
            set { _mostrecent = value; }
        }

        public string FeelsJustified
        {
            get { return _feelsjustified; }
            set { _feelsjustified = value; }
        }

        public string Employee
        {
            get { return _employee; }
            set { _employee = value; }
        }
        

        public int Accessibility
        {
            get
            {
                return _accessibility;
            }
            set
            {
                _accessibility = value;
            }
        }

        public int CallerAlone
        {
            get
            {
                return _calleralone;
            }
            set
            {
                _calleralone = value;
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
        public DateTime Date
        {
            get
            {
                return _date;
            }
            set
            {
                _date = value;
            }
        }
        public int DepressionSymptoms
        {
            get
            {
                return _depressionsymptons;
            }
            set
            {
                _depressionsymptons = value;
            }
        }
        public int DrugAlcoholUse
        {
            get
            {
                return _drugalcoholuse;
            }
            set
            {
                _drugalcoholuse = value;
            }
        }
        public int FamilyAttemptsCompletions
        {
            get
            {
                return _familyattemptscompletions;
            }
            set
            {
                _familyattemptscompletions = value;
            }
        }
        private int LethalityID
        {
            get
            {
                return _lethalityID;
            }
            set
            {
                _lethalityID = value;
            }
        }
        public int Method
        {
            get
            {
                return _method;
            }
            set
            {
                _method = value;
            }
        }
        public string PatientID
        {
            get
            {
                return _patientID;
            }
            set
            {
                _patientID = value;
            }
        }
        public string PfirstName
        {
            get
            {
                return _pfirstName;
            }
            set
            {
                _pfirstName = value;
            }
        }
        public int PlanSelect
        {
            get
            {
                return _planselect;
            }
            set
            {
                _planselect = value;
            }
        }
        public string PlastName
        {
            get
            {
                return _plastname;
            }
            set
            {
                _plastname = value;
            }
        }
        public int PriorAttempts
        {
            get
            {
                return _priorattempts;
            }
            set
            {
                _priorattempts = value;
            }
        }
        public int Score
        {
            get
            {
                return _score;
            }
            set
            {
                _score = value;
            }
        }
        public int Supports
        {
            get
            {
                return _support;
            }
            set
            {
                _support = value;
            }
        }
        public string Time
        {
            get
            {
                return _time;
            }
            set
            {
                _time = value;
            }
        }
        public int Total1
        {
            get
            {
                return _total1;
            }
            set
            {
                _total1 = value;
            }
        }
        public int Total2
        {
            get
            {
                return _total2;
            }
            set
            {
                _total2 = value;
            }
        }
        public int Total3
        {
            get
            {
                return _total3;
            }
            set
            {
                _total3 = value;
            }
        }

    }
}
