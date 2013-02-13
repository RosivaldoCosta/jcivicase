using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class ConsentForServices : Form
    {
        private string _pFirstName; //[PFirstName] [varchar](50) NULL,
	    private string _pLastName; //[PLastName] [varchar](50) NULL,
	    //private string _employeeID; //[EmployeeID] [varchar](50) NULL,
	    private string _staffsig; //[Staffsig] [varchar](50) NULL,
	    private string _witness; //[Witness] [varchar](50) NULL,
	    private string _parent; //[Parent] [varchar](50) NULL,
	    private string _parentSignature; //[ParentSignature] [varchar](50) NULL,
	    private string _mCT; //[MCT] [varchar](10) NULL,
	    private string _unableSign; //[UnableSign] [varchar](50) NULL,
	    private string _why; //[Why] [varchar](70) NULL,
        private string _notApplicable;

        public string MCT
        {
            get
            {
                return _mCT;
            }
            set
            {
                _mCT = value;
            }
        }

        public string NotApplicable
        {
            get
            {
                return _notApplicable;
            }
            set
            {
                _notApplicable = value;
            }
        }

        public string Parent
        {
            get
            {
                return _parent;
            }
            set
            {
                _parent = value;
            }
        }
        public string ParentSignature
        {
            get
            {
                return _parentSignature;
            }
            set
            {
                _parentSignature = value;
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
        public string Staffsig
        {
            get
            {
                return _staffsig;
            }
            set
            {
                _staffsig = value;
            }
        }
        public string UnableSign
        {
            get
            {
                return _unableSign;
            }
            set
            {
                _unableSign = value;
            }
        }
        public string Why
        {
            get
            {
                return _why;
            }
            set
            {
                _why = value;
            }
        }
        public string Witness
        {
            get
            {
                return _witness;
            }
            set
            {
                _witness = value;
            }
        }
	
    }
}
