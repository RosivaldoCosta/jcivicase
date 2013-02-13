using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class ProgressNote : Form
    {
        private Int64 _progressNoteID = Convert.ToInt64(DateTime.Now.TimeOfDay.TotalMilliseconds); //[ProgressNoteID] [bigint] NOT NULL CONSTRAINT [DF_ProgressNote_ProgressNoteID]  DEFAULT ((0)),
        private DateTime _timeStamp; //[TimeStamp] [datetime] NULL,
        private DateTime _time; //[Time] [varchar](20) NULL,
	    private string _StaffID;
        private string _notes;//[Notes] [varchar](500) NULL,
        private string _comments; //[Comments] [varchar](255) NULL,
        private string _noteAction; //[NoteAction] [varchar](50) NULL,
	    private int _active = 1;//[Active] [bit] NOT NULL,
        private int _initial_visit;
        private string _eastWest;

        public int InitialVisit
        {
            get { return _initial_visit; }
            set { _initial_visit = value; }
        }

        public string EastWest
        {
            get { return _eastWest; }
            set { _eastWest = value; }
        }

        public string StaffID
        {
            get { return _StaffID; }
            set { _StaffID = value; }
        }

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
        public string NoteAction
        {
            get
            {
                return _noteAction;
            }
            set
            {
                _noteAction = value;
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
        //[ID] [bigint] IDENTITY(1,1) NOT NULL
        public Int64 ProgressNoteID
        {
            get
            {
                return _progressNoteID;
            }
            set
            {
                _progressNoteID = value;
            }
        }
        public DateTime Time
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
        public DateTime TimeStamp
        {
            get
            {
                return _timeStamp;
            }
            set
            {
                _timeStamp = value;
            }
        }

    }
}
