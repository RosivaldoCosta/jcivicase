using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class MCTDispatch : Form
    {
        private Int64 _mctDispatchID = Convert.ToInt64(DateTime.Now.TimeOfDay.TotalMilliseconds);
        private string _staffIN; //[StaffIN] [varchar](50) NULL,
        private string _respondingEmpleeID;//[RespondingEmpleeID] [varchar](20) NULL,
        private DateTime _date; //[Date] [datetime] NULL,
        private DateTime _DispatchTime; // [datetime] NULL,
        private DateTime _ArrivalTime; //[ArrivalTime] [datetime] NULL,
        private DateTime _Time; //[Time] [datetime] NULL,
        private string _Team;//[Team] [varchar](50) NULL,
        private string _TotalTime; //[TotalTime] [datetime] NULL,
        private int _mileage; //[Mileage] [int] NULL,
        private string _OthersInvolved;//[OthersInvolved] [varchar](255) NULL,
        private string _Information;//[Information] [varchar](255) NULL,
        private string _Enviromental;//[Enviromental] [varchar](255) NULL,
        private string _Additional;//[Additional] [varchar](2048) NULL,
        private string _Jail;//[Jail] [varchar](2) NULL,
        private string _AssistEP;//[AssistEP] [varchar](2) NULL,
        private string _VoluntaryER;//[VoluntaryER] [varchar](2) NULL,
        private string _CPSReferral;//[CPSReferral] [varchar](2) NULL,
        private string _APSReferral;//[APSReferral] [varchar](2) NULL,
        private string _CrisisBedReferral;//[CrisisBedReferral] [varchar](2) NULL,
        private string _UCCAppointment;//[UCCAppointment] [varchar](2) NULL,
        private string _IHITReferral;//[IHITReferral] [varchar](2) NULL,
        private string _PHPReferral;//[PHPReferral] [varchar](2) NULL,
        private string _IOPReferral;//[IOPReferral] [varchar](2) NULL,
        private string _TCMReferral;//[TCMReferral] [varchar](2) NULL,
        private string _OutPatientReferral;
        private string _HotelVoucher;//[HotelVoucher] [varchar](2) NULL,
        private string _ShelterVoucher;//[ShelterVoucher] [varchar](2) NULL,
        private string _CrisisPlan;//[CrisisPlan] [varchar](2) NULL,
        private string _NonMHReferral;//[NonMHReferral] [varchar](2) NULL,
        private string _SituationalStable;//[SituationalStable] [varchar](2) NULL,
        private string _MCTTransportation;//[MCTTransportation] [varchar](2) NULL,
        private string _MCTFollowUp;//[MCTFollowUp] [varchar](2) NULL,
        private string _ClientRefused;//[ClientRefused] [varchar](2) NULL,
        private string _ContactPolice;//[ContactPolice] [varchar](2) NULL,
        private string _verbal;//[verbal] [varchar](2) NULL,
        private string _Agency;//[Agency] [varchar](2) NULL,
        private string _ContactEMT;//[ContactEMT] [varchar](2) NULL,
        private string _diversion;//[diversion] [varchar](2) NULL,
        private string _Escort;//[Escort] [varchar](2) NULL,
        private string _ClientEducation;//[ClientEducation] [varchar](2) NULL,
        private string _physical;//[physical] [varchar](2) NULL,
        private string _Family;//[Family] [varchar](2) NULL,
        private string _Evaluation;//[Evaluation] [varchar](2) NULL,
        private string _InterventionEmergencyPetition;//[InterventionEmergencyPetition] [varchar](2) NULL,
        private string _Precinct;//[Precinct] [varchar](50) NULL,
        private string _Other;//[Other] [varchar](255) NULL,
        private string _Stable;//[Stable] [varchar](2) NULL,
        private string _StableW;//[StableW] [varchar](2) NULL,
        private string _Deteriorating;//[Deteriorating] [varchar](2) NULL,
        private string _AtRisk;//[AtRisk] [varchar](2) NULL,
        private string _WithPolice;//[WithPolice] [varchar](2) NULL,
        private string _ER;//[ER] [varchar](2) NULL,
        private string _Diagnostic;//[Diagnostic] [varchar](50) NULL,
        private string _StaffID;//[StaffID] [varchar](20) NULL,
        private string _location;//[location] [varchar](50) NULL,
        private string _dispatch;//[dispatch] [varchar](10) NULL,
        private string _intreatment;//[intreatment] [varchar](10) NULL,
        private string _intoxicated;//[intoxicated] [varchar](10) NULL,
        private string _HomicideViolence;//[HomicideViolence] [varchar](10) NULL,
        private string _SuicideAttempt;//[SuicideAttempt] [varchar](10) NULL,
        private string _SuicideIdeation;//[SuicideIdeation] [varchar](10) NULL,
        private string _DeathBySuicide;//[DeathBySuicide] [varchar](10) NULL,
        private string _DeathOtherThanSuicide;//[DeathOtherThanSuicide] [varchar](10) NULL,
        private string _FatalAccident;//[FatalAccident] [varchar](10) NULL,
        private string _SexualAssault;//[SexualAssault] [varchar](10) NULL,
        private string _Depression;//[Depression] [varchar](10) NULL,
        private string _SubstanceAbuse;//[SubstanceAbuse] [varchar](10) NULL,
        private string _ChildWithSignificantIllness;//[ChildWithSignificantIllness] [varchar](10) NULL,
        private string _ChildBehavoir;//[ChildBehavoir] [varchar](10) NULL,
        private string _FamilyConflict;//[FamilyConflict] [varchar](10) NULL,
        private string _maritalConflict;//[maritalConflict] [varchar](10) NULL,
        private string _DomesticViolence;//[DomesticViolence] [varchar](10) NULL,
        private string _ChildAbuse;//[ChildAbuse] [varchar](10) NULL,
        private string _Runaway;//[Runaway] [varchar](10) NULL,
        private string _ChronicMI;//[ChronicMI] [varchar](10) NULL,
        private string _EmergencyPetition;//[EmergencyPetition] [varchar](10) NULL,
        private string _MedicalIssuePrimary;//[MedicalIssuePrimary] [varchar](10) NULL,
        private string _ElderlyConfusion;//[ElderlyConfusion] [varchar](10) NULL,
        private string _Homeless;//[Homeless] [varchar](10) NULL,
        private string _SituationalCrisis;//[SituationalCrisis] [varchar](10) NULL,
        private string _Anxiety;//[Anxiety] [varchar](10) NULL,
        private string _Financial;//[Financial] [varchar](10) NULL,
        private string _CommunityResources;//[CommunityResources] [varchar](10) NULL,
        private string _OtherDesc;//[OtherDesc] [varchar](50) NULL,
        private string _FocalOther;//[FocalOther] [varchar](10) NULL,
        private string _UserName;
        private string _axis1;
        private string _axis2;
        private string _axis3;
        private string _axis4;
        private string _axis5;
        private string _divertedCall;
        private string _dispatchedBy;
        private string _county;
        

        public string County
        {
            get { return _county; }
            set { _county = value; }
        }

        public string DivertedCall
        {
            get { return _divertedCall; }
            set { _divertedCall = value; }
        }

        public string DispatchedBy
        {
            get { return _dispatchedBy; }
            set { _dispatchedBy = value; }
        }

        public string Axis1
        {
            get { return _axis1; }
            set { _axis1 = value; }
        }

        public string Axis2
        {
            get { return _axis2; }
            set { _axis2 = value; }
        }

        public string Axis3
        {
            get { return _axis3; }
            set { _axis3 = value; }
        }

        public string Axis4
        {
            get { return _axis4; }
            set { _axis4 = value; }
        }

        public string Axis5
        {
            get { return _axis5; }
            set { _axis5 = value; }
        }

        public string UserName
        {
            get { return _UserName;  }
            set { _UserName = value; }
        }


        public DateTime Date
        {
            get { return _date; }
            set { _date = value; }
        }


        public string Additional
        {
            get
            {
                return _Additional;
            }
            set
            {
                _Additional = value;
            }
        }
        public string Agency
        {
            get
            {
                return _Agency;
            }
            set
            {
                _Agency = value;
            }
        }
        public string Anxiety
        {
            get
            {
                return _Anxiety;
            }
            set
            {
                _Anxiety = value;
            }
        }
        public string APSReferral
        {
            get
            {
                return _APSReferral;
            }
            set
            {
                _APSReferral = value;
            }
        }
        public DateTime ArrivalTime
        {
            get
            {
                return _ArrivalTime;
            }
            set
            {
                _ArrivalTime = value;
            }
        }
        public string AssistEP
        {
            get
            {
                return _AssistEP;
            }
            set
            {
                _AssistEP = value;
            }
        }
        public string AtRisk
        {
            get
            {
                return _AtRisk;
            }
            set
            {
                _AtRisk = value;
            }
        }
        public string ChildAbuse
        {
            get
            {
                return _ChildAbuse;
            }
            set
            {
                _ChildAbuse = value;
            }
        }
        public string ChildBehavoir
        {
            get
            {
                return _ChildBehavoir;
            }
            set
            {
                _ChildBehavoir = value;
            }
        }
        public string ChildWithSignificantIllness
        {
            get
            {
                return _ChildWithSignificantIllness;
            }
            set
            {
                _ChildWithSignificantIllness = value;
            }
        }
        public string ChronicMI
        {
            get
            {
                return _ChronicMI;
            }
            set
            {
                _ChronicMI = value;
            }
        }
        public string ClientEducation
        {
            get
            {
                return _ClientEducation;
            }
            set
            {
                _ClientEducation = value;
            }
        }
        public string ClientRefused
        {
            get
            {
                return _ClientRefused;
            }
            set
            {
                _ClientRefused = value;
            }
        }
        public string CommunityResources
        {
            get
            {
                return _CommunityResources;
            }
            set
            {
                _CommunityResources = value;
            }
        }
        public string ContactEMT
        {
            get
            {
                return _ContactEMT;
            }
            set
            {
                _ContactEMT = value;
            }
        }
        public string ContactPolice
        {
            get
            {
                return _ContactPolice;
            }
            set
            {
                _ContactPolice = value;
            }
        }
        public string CPSReferral
        {
            get
            {
                return _CPSReferral;
            }
            set
            {
                _CPSReferral = value;
            }
        }
        public string CrisisBedReferral
        {
            get
            {
                return _CrisisBedReferral;
            }
            set
            {
                _CrisisBedReferral = value;
            }
        }
        public string CrisisPlan
        {
            get
            {
                return _CrisisPlan;
            }
            set
            {
                _CrisisPlan = value;
            }
        }
        public string DeathBySuicide
        {
            get
            {
                return _DeathBySuicide;
            }
            set
            {
                _DeathBySuicide = value;
            }
        }
        public string DeathOtherThanSuicide
        {
            get
            {
                return _DeathOtherThanSuicide;
            }
            set
            {
                _DeathOtherThanSuicide = value;
            }
        }
        public string Depression
        {
            get
            {
                return _Depression;
            }
            set
            {
                _Depression = value;
            }
        }
        public string Deteriorating
        {
            get
            {
                return _Deteriorating;
            }
            set
            {
                _Deteriorating = value;
            }
        }
        public string Diagnostic
        {
            get
            {
                return _Diagnostic;
            }
            set
            {
                _Diagnostic = value;
            }
        }
        public string dispatch
        {
            get
            {
                return _dispatch;
            }
            set
            {
                _dispatch = value;
            }
        }
        public DateTime DispatchTime
        {
            get
            {
                return _DispatchTime;
            }
            set
            {
                _DispatchTime = value;
            }
        }
        public string diversion
        {
            get
            {
                return _diversion;
            }
            set
            {
                _diversion = value;
            }
        }
        public string DomesticViolence
        {
            get
            {
                return _DomesticViolence;
            }
            set
            {
                _DomesticViolence = value;
            }
        }
        public string ElderlyConfusion
        {
            get
            {
                return _ElderlyConfusion;
            }
            set
            {
                _ElderlyConfusion = value;
            }
        }
        public string EmergencyPetition
        {
            get
            {
                return _EmergencyPetition;
            }
            set
            {
                _EmergencyPetition = value;
            }
        }
        public string Enviromental
        {
            get
            {
                return _Enviromental;
            }
            set
            {
                _Enviromental = value;
            }
        }
        public string ER
        {
            get
            {
                return _ER;
            }
            set
            {
                _ER = value;
            }
        }
        public string Escort
        {
            get
            {
                return _Escort;
            }
            set
            {
                _Escort = value;
            }
        }
        public string Evaluation
        {
            get
            {
                return _Evaluation;
            }
            set
            {
                _Evaluation = value;
            }
        }
        public string Family
        {
            get
            {
                return _Family;
            }
            set
            {
                _Family = value;
            }
        }
        public string FamilyConflict
        {
            get
            {
                return _FamilyConflict;
            }
            set
            {
                _FamilyConflict = value;
            }
        }
        public string FatalAccident
        {
            get
            {
                return _FatalAccident;
            }
            set
            {
                _FatalAccident = value;
            }
        }
        public string Financial
        {
            get
            {
                return _Financial;
            }
            set
            {
                _Financial = value;
            }
        }
        public string FocalOther
        {
            get
            {
                return _FocalOther;
            }
            set
            {
                _FocalOther = value;
            }
        }
        public string Homeless
        {
            get
            {
                return _Homeless;
            }
            set
            {
                _Homeless = value;
            }
        }
        public string HomicideViolence
        {
            get
            {
                return _HomicideViolence;
            }
            set
            {
                _HomicideViolence = value;
            }
        }
        public string HotelVoucher
        {
            get
            {
                return _HotelVoucher;
            }
            set
            {
                _HotelVoucher = value;
            }
        }
        public string IHITReferral
        {
            get
            {
                return _IHITReferral;
            }
            set
            {
                _IHITReferral = value;
            }
        }
        public string Information
        {
            get
            {
                return _Information;
            }
            set
            {
                _Information = value;
            }
        }
        public string InterventionEmergencyPetition
        {
            get
            {
                return _InterventionEmergencyPetition;
            }
            set
            {
                _InterventionEmergencyPetition = value;
            }
        }
        public string intoxicated
        {
            get
            {
                return _intoxicated;
            }
            set
            {
                _intoxicated = value;
            }
        }
        public string intreatment
        {
            get
            {
                return _intreatment;
            }
            set
            {
                _intreatment = value;
            }
        }
        public string IOPReferral
        {
            get
            {
                return _IOPReferral;
            }
            set
            {
                _IOPReferral = value;
            }
        }
        public string Jail
        {
            get
            {
                return _Jail;
            }
            set
            {
                _Jail = value;
            }
        }
        public string location
        {
            get
            {
                return _location;
            }
            set
            {
                _location = value;
            }
        }
        public string maritalConflict
        {
            get
            {
                return _maritalConflict;
            }
            set
            {
                _maritalConflict = value;
            }
        }
        public Int64 MCTDispatchID
        {
            get
            {
                return _mctDispatchID;
            }
            set
            {
                _mctDispatchID = value;
            }
        }
        public string MCTFollowUp
        {
            get
            {
                return _MCTFollowUp;
            }
            set
            {
                _MCTFollowUp = value;
            }
        }
        public string MCTTransportation
        {
            get
            {
                return _MCTTransportation;
            }
            set
            {
                _MCTTransportation = value;
            }
        }
        public string MedicalIssuePrimary
        {
            get
            {
                return _MedicalIssuePrimary;
            }
            set
            {
                _MedicalIssuePrimary = value;
            }
        }
        public int Mileage
        {
            get
            {
                return _mileage;
            }
            set
            {
                _mileage = value;
            }
        }
        public string NonMHReferral
        {
            get
            {
                return _NonMHReferral;
            }
            set
            {
                _NonMHReferral = value;
            }
        }
        public string Other
        {
            get
            {
                return _Other;
            }
            set
            {
                _Other = value;
            }
        }
        public string OtherDesc
        {
            get
            {
                return _OtherDesc;
            }
            set
            {
                _OtherDesc = value;
            }
        }
        public string OthersInvolved
        {
            get
            {
                return _OthersInvolved;
            }
            set
            {
                _OthersInvolved = value;
            }
        }
        public string OutPatientReferral
        {
            get
            {
                return _OutPatientReferral;
            }
            set
            {
                _OutPatientReferral = value;
            }
        }
        public string PHPReferral
        {
            get
            {
                return _PHPReferral;
            }
            set
            {
                _PHPReferral = value;
            }
        }
        public string physical
        {
            get
            {
                return _physical;
            }
            set
            {
                _physical = value;
            }
        }
        public string Precinct
        {
            get
            {
                return _Precinct;
            }
            set
            {
                _Precinct = value;
            }
        }
        public string RespondingEmployeeID
        {
            get
            {
                return _respondingEmpleeID;
            }
            set
            {
                _respondingEmpleeID = value;
            }
        }
        public string Runaway
        {
            get
            {
                return _Runaway;
            }
            set
            {
                _Runaway = value;
            }
        }
        public string SexualAssault
        {
            get
            {
                return _SexualAssault;
            }
            set
            {
                _SexualAssault = value;
            }
        }
        public string ShelterVoucher
        {
            get
            {
                return _ShelterVoucher;
            }
            set
            {
                _ShelterVoucher = value;
            }
        }
        public string SituationalCrisis
        {
            get
            {
                return _SituationalCrisis;
            }
            set
            {
                _SituationalCrisis = value;
            }
        }
        public string SituationalStable
        {
            get
            {
                return _SituationalStable;
            }
            set
            {
                _SituationalStable = value;
            }
        }
        public string Stable
        {
            get
            {
                return _Stable;
            }
            set
            {
                _Stable = value;
            }
        }
        public string StableW
        {
            get
            {
                return _StableW;
            }
            set
            {
                _StableW = value;
            }
        }
        public string StaffID
        {
            get
            {
                return _StaffID;
            }
            set
            {
                _StaffID = value;
            }
        }
        public string StaffIN
        {
            get
            {
                return _staffIN;
            }
            set
            {
                _staffIN = value;
            }
        }
        public string SubstanceAbuse
        {
            get
            {
                return _SubstanceAbuse;
            }
            set
            {
                _SubstanceAbuse = value;
            }
        }
        public string SuicideAttempt
        {
            get
            {
                return _SuicideAttempt;
            }
            set
            {
                _SuicideAttempt = value;
            }
        }
        public string SuicideIdeation
        {
            get
            {
                return _SuicideIdeation;
            }
            set
            {
                _SuicideIdeation = value;
            }
        }
        public string TCMReferral
        {
            get
            {
                return _TCMReferral;
            }
            set
            {
                _TCMReferral = value;
            }
        }
        public string Team
        {
            get
            {
                return _Team;
            }
            set
            {
                _Team = value;
            }
        }
        public DateTime Time
        {
            get
            {
                return _Time;
            }
            set
            {
                _Time = value;
            }
        }
        public string TotalTime
        {
            get
            {
                return _TotalTime;
            }
            set
            {
                _TotalTime = value;
            }
        }
        public string UCCAppointment
        {
            get
            {
                return _UCCAppointment;
            }
            set
            {
                _UCCAppointment = value;
            }
        }
        public string verbal
        {
            get
            {
                return _verbal;
            }
            set
            {
                _verbal = value;
            }
        }
        public string VoluntaryER
        {
            get
            {
                return _VoluntaryER;
            }
            set
            {
                _VoluntaryER = value;
            }
        }
        public string WithPolice
        {
            get
            {
                return _WithPolice;
            }
            set
            {
                _WithPolice = value;
            }
        }

    }
}
