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
        private string _suicidal;
        private int _ablility;
        private int _justified;
        private int _consequences;
        private int _recognizeAlternative;
        private int _mostRecent;
        private DateTime _mostRecentDate;
        private int _mostLethalMeans;
        private int _totalSectionOne1;
        private int _totalSection2;
        private int _combinedTotal1;
        private string _outcomeDescription;
        private string _incidentDescription;
        private int _totalSectionOne2;
        private int _combinedTotal2;
        private string _treatment;
        private int _outcome;

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

        public string Suicidal
        {
            get
            {
                return _suicidal;
            }
            set
            {
                _suicidal = value;
            }
        }

        public int Ablility
        {
            get
            {
                return _ablility;
            }
            set
            {
                _ablility = value;
            }
        }

        public int Justified
        {
            get
            {
                return _justified;
            }
            set
            {
                _justified = value;
            }
        }

        public int Consequences
        {
            get
            {
                return _consequences;
            }
            set
            {
                _consequences = value;
            }
        }

        public int RecognizeAlternative
        {
            get
            {
                return _recognizeAlternative;
            }
            set
            {
                _recognizeAlternative = value;
            }
        }

        public int MostRecent
        {
            get
            {
                return _mostRecent;
            }
            set
            {
                _mostRecent = value;
            }
        }

        public int Outcome
        {
            get
            {
                return _outcome;
            }
            set
            {
                _outcome = value;
            }
        }

        public DateTime MostRecentDate
        {
            get
            {
                return _mostRecentDate;
            }
            set
            {
                _mostRecentDate = value;
            }
        }

        public int MostLethalMeans
        {
            get
            {
                return _mostLethalMeans;
            }
            set
            {
                _mostLethalMeans = value;
            }
        }

        public int TotalSectionOne1
        {
            get
            {
                return _totalSectionOne1;
            }
            set
            {
                _totalSectionOne1 = value;
            }
        }

        public int TotalSection2
        {
            get
            {
                return _totalSection2;
            }
            set
            {
                _totalSection2 = value;
            }
        }

        public int CombinedTotal1
        {
            get
            {
                return _combinedTotal1;
            }
            set
            {
                _combinedTotal1 = value;
            }
        }

        public string OutcomeDescription
        {
            get
            {
                return _outcomeDescription;
            }
            set
            {
                _outcomeDescription = value;
            }
        }

        public string IncidentDescription
        {
            get
            {
                return _incidentDescription;
            }
            set
            {
                _incidentDescription = value;
            }
        }

        public int TotalSectionOne2
        {
            get
            {
                return _totalSectionOne2;
            }
            set
            {
                _totalSectionOne2 = value;
            }
        }

        public int CombinedTotal2
        {
            get
            {
                return _combinedTotal2;
            }
            set
            {
                _combinedTotal2 = value;
            }
        }

        public string Treatment
        {
            get
            {
                return _treatment;
            }
            set
            {
                _treatment = value;
            }
        }
    }
}