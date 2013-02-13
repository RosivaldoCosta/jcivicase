using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class AuthorizationRelease : Form
    {
        //    [CaseNumber] [bigint] NULL,
        private string _company; //[Company] [varchar](50) NULL,
        private string _pFirstName;//[PFirstName] [varchar](50) NULL,
        private string _employeeSig;

        public string AdmissionSummary
        {
            get
            {
                return _admissionSummary;
            }
            set
            {
                _admissionSummary = value;
            }
        }
        public string DateSigned
        {
            get
            {
                return _dateSigned;
            }
            set
            {
                _dateSigned = value;
            }
        }
        public string DischargeInstructions
        {
            get
            {
                return _dischargeInstructions;
            }
            set
            {
                _dischargeInstructions = value;
            }
        }
        public string DischargeSummary
        {
            get
            {
                return _dischargeSummary;
            }
            set
            {
                _dischargeSummary = value;
            }
        }
        public string DOB
        {
            get
            {
                return _dob;
            }
            set
            {
                _dob = value;
            }
        }
        public string EmployeeDateSigned
        {
            get
            {
                return _employeeDateSigned;
            }
            set
            {
                _employeeDateSigned = value;
            }
        }
        public string ExpireDate
        {
            get
            {
                return _expireDate;
            }
            set
            {
                _expireDate = value;
            }
        }
        public string LaboratoryReports
        {
            get
            {
                return _laboratoryReports;
            }
            set
            {
                _laboratoryReports = value;
            }
        }
        public string LegalRep
        {
            get
            {
                return _legalRep;
            }
            set
            {
                _legalRep = value;
            }
        }
        public string Other
        {
            get
            {
                return _other;
            }
            set
            {
                _other = value;
            }
        }
        public string OtherDesc
        {
            get
            {
                return _otherDesc;
            }
            set
            {
                _otherDesc = value;
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

        public string EmployeeSigID
        {
            get
            {
                return _employeeSig;
            }

            set
            {
                _employeeSig = value;
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

        public string PHIAddress
        {
            get
            {
                return _pHIAddress;
            }
            set
            {
                _pHIAddress = value;
            }
        }
        public string PHIName
        {
            get
            {
                return _pHIName;
            }
            set
            {
                _pHIName = value;
            }
        }
        public string PHIPhone
        {
            get
            {
                return _pHIPhone;
            }
            set
            {
                _pHIPhone = value;
            }
        }
        public string PhysicalExamination
        {
            get
            {
                return _physicalExamination;
            }
            set
            {
                _physicalExamination = value;
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
        private string _pLastName; //[PLastName] [varchar](50) NULL,
        private string _pHIName; //[PHIName] [varchar](50) NULL,
        private string _pHIAddress; //[PHIAddress] [varchar](50) NULL,
        private string _pHIPhone; //[PHIPhone] [bigint] NULL,
        private string _socialHistory; //[SocialHistory] [varchar](50) NULL,
        private string _physicalExamination; //[PhysicalExamination] [varchar](50) NULL,
        private string _xRayCTMRIReports; //[XRayCTMRIReports] [varchar](50) NULL,
        private string _admissionSummary; //[AdmissionSummary] [varchar](50) NULL,
        private string _laboratoryReports;//[LaboratoryReports] [varchar](50) NULL,
        private string _dischargeSummary; //[DischargeSummary] [varchar](50) NULL,
        private string _treatmentSummary; //[TreatmentSummary] [varchar](50) NULL,
        private string _psychiatricEvaluation; //[PsychiatricEvaluation] [varchar](50) NULL,
        private string _dischargeInstructions;//[DischargeInstructions] [varchar](50) NULL,
        private string _other; //[Other] [varchar](50) NULL,
        private string _otherDesc; //[OtherDesc] [varchar](50) NULL,
        private string _expireDate; //[ExpireDate] [varchar](50) NULL,
        private string _person; //[Person] [varchar](50) NULL,
        private string _patientID; //[PatientID] [varchar](50) NULL,
        private string _dob; //[DOB] [varchar](50) NULL,
        private string _dateSigned;//[DateSigned] [varchar](50) NULL,
        private string _legalRep; //[LegalRep] [varchar](50) NULL,
        //private string _employeeID; //[EmployeeID] [varchar](50) NULL,
        //private string _depart; //[Depart] [varchar](15) NULL,
        private string _employeeDateSigned; //[EmployeeDateSigned] [varchar](50) NULL,
        //[TStamp] [datetime] NULL
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
        public string PsychiatricEvaluation
        {
            get
            {
                return _psychiatricEvaluation;
            }
            set
            {
                _psychiatricEvaluation = value;
            }
        }
        public string SocialHistory
        {
            get
            {
                return _socialHistory;
            }
            set
            {
                _socialHistory = value;
            }
        }
        public string TreatmentSummary
        {
            get
            {
                return _treatmentSummary;
            }
            set
            {
                _treatmentSummary = value;
            }
        }
        public string XRayCTMRIReports
        {
            get
            {
                return _xRayCTMRIReports;
            }
            set
            {
                _xRayCTMRIReports = value;
            }
        }

    }
}
