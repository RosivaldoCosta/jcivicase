using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    public enum Depart
    {
        OPS,
        MCT,
        IHIT,
        UCC,
        CISM
    }

    public enum Status
    {
        Active,
        Unresolved,
        FollowUp,
        FrequentCaller,
        MCT,
        EastIHIT,
        WestIHIT,
        CISM,
        Closure
    }


    [Serializable]
    public class Case : DataObj
    {
        private int _caseNumber;
        private DateTime _dateOpened;
        private DateTime _dateClosed;
        private Status _status;
        private string _nextTask;
        private DateTime _nextTaskDate;
        private string _openBy;
        
        private DateTime _statusDate;
        private string _closedInitials;
        private string _comments;
        private DateTime _setDate;
        
        private Task _nextTaskObj;
        //private List<IntakeForm> _intakeforms;
        private List<Form> _forms;
        private List<Telephone> _phoneContacts;
        private Patient _patient;

        //public Patient Patient
        //{
        //    get
        //    {
        //        return _patient;
        //    }

        //    set
        //    {
        //        _patient = value;
        //    }
        //}

        public List<Form> Forms
        {
            get
            {
                
                return _forms;
            }
            set
            {
            	_forms = value;
            }
            
        }

        private Int64 _userCaseNumber;

        public Int64 UserGeneratedCaseNumber
        {
            get
            {
                return _userCaseNumber;
            }

            set
            {
                _userCaseNumber = value;
            }
        }


        
        public Task NextTaskObj
        {
            get
            {
                return _nextTaskObj;
            }

            set
            {
            	_nextTaskObj = value;
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

        public string ClosedInitials
        {
            get
            {
                return _closedInitials;
            }

            set
            {
            	_closedInitials = value;
            }
        }
        public DateTime StatusDate
        {
            get
            {
                return _statusDate;
            }

            set
            {
            	_statusDate = value;
            }
        }
        

        public string OpenBy
        {
            get
            {
                return _openBy;
            }

            set
            {
            	_openBy = value;
            }
        }

        public DateTime NextTaskDate
        {
            get
            {
                return _nextTaskDate;
            }

            set
            {
            	_nextTaskDate = value;
            }
        }

        public Status Status
        {
            get
            {
                return _status;
            }

            set
            {
            	_status = value;
            }
        }

        public DateTime DateClosed
        {
            get
            {
                return _dateClosed;
            }

            set
            {
            	_dateClosed = value;
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

        public DateTime DateOpened
        {
            get
            {
                return _dateOpened;
            }

            set
            {
            	_dateOpened = value;
            }
        }

        public int CaseNumber
        {
            get
            {
                return _caseNumber;
            }

            set
            {
            	_caseNumber = value;
            }
        }

        public static Status SetStatus(string value)
        {
            switch (value)
                            {
                                case "Active":
                                    return Status.Active;      
                                    break;
                                case "Unresolved":
                                    return Status.Unresolved;
                                    break;
                                case "Follow Up":
                                    return Status.FollowUp;
                                    break;
                                case "East IHIT":
                                    return Status.EastIHIT;
                                    break;
                                case "Frequent Caller":
                                    return Status.FrequentCaller;
                                    break;
                                case "MCT":
                                    return Status.MCT;
                                    break;
                                case "West IHIT Referral":
                                    return Status.WestIHIT;
                                    break;
                                case "CISM":
                                    return Status.CISM;
                                    break;
                                case "Recommend for Closure":
                                    return Status.Closure;
                                    break;
                                default:
                                    return Status.Active;
                                    break;
                            } 
                        
        }




        /// <summary>
        /// 
        /// </summary>
        /// <param name="globalSanteEMRSmartClientOPSNewIntakeFormView"></param>
        public void Fill(UserControl form)
        {
            //this.CaseNumber = form.
        }
    }
}
