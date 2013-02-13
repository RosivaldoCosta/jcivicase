using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class IHITIndividualTreatment : Form
    {
        private Int64 _iHITIndividualTreatmentPlanID = Convert.ToInt64(DateTime.Now.TimeOfDay.TotalMilliseconds);
        private string _pFirstName; //[PFirstName] [varchar](50) NULL,
	    private string _pLastName; //[PLastName] [varchar](50) NULL,
	    private string _pDate; //[PDate] [varchar](20) NULL,
	    private string _longTerm; //[LongTerm] [varchar](255) NULL,
        private DateTime _lTStart = new DateTime(); //[LTStart] [datetime] NULL,
	    private DateTime _lTTarget = new DateTime(); //[LTTarget] [datetime] NULL,
	    private DateTime _lTComplete = new DateTime(); //[LTComplete] [datetime] NULL,
	    private DateTime _lTRevised = new DateTime(); //[LTRevised] [datetime] NULL,
	    private string _lTProgress; //[LTProgress] [varchar](255) NULL,
	    private DateTime _lTPStart = new DateTime(); //[LTPStart] [datetime] NULL,
	    private DateTime _lTPTarget = new DateTime(); //[LTPTarget] [datetime] NULL,
	    private DateTime _lTPComplete = new DateTime(); //[LTPComplete] [datetime] NULL,
	    private DateTime _lTPRevised = new DateTime(); //[LTPRevised] [datetime] NULL,
	    private string _shortTerm1; //[ShortTerm1] [varchar](255) NULL,
	    private DateTime _sT1Start = new DateTime(); //[ST1Start] [datetime] NULL,
	    private DateTime _sT1Target = new DateTime(); //[ST1Target] [datetime] NULL,
	    private string _sT1Complete; //[ST1Complete] [datetime] NULL,
	    private string _sT1Revised; //[ST1Revised] [datetime] NULL,
	    private string _sT1Objective; //[ST1Objective] [varchar](255) NULL,
	    private DateTime _sT1OStart = new DateTime(); //[ST1OStart] [datetime] NULL,
	    private DateTime _sT1OTarget =  new DateTime(); //[ST1OTarget] [datetime] NULL,
	    private DateTime _sT1OComplete = new DateTime(); //[ST1OComplete] [datetime] NULL,
	    private DateTime _sT1ORevised = new DateTime(); //[ST1ORevised] [datetime] NULL,
	    private string _sT1Progress; //[ST1Progress] [varchar](255) NULL,
	    private DateTime _sT1PStart =  new DateTime(); //[ST1PStart] [datetime] NULL,
	    private DateTime _st1PTarget = new DateTime(); //[ST1PTarget] [datetime] NULL,
	    private DateTime _sT1PComplete = new DateTime();//[ST1PComplete] [datetime] NULL,
	    private DateTime _sT1PRevised = new DateTime(); //[ST1PRevised] [datetime] NULL,
	    private string _shortTerm2; //[ShortTerm2] [varchar](255) NULL,
	    private DateTime _sT2Start = new DateTime(); //[ST2Start] [datetime] NULL,
	    private DateTime _sT2Target = new DateTime(); //[ST2Target] [datetime] NULL,
	    private DateTime _sT2Complete = new DateTime(); //[ST2Complete] [datetime] NULL,
	    private DateTime _sT2Revised =  new DateTime(); //[ST2Revised] [datetime] NULL,
	    private string _sT2Objective; //[ST2Objective] [varchar](255) NULL,
	    private DateTime _sT2OStart = new DateTime(); //[ST2OStart] [datetime] NULL,
	    private DateTime _sT2OTarget =  new DateTime(); //[ST2OTarget] [datetime] NULL,
	    private DateTime _sT2OComplete =  new DateTime(); //[ST2OComplete] [datetime] NULL,
	    private DateTime _sT2ORevised = new DateTime(); //[ST2ORevised] [datetime] NULL,
	    private string _sT2Progress; //[ST2Progress] [varchar](255) NULL,
	    private DateTime _sT2PStart = new DateTime(); //[ST2PStart] [datetime] NULL,
	    private DateTime _sT2PTarget = new DateTime(); //[ST2PTarget] [datetime] NULL,
	    private DateTime _sT2PComplete = new DateTime(); //[ST2PComplete] [datetime] NULL,
	    private DateTime _sT2PRevised = new DateTime(); //[ST2PRevised] [datetime] NULL,
	    private string _comments; //[Comments] [varchar](255) NULL,
        private int _active;

        
        public int Active
        {
            get
            {
                return _active;
            }

            set
            {
            	_active = value;
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

        public Int64 IHITIndividualTreatmentID
        {
            get
            {
                return _iHITIndividualTreatmentPlanID;
            }
            set
            {
                _iHITIndividualTreatmentPlanID = value;
            }
        }

        public string LongTerm
        {
            get
            {
                return _longTerm;
            }
            set
            {
                _longTerm = value;
            }
        }
        public DateTime LTComplete
        {
            get
            {
                return _lTComplete;
            }
            set
            {
                _lTComplete = value;
            }
        }
        public DateTime LTPComplete
        {
            get
            {
                return _lTPComplete;
            }
            set
            {
                _lTPComplete = value;
            }
        }
        public DateTime LTPRevised
        {
            get
            {
                return _lTPRevised;
            }
            set
            {
                _lTPRevised = value;
            }
        }
        public string LTProgress
        {
            get
            {
                return   _lTProgress;
            }
            set
            {
                _lTProgress = value;
            }
        }
        public DateTime LTPStart
        {
            get
            {
                return _lTPStart;
            }
            set
            {
                _lTPStart = value;
            }
        }
        public DateTime LTPTarget
        {
            get
            {
                return _lTPTarget;
            }
            set
            {
                _lTPTarget = value;
            }
        }
        public DateTime LTRevised
        {
            get
            {
                return _lTRevised;
            }
            set
            {
                _lTRevised = value;
            }
        }
        public DateTime LTStart
        {
            get
            {
                return _lTStart;
            }
            set
            {
                _lTStart = value;
            }
        }
        public DateTime LTTarget
        {
            get
            {
                return _lTTarget;
            }
            set
            {
                _lTTarget = value;
            }
        }
        public string PDate
        {
            get
            {
                return _pDate;
            }
            set
            {
                _pDate = value;
            }
        }
        public string PFirstName
        {
            get
            {
                return _pFirstName;
            }
            set
            {
                _pFirstName = value;
            }
        }
        public string PLastName
        {
            get
            {
                return _pLastName;
            }
            set
            {
                _pLastName = value;
            }
        }
        public string ShortTerm1
        {
            get
            {
                return _shortTerm1;
            }
            set
            {
                _shortTerm1 = value;
            }
        }
        public string ShortTerm2
        {
            get
            {
                return _shortTerm2;
            }
            set
            {
                _shortTerm2 = value;
            }
        }
        public string ST1Complete
        {
            get
            {
                return _sT1Complete;
            }
            set
            {
                _sT1Complete = value;
            }
        }
        public string ST1Objective
        {
            get
            {
                return _sT1Objective;
            }
            set
            {
                _sT1Objective = value;
            }
        }
        public DateTime ST1OComplete
        {
            get
            {
                return _sT1OComplete;
            }
            set
            {
                _sT1OComplete = value;
            }
        }
        public DateTime ST1ORevised
        {
            get
            {
                return _sT1ORevised;
            }
            set
            {
                _sT1ORevised = value;
            }
        }
        public DateTime ST1OStart
        {
            get
            {
                return _sT1OStart;
            }
            set
            {
                _sT1OStart = value;
            }
        }
        public DateTime ST1OTarget
        {
            get
            {
                return _sT1OTarget;
            }
            set
            {
                _sT1OTarget = value;
            }
        }
        public DateTime ST1PComplete
        {
            get
            {
                return _sT1PComplete;
            }
            set
            {
                _sT1PComplete = value;
            }
        }
        public DateTime ST1PRevised
        {
            get
            {
                return _sT1PRevised;
            }
            set
            {
                _sT1PRevised = value;
            }
        }
        public string ST1Progress
        {
            get
            {
                return _sT1Progress;
            }
            set
            {
                _sT1Progress = value;
            }
        }
        public DateTime ST1PStart
        {
            get
            {
                return _sT1PStart;
            }
            set
            {
                _sT1PStart = value;
            }
        }
        public DateTime ST1PTarget
        {
            get
            {
                return _st1PTarget;
            }
            set
            {
                _st1PTarget = value;
            }
        }
        public string ST1Revised
        {
            get
            {
                return _sT1Revised;
            }
            set
            {
                _sT1Revised = value;
            }
        }
        public DateTime ST1Start
        {
            get
            {
                return _sT1Start;
            }
            set
            {
                _sT1Start = value;
            }
        }
        public DateTime ST1Target
        {
            get
            {
                return _sT1Target;
            }
            set
            {
                _sT1Target = value;
            }
        }
        public DateTime ST2Complete
        {
            get
            {
                return _sT2Complete;
            }
            set
            {
                _sT2Complete = value;
            }
        }
        public string ST2Objective
        {
            get
            {
                return _sT2Objective;
            }
            set
            {
                _sT2Objective = value;
            }
        }
        public DateTime ST2OComplete
        {
            get
            {
                return _sT2OComplete;
            }
            set
            {
                _sT2OComplete = value;
            }
        }
        public DateTime ST2ORevised
        {
            get
            {
                return _sT2ORevised;
            }
            set
            {
                _sT2ORevised = value;
            }
        }
        public DateTime ST2OStart
        {
            get
            {
                return _sT2OStart;
            }
            set
            {
                _sT2OStart = value;
            }
        }
        public DateTime ST2OTarget
        {
            get
            {
                return _sT2OTarget;
            }
            set
            {
                _sT2OTarget = value;
            }
        }
        public DateTime ST2PComplete
        {
            get
            {
                return _sT2PComplete;
            }
            set
            {
                _sT2PComplete = value;
            }
        }
        public DateTime ST2PRevised
        {
            get
            {
                return _sT2PRevised;
            }
            set
            {
                _sT2PRevised = value;
            }
        }
        public string ST2Progress
        {
            get
            {
                return _sT2Progress;
            }
            set
            {
                _sT2Progress = value;
            }
        }
        public DateTime ST2PStart
        {
            get
            {
                return _sT2PStart;
            }
            set
            {
                _sT2PStart = value;
            }
        }
        public DateTime ST2PTarget
        {
            get
            {
                return _sT2PTarget;
            }
            set
            {
                _sT2PTarget = value;
            }
        }
        public DateTime ST2Revised
        {
            get
            {
                return _sT2Revised;
            }
            set
            {
                _sT2Revised = value;
            }
        }
        public DateTime ST2Start
        {
            get
            {
                return _sT2Start;
            }
            set
            {
                _sT2Start = value;
            }
        }
        public DateTime ST2Target
        {
            get
            {
                return _sT2Target;
            }
            set
            {
                _sT2Target = value;
            }
        }
    }
}
