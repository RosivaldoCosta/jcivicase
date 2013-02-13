using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class Intake : Form
    {
        private int _IntakeID = new Random().Next();
        private string _state;


        public string AbuseOccuring
        {
            get
            {
                return _abuseOccuring;
            }
            set
            {
                _abuseOccuring = value;
            }
        }
        public string AbuseThreatened
        {
            get
            {
                return _abuseThreatened;
            }
            set
            {
                _abuseThreatened = value;
            }
        }
        public string Address
        {
            get
            {
                return _address;
            }
            set
            {
                _address = value;
            }
        }
        public string AdultInHouse
        {
            get
            {
                return _adultInHouse;
            }
            set
            {
                _adultInHouse = value;
            }
        }
        public string AdultRelationship
        {
            get
            {
                return _adultRelationship;
            }
            set
            {
                _adultRelationship = value;
            }
        }
        public string AdultWho
        {
            get
            {
                return _adultWho;
            }
            set
            {
                _adultWho = value;
            }
        }
        public int Age
        {
            get
            {
                return _age;
            }
            set
            {
                _age = value;
            }
        }
        public string AuditedBy
        {
            get
            {
                return _auditedBy;
            }
            set
            {
                _auditedBy = value;
            }
        }
        public string Called911
        {
            get
            {
                return _called911;
            }
            set
            {
                _called911 = value;
            }
        }
        public string CallerAddress
        {
            get
            {
                return _callerAddress;
            }
            set
            {
                _callerAddress = value;
            }
        }
        public string CallerCity
        {
            get
            {
                return _callerCity;
            }
            set
            {
                _callerCity = value;
            }
        }
        public string CallerDisconnects
        {
            get
            {
                return _callerDisconnects;
            }
            set
            {
                _callerDisconnects = value;
            }
        }
        public string CallerName
        {
            get
            {
                return _callerName;
            }
            set
            {
                _callerName = value;
            }
        }
        public string CallerPhone
        {
            get
            {
                return _callerPhone;
            }
            set
            {
                _callerPhone = value;
            }
        }
        public string CallerRefuse
        {
            get
            {
                return _callerRefuse;
            }

            set
            {
            	_callerRefuse = value;
            }
        }
        public string CallerState
        {
            get
            {
                return _callerState;
            }
            set
            {
                _callerState = value;
            }
        }
        public string CallerZip
        {
            get
            {
                return _callerZip;
            }
            set
            {
                _callerZip = value;
            }
        }
        public string ChildrenInHouse
        {
            get
            {
                return _childrenInHouse;
            }
            set
            {
                _childrenInHouse = value;
            }
        }
        public string ChildrenRelationship
        {
            get
            {
                return _childrenRelationship;
            }
            set
            {
                _childrenRelationship = value;
            }
        }
        public string ChildrenWho
        {
            get
            {
                return _childrenWho;
            }
            set
            {
                _childrenWho = value;
            }
        }
        public string Cism
        {
            get
            {
                return _cism;
            }
            set
            {
                _cism = value;
            }
        }
        public string City
        {
            get
            {
                return _city;
            }
            set
            {
                _city = value;
            }
        }
        public string ClientsName
        {
            get
            {
                return _clientsName;
            }
            set
            {
                _clientsName = value;
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
        public string CommunityEd
        {
            get
            {
                return _communityEd;
            }

            set
            {
            	_crisisPlanOnly = value;
            }
        }
        public string CrisisPlanOnly
        {
            get
            {
                return _crisisPlanOnly;
            }

            set
            {
            	_crisisPlanOnly = value;
            }
        }
        public string Culture
        {
            get
            {
                return _culture;
            }
            set
            {
                _culture = value;
            }
        }
        public string DangerToOthers
        {
            get
            {
                return _dangerToOthers;
            }
            set
            {
                _dangerToOthers = value;
            }
        }
        public string DangerToSelf
        {
            get
            {
                return _dangerToSelf;
            }
            set
            {
                _dangerToSelf = value;
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

        
        public string District
        {
            get
            {
                return _district;
            }
            set
            {
                _district = value;
            }
        }
        public DateTime Dob
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
        public string Drugs
        {
            get
            {
                return _drugs;
            }
            set
            {
                _drugs = value;
            }
        }
        public string DrugUse
        {
            get
            {
                return _drugUse;
            }
            set
            {
                _drugUse = value;
            }
        }
        public string EmergencyName
        {
            get
            {
                return _emergencyName;
            }
            set
            {
                _emergencyName = value;
            }
        }
        public string EmergencyPhone
        {
            get
            {
                return _emergencyPhone;
            }
            set
            {
                _emergencyPhone = value;
            }
        }
        public string EmergencyRelationship
        {
            get
            {
                return _emergencyRelationship;
            }
            set
            {
                _emergencyRelationship = value;
            }
        }
        public string Eviction
        {
            get
            {
                return _eviction;
            }
            set
            {
                _eviction = value;
            }
        }
        public string Financial
        {
            get
            {
                return _financial;
            }
            set
            {
                _financial = value;
            }
        }
        public string PFirstName
        {
            get
            {
                return _firstname;
            }
            set
            {
                _firstname = value;
            }
        }
        public string Frequent
        {
            get
            {
                return _frequent;
            }
            set
            {
                _frequent = value;
            }
        }
        public string GunType
        {
            get
            {
                return _gunType;
            }
            set
            {
                _gunType = value;
            }
        }
        public string HistoryOfViolence
        {
            get
            {
                return _historyOfViolence;
            }
            set
            {
                _historyOfViolence = value;
            }
        }
        public string Incarceration
        {
            get
            {
                return _incarceration;
            }
            set
            {
                _incarceration = value;
            }
        }
        public string InformationOnly
        {
            get
            {
                return _informationOnly;
            }

            set
            {
            	_informationOnly = value;
            }
        }
        public string InpatientHospital
        {
            get
            {
                return _inpatientHospital;
            }
            set
            {
                _inpatientHospital = value;
            }
        }
        public string InsuranceCompany
        {
            get
            {
                return _insuranceCompany;
            }
            set
            {
                _insuranceCompany = value;
            }
        }
        public string InsuranceID
        {
            get
            {
                return _insuranceID;
            }
            set
            {
                _insuranceID = value;
            }
        }
        public string InsuranceOther
        {
            get
            {
                return _insuranceOther;
            }
            set
            {
                _insuranceOther = value;
            }
        }
        public string InsurancePhone
        {
            get
            {
                return _insurancePhone;
            }
            set
            {
                _insurancePhone = value;
            }
        }
        public int IntakeID
        {
            get { return _IntakeID; }
            set { _IntakeID = value; }
        }

        private DateTime _date;
        private string _time;

        public string Intoxication
        {
            get
            {
                return _intoxication;
            }
            set
            {
                _intoxication = value;
            }
        }
        public string IntoxicationDesc
        {
            get
            {
                return _intoxicationDesc;
            }
            set
            {
                _intoxicationDesc = value;
            }
        }
        public string PlastName
        {
            get
            {
                return _lastname;
            }
            set
            {
                _lastname = value;
            }
        }
        public string Limitations
        {
            get
            {
                return _limitations;
            }

            set
            {
            	_limitations = value;
            }
        }
        public string MctDispatch
        {
            get
            {
                return _mctDispatch;
            }
            set
            {
                _mctDispatch = value;
            }
        }
        public string MedicalDesc
        {
            get
            {
                return _medicalDesc;
            }
            set
            {
                _medicalDesc = value;
            }
        }
        public string MedicalIssues
        {
            get
            {
                return _medicalIssues;
            }
            set
            {
                _medicalIssues = value;
            }
        }
        public string Message
        {
            get
            {
                return _message;
            }
            set
            {
                _message = value;
            }
        }
        public string MH
        {
            get
            {
                return _mH;
            }
            set
            {
                _mH = value;
            }
        }
        public string MhNeeded
        {
            get
            {
                return _mhNeeded;
            }
            set
            {
                _mhNeeded = value;
            }
        }
        public string NeedsMental
        {
            get
            {
                return _needsMental;
            }
            set
            {
                _needsMental = value;
            }
        }
        public string NonMH
        {
            get
            {
                return _nonMH;
            }
            set
            {
                _nonMH = value;
            }
        }
        public string NotifyAPS
        {
            get
            {
                return _notifyAPS;
            }
            set
            {
                _notifyAPS = value;
            }
        }
        public string Officer
        {
            get
            {
                return _officer;
            }
            set
            {
                _officer = value;
            }
        }
        public string OperatorNum
        {
            get
            {
                return _operatorNum;
            }
            set
            {
                _operatorNum = value;
            }
        }
        public string OperatorTime
        {
            get
            {
                return _operatorTime;
            }
            set
            {
                _operatorTime = value;
            }
        }
        public string OtherMed
        {
            get
            {
                return _otherMed;
            }
            set
            {
                _otherMed = value;
            }
        }
        public string Pcp
        {
            get
            {
                return _pcp;
            }
            set
            {
                _pcp = value;
            }
        }
        public string PcpPhone
        {
            get
            {
                return _pcpPhone;
            }
            set
            {
                _pcpPhone = value;
            }
        }
        public string Phone
        {
            get
            {
                return _phone;
            }
            set
            {
                _phone = value;
            }
        }
        public string PhoneBogus
        {
            get
            {
                return _phoneBogus;
            }

            set
            {
            	_phoneBogus = value;
            }
        }
        public string Provider
        {
            get
            {
                return _provider;
            }
            set
            {
                _provider = value;
            }
        }
        public string ProviderContact
        {
            get
            {
                return _providerContact;
            }
            set
            {
                _providerContact = value;
            }
        }
        public string ProviderPhone
        {
            get
            {
                return _providerPhone;
            }
            set
            {
                _providerPhone = value;
            }
        }
        public string RapidDeterioration
        {
            get
            {
                return _rapidDeterioration;
            }
            set
            {
                _rapidDeterioration = value;
            }
        }
        public string ReasonForContact
        {
            get
            {
                return _reasonForContact;
            }
            set
            {
                _reasonForContact = value;
            }
        }
        public string ReferralOther
        {
            get
            {
                return _referalOther;
            }
            set
            {
                _referalOther = value;
            }
        }
        public string RefferalSource
        {
            get
            {
                return _referalSource;
            }
            set
            {
                _referalSource = value;
            }
        }
        public string Relationship
        {
            get
            {
                return _relationship;
            }
            set
            {
                _relationship = value;
            }
        }
        public string Release
        {
            get
            {
                return _release;
            }
        }
        public string Resident
        {
            get
            {
                return _resident;
            }
            set
            {
                _resident = value;
            }
        }
        public string RfNone
        {
            get
            {
                return _rfNone;
            }

            set
            {
            	_rfNone = value;
            }
        }
        public string RiskHospitalization
        {
            get
            {
                return _riskHospitalization;
            }
            set
            {
            	_riskHospitalization = value;
            }
        }
        public string SituationalCrisis
        {
            get
            {
                return _situationCrisis;
            }
            set
            {
                _situationCrisis = value;
            }
        }

        public string SSN
        {
            get
            {
                return _ssn;
            }

            set
            {
            	_ssn = value;
            }
        }


        public string state
        {
            get
            {
                return _state;
            }
            set
            {
                _state = value;
            }
        }

        public string SubstanceAbuseP
        {
            get
            {
                return _substanceAbuseP;
            }
            set
            {
                _substanceAbuseP = value;
            }
        }
        public string SuicideAttempt
        {
            get
            {
                return _suicideAttempt;
            }
            set
            {
                _suicideAttempt = value;
            }
        }
        public string SuicideIdeation
        {
            get
            {
                return _suicideIdeation;
            }
            set
            {
                _suicideIdeation = value;
            }
        }
        public string SuicideThreat
        {
            get
            {
                return _suicideThreat;
            }
            set
            {
                _suicideThreat = value;
            }
        }
        public string ThreateningViolence
        {
            get
            {
                return _threateningViolence;
            }
            set
            {
                _threateningViolence = value;
            }
        }

        public string Time
        {
            get { return _time; }
            set { _time = value; }
        }

        public string Sex
        {
            get { return _sex; }
            set { _sex = value; }
        }

       
        private string _veteran;
        private string _resident;
        private string _firstname;
        private string _lastname;
        private string _address;
        private string _city;
        private int _zip;
        private string _phone;
        private string _message;
        private string _ssn;
        private DateTime _dob;
        private int _age;
        private string _callerName;
        private string _callerAddress;
        private string _callerCity;
        private string _callerZip;
        private string _callerState;
        private string _callerPhone;
        private string _relationship;
        private string _financial;
        private string _clientsName;
        private string _culture;
        private string _insuranceCompany;
        private string _insuranceOther;
        private string _insuranceID;
        private string _insurancePhone;
        private string _otherMed;
        private string _emergencyName;
        private string _emergencyPhone;
        private string _emergencyRelationship;
        private string _reasonForContact;
        private string _referalSource;
        private string _referalOther;
        private string _officer;
        private string _district;
        private string _providerContact;
        private string _provider;
        private string _providerPhone;
        private string _pcp;
        private string _pcpPhone;
        private string _threateningViolence;
        private string _historyOfViolence;
        private string _suicideIdeation;
        private string _suicideThreat;
        private string _suicideAttempt;
        private string _intoxication;
        private string _intoxicationDesc;
        private string _drugUse;
        private string _childrenInHouse;
        private string _childrenWho;
        private string _childrenRelationship;
        private string _adultInHouse;
        private string _adultWho;
        private string _adultRelationship;
        private string _abuseThreatened;
        private string _abuseOccuring;
        private string _weaponPresent;
        private string _weaponUsed;
        private string _gunType;
        private string _medicalIssues;
        private string _medicalDesc;
        private string _dangerToSelf;
        private string _dangerToOthers;
        private string _rapidDeterioration;
        private string _needsMental;
        private string _substanceAbuseP;
        private string _situationCrisis;
        private string _cism;
        private string _eviction;
        private string _inpatientHospital;
        private string _incarceration;
        private string _riskHospitalization;
        private string _rfNone;
        private string _informationOnly;
        private string _communityEd;
        private string _crisisPlanOnly;
        private string _called911;
        private string _mctDispatch;
        private string _notifyAPS;
        private string _nonMH;
        private string _mH;
        private string _callerDisconnects;
        private string _frequent;
        private string _callerRefuse;
        private string _phoneBogus;
        private string _release;
        private string _limitations;
        private string _auditedBy;
        private string _drugs;
        private string _depart;
        private string _operatorNum;
        private string _operatorTime;
        private string _comments;
        private string _mhNeeded;
        private string _crisisNeeded;
        private string _crisisPlan;
        private string _record911;
        private string _followUp;
        private string _cismFlag;
        private string _linkage;
        private string _sex;
        private string _caseRelated;
        private string _referral1;
        private string _referral2;
        private string _referral3;
        private string _county;
        private string _hospitalName;
        private string _hospitalDiversion;

        public string HospitalDiversion
        {
            get { return _hospitalDiversion; }
            set { _hospitalDiversion = value; }
        }

        public string HospitalName
        {
            get { return _hospitalName; }
            set { _hospitalName = value; }
        }

        public string County
        {
            get { return _county; }
            set { _county = value; }
        }

        public string Referral3
        {
            get { return _referral3; }
            set { _referral3 = value; }
        }
        private string _referral4;

        public string Referral4
        {
            get { return _referral4; }
            set { _referral4 = value; }
        }
        private string _referral5;

        public string Referral5
        {
            get { return _referral5; }
            set { _referral5 = value; }
        }
        private string _referral6;

        public string Referral6
        {
            get { return _referral6; }
            set { _referral6 = value; }
        }
        private string _referral7;

        public string Referral7
        {
            get { return _referral7; }
            set { _referral7 = value; }
        }
        private string _referral8;

        public string Referral8
        {
            get { return _referral8; }
            set { _referral8 = value; }
        }
        private string _referral9;

        public string Referral9
        {
            get { return _referral9; }
            set { _referral9 = value; }
        }
        private string _referral10;

        public string Referral10
        {
            get { return _referral10; }
            set { _referral10 = value; }
        }

        public string Referral1
        {
            get
            {
                return _referral1;
            }

            set
            {
                _referral1 = value;
            }
        }

        public string Referral2
        {
            get
            {
                return _referral2;
            }

            set
            {
                _referral2 = value;
            }
        }

        public string CrisisNeeded
        {
            get
            {
                return _crisisNeeded;
            }
            set
            {
                _crisisNeeded = value;
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
        public string Record911
        {
            get
            {
                return _record911;
            }
            set
            {
                _record911 = value;
            }
        }
        public string FollowUp
        {
            get
            {
                return _followUp;
            }
            set
            {
                _followUp = value;
            }
        }
        public string CismFlag
        {
            get
            {
                return _cismFlag;
            }
            set
            {
                _cismFlag = value;
            }
        }
        
       
        public string Linkage
        {
            get
            {
                return _linkage;
            }
            set
            {
                _linkage = value;
            }
        }
        public string CaseRelated
        {
            get
            {
                return _caseRelated;
            }
            set
            {
                _caseRelated = value;
            }
        }
        public string Veteran
        {
            get
            {
                return _veteran;
            }
            set
            {
                _veteran = value;
            }
        }

        
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

        private int _ownerCaseNumber;
        public int Owner
        {
            get
            {
                return _ownerCaseNumber;
            }

            set
            {
            	_ownerCaseNumber = value;
            }
        }

        public string WeaponPresent
        {
            get
            {
                return _weaponPresent;
            }
            set
            {
                _weaponPresent = value;
            }
        }
        public string WeaponUsed
        {
            get
            {
                return _weaponUsed;
            }
            set
            {
                _weaponUsed = value;
            }
        }
        public int Zip
        {
            get
            {
                return _zip;
            }
            set
            {
                _zip = value;
            }
        }

	
    }
}
