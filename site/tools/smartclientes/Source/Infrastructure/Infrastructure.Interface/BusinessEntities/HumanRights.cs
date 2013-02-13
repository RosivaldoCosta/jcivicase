using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class HumanRights : Form
    {
                                    //[HumanRightsID] [bigint] IDENTITY(1,1) NOT NULL,
	   private string _person;      //[Person] [varchar](50) NULL,
	                                //[CaseNumber] [bigint] NULL,
	   private string _pName;       //[PName] [varchar](50) NULL,
	   private DateTime _patientDate; //[PatientDate] [datetime] NULL,
	   private string _staffsig;    //[Staffsig] [varchar](50) NULL,
	                                //[EmployeeID] [varchar](50) NULL,
	   private DateTime _employeeDate; //[EmployeeDate] [datetime] NULL,
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
       //[Depart] [varchar](15) NULL,
       //[tstamp] [datetime] NULL CONSTRAINT [DF_HumanRights_tstamp]  DEFAULT (getdate())
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
       public string PName
       {
           get
           {
               return _pName;
           }
           set
           {
               _pName = value;
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
