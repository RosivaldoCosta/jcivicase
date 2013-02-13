using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class InformedConsent : Form
    {
        private string _company; //[Company] [varchar](50) NULL,
	    private string _pFirstName; //[PFirstName] [varchar](50) NULL,
	    private string _pLastName; //[PLastName] [varchar](50) NULL,
	    private string _person; //[Person] [varchar](50) NULL,
	    private string _patientID; //[PatientID] [varchar](50) NULL,
	    private DateTime _patientDate; //[PatientDate] [datetime] NULL,
	    private string _staffsig; //[Staffsig] [varchar](50) NULL,
	   
	   private DateTime _employeeDate; //[EmployeeDate] [datetime] NULL,
	   private string _mCT; //[MCT] [varchar](10) NULL,
       public string Company
       {
           get
           {
               return _company;
           }
           set
           {
               _company = value;
           }
       }
       public DateTime EmployeeDate
       {
           get
           {
               return _employeeDate;
           }
           set
           {
               _employeeDate = value;
           }
       }
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
        public DateTime PatientDate
        {
            get
            {
                return _patientDate;
            }
            set
            {
                _patientDate = value;
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
        public string Person
        {
            get
            {
                return _person;
            }
            set
            {
                _person = value;
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
	
    }
}
