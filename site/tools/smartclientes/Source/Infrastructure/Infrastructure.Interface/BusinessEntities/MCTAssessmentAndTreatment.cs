using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class MCTAssessmentAndTreatment : Form
    {
        private DateTime _timeStamp;
        private Int64 _MCTAssessmentAndTreatmentID = Convert.ToInt64(DateTime.Now.TimeOfDay.TotalMilliseconds);//[MCTAssessmentAndTreatmentID] [bigint] NOT NULL,
        private string _clinician; //[Clinician] [varchar](50) NULL,
        private string _problem; //[Problem] [varchar](255) NULL,
        private string _riskAssessment; //[RiskAssessment] [varchar](255) NULL,
        private string _psychHistory; //[PsychHistory] [varchar](255) NULL,
        private string _psychMeds; //[PsychMeds] [varchar](255) NULL,
        private string _psychTreatment; //[PsychTreatment] [varchar](255) NULL,
        private string _substanceTreatment; //[SubstanceTreatment] [varchar](255) NULL,
        private string _support; //[Support] [varchar](255) NULL,
        private string _legal; //[Legal] [varchar](255) NULL,
        private string _medical; //[Medical] [varchar](255) NULL,
        private string _somatic; //[Somatic] [varchar](255) NULL,
        private string _mental; //[Mental] [varchar](255) NULL,
        private string _strengths; //[Strengths] [varchar](255) NULL,
        private string _diagnosticImpression; //[DiagnosticImpression] [varchar](255) NULL,
        private string _diagnosticClient; //[DiagnosticClient] [varchar](255) NULL,
        private string _axis1; //[Axis1] [varchar](100) NULL,
        private string _axis2; //[Axis2] [varchar](100) NULL,
        private string _axis3; //[Axis3] [varchar](100) NULL,
        private string _axis4; //[Axis4] [varchar](100) NULL,
        private string _axis5; //[Axis5] [varchar](100) NULL,
        private string _crisisPlan; //[CrisisPlan] [varchar](255) NULL,
        private string _treatment; //[Treatment] [varchar](255) NULL,
        private string _erEval;//[ErEval] [varchar](10) NULL,
        private string _inptadm;//[inptadm] [varchar](10) NULL,
        private string _crisisBed;//[CrisisBed] [varchar](10) NULL,
        private string _sa;//[SA] [varchar](10) NULL,
        private string _ohmc;//[OHMC] [varchar](10) NULL,
        private string _php;//[PHP] [varchar](10) NULL,
        private string _iop;//[IOP] [varchar](10) NULL,
        private string _prp;//[PRP] [varchar](10) NULL,
        private string _targeted;//[Targeted] [varchar](10) NULL,
        private string _pyschEval;//[PsychEval] [varchar](10) NULL,
        private string _medicalEval;//[MedicalEval] [varchar](10) NULL,
        private string _mobile;//[Mobile] [varchar](10) NULL,
        private string _cotaa;//[COTAA] [varchar](10) NULL,
        private string _ifit;//[IFIT] [varchar](10) NULL,
        private string _dss;//[DSS] [varchar](10) NULL,
        private string _aps;//[APS] [varchar](10) NULL,
        private string _cps;//[CPS] [varchar](10) NULL,
        private string _other;//[Other] [varchar](10) NULL,
        private string _comments;//[Comments] [varchar](255) NULL,
        private int _active = 1;
        private string _county;

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


        public string APS
        {
            get
            {
                return _aps;
            }
            set
            {
                _aps = value;
            }
        }
        public string Axis1
        {
            get
            {
                return _axis1;
            }
            set
            {
                _axis1 = value;
            }
        }
        public string Axis2
        {
            get
            {
                return _axis2;
            }
            set
            {
                _axis2 = value;
            }
        }
        public string Axis3
        {
            get
            {
                return _axis3;
            }
            set
            {
                _axis3 = value;
            }
        }
        public string Axis4
        {
            get
            {
                return _axis4;
            }
            set
            {
                _axis4 = value;
            }
        }
        public string Axis5
        {
            get
            {
                return _axis5;
            }
            set
            {
                _axis5 = value;
            }
        }
        //private string _depart;//[Depart] [varchar](15) NULL,
        //private string _treatment;//[TimeStamp] [datetime] NULL,
        //private string _treatmen;//[tstamp] [datetime] NULL CONSTRAINT [DF_MCTAssessmentAndTreatment_tstamp]  DEFAULT (getdate()),
        //private string _cstamp;//[cstamp] [datetime] NULL,
        //private string _active;//[Active] [bit] NULL,
        //private string _id;//[ID] [bigint] IDENTITY(1,1) NOT NULL
        public string Clinician
        {
            get
            {
                return _clinician;
            }
            set
            {
                _clinician = value;
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
        public string COTAA
        {
            get
            {
                return _cotaa;
            }
            set
            {
                _cotaa = value;
            }
        }
        public string CPS
        {
            get
            {
                return _cps;
            }
            set
            {
                _cps = value;
            }

        }
        public string CrisisBed
        {
            get
            {
                return _crisisBed;
            }
            set
            {
                _crisisBed = value;
            }
        }
        public string CrisisPlan
        {
            get
            {
                return _crisisPlan;
            }
            set
            {
                _crisisPlan = value;
            }
        }
        public string DiagnosticClient
        {
            get
            {
                return _diagnosticClient;
            }
            set
            {
                _diagnosticClient = value;
            }
        }
        public string DiagnosticImpression
        {
            get
            {
                return _diagnosticImpression;
            }
            set
            {
                _diagnosticImpression = value;
            }
        }
        public string DSS
        {
            get
            {
                return _dss;
            }
            set
            {
                _dss = value;
            }
        }
        public string ErEval
        {
            get
            {
                return _erEval;
            }
            set
            {
                _erEval = value;
            }
        }
        public string IFIT
        {
            get
            {
                return _ifit;
            }
            set
            {
                _ifit = value;
            }
        }
        public string inptadm
        {
            get
            {
                return _inptadm;
            }
            set
            {
                _inptadm = value;
            }
        }
        public string IOP
        {
            get
            {
                return _iop;
            }
            set
            {
                _iop = value;
            }
        }
        public string Legal
        {
            get
            {
                return _legal;
            }
            set
            {
                _legal = value;
            }
        }
        public Int64 MCTAssessmentAndTreatmentID
        {
            get
            {
                return _MCTAssessmentAndTreatmentID;
            }
            set
            {
                _MCTAssessmentAndTreatmentID = value;
            }
        }
        public string Medical
        {
            get
            {
                return _medical;
            }
            set
            {
                _medical = value;
            }
        }
        public string MedicalEval
        {
            get
            {
                return _medicalEval;
            }
            set
            {
                _medicalEval = value;
            }
        }
        public string Mental
        {
            get
            {
                return _mental;
            }
            set
            {
                _mental = value;
            }
        }
        public string Mobile
        {
            get
            {
                return _mobile;
            }
            set
            {
                _mobile = value;
            }
        }
        public string OHMC
        {
            get
            {
                return _ohmc;
            }
            set
            {
                _ohmc = value;
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
        public string PHP
        {
            get
            {
                return _php;
            }
            set
            {
                _php = value;
            }
        }
        public string Problem
        {
            get
            {
                return _problem;
            }
            set
            {
                _problem = value;
            }
        }
        public string PRP
        {
            get
            {
                return _prp;
            }
            set
            {
                _prp = value;
            }
        }
        public string PsychHistory
        {
            get
            {
                return _psychHistory;
            }
            set
            {
                _psychHistory = value;
            }
        }
        public string PsychMeds
        {
            get
            {
                return _psychMeds;
            }
            set
            {
                _psychMeds = value;
            }
        }
        public string PsychTreatment
        {
            get
            {
                return _psychTreatment;
            }
            set
            {
                _psychTreatment = value;
            }
        }
        public string PsychEval
        {
            get
            {
                return _pyschEval;
            }
            set
            {
                _pyschEval = value;
            }
        }
        public string RiskAssessment
        {
            get
            {
                return _riskAssessment;
            }
            set
            {
                _riskAssessment = value;
            }
        }
        public string SA
        {
            get
            {
                return _sa;
            }
            set
            {
                _sa = value;
            }
        }
        public string Somatic
        {
            get
            {
                return _somatic;
            }
            set
            {
                _somatic = value;
            }
        }
        public string Strengths
        {
            get
            {
                return _strengths;
            }
            set
            {
                _strengths = value;
            }
        }
        public string SubstanceTreatment
        {
            get
            {
                return _substanceTreatment;
            }
            set
            {
                _substanceTreatment = value;
            }
        }
        public string Support
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
        public string Targeted
        {
            get
            {
                return _targeted;
            }
            set
            {
                _targeted = value;
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
