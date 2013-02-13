using System;
using System.Collections.Generic;
using System.Text;
using System.Collections;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using System.Reflection;
using System.Web;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    [Serializable]
    public class Intake : Form
    {
        private int _IntakeID = new Random().Next();
        private string _state;
        private Hashtable _customfieldMap = new Hashtable();
       
        private void populateHash()
        {
            String[] nvpairs = Properties.Settings.Default.IntakeFieldMap.Split('&');

            foreach (String pair in nvpairs)
            {
                String[] kv = pair.Split('=');

                _customfieldMap[kv[0]] = kv[1];
            }
        }

        /// <summary>
        /// Loop through all the Properties and create a string of name/value pairs
        /// </summary>
        /// <returns></returns>
        //public override string ToString()
        //{

        //    populateHash();

        //    String t = "";
        //    foreach (PropertyInfo source in this.GetType().GetProperties())
        //    {
        //        foreach (String key in _customfieldMap.Keys)
        //        {
        //            if (key.Equals(source.Name))
        //            {
        //                //object o = source.GetValue(value, null);
        //                object v = source.GetValue(this, null);
        //                if (v != null)
        //                {
        //                    string value = (string)v;
        //                    if(value != "")
        //                        t += "&" + _customfieldMap[key] + "=" + HttpUtility.UrlEncode(value);
        //                }
        //            }
        //        }
        //    }

        //    return t;
        //}


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
                _communityEd = value;
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

        public string CallerRelationship
        {
            get
                {
                    return _CallerRelationship;
                }
            set 
                {
                    _CallerRelationship = value;
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

        public string InsuranceName
        {
            get
            {
                return _insuranceName;
            }
            set
            {
                _insuranceName = value;
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
        public string MCTDispatch
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


        private string _insuranceName; 
        private string _gunType2;
        private string _CallerRelationship;
        private string _emergencyPhone2;
        private string _emergencyRelationship2;
        private string _emergencyName3;
        private string _emergencyPhone3;
        private string _emergencyRelationship3;
        private string _mobilePhone;
        private string _workPhone;
        private string _otherPhone;
        private string _county;
        private string _countyLoc;
        private string _dOBUnknown;
        private string _clientSpeak;
        private string _clientSpeakWhy;
        private string _reference;
        private string _fullTimeStudent;
        private string _providerName;
        private string _provider2;
        private string _providerName2;
        private string _providerPhone2;
        private string _provider3;
        private string _providerName3;
        private string _providerPhone3;
        private string _childrenDesc;
        private string _adultDesc;
        private string _mentalHealthP;
        private string _finalcialP;
        private string _mhaNeeded;
        private string _releaseInfo;
        private string _releaseInfoLimitations;
        private string _emergencyName2;
        private string _veteran;
        private string _homeless;
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
        private string _commProviderA;
        private string _scheduleAppointment;
        private string _hearaboutus;

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

        

        public string Homeless
        {
            get
            {
                return _homeless;
            }
            set
            {
                _homeless = value;
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
        
        public string EmergencyName2
        {
            get
            {
                return _emergencyName2;
            }
            set
            {
                _emergencyName2 = value;
            }
        }

        public string EmergencyPhone2
        {
            get
            {
                return _emergencyPhone2;
            }
            set
            {
                _emergencyPhone2 = value;
            }
        }

        public string EmergencyRelationship2
        {
            get
            {
                return _emergencyRelationship2;
            }
            set
            {
                _emergencyRelationship2 = value;
            }
        }

        public string EmergencyName3
        {
            get
            {
                return _emergencyName3;
            }
            set
            {
                _emergencyName3 = value;
            }
        }

        public string EmergencyPhone3
        {
            get
            {
                return _emergencyPhone3;
            }
            set
            {
                _emergencyPhone3 = value;
            }
        }

        public string EmergencyRelationship3
        {
            get
            {
                return _emergencyRelationship3;
            }
            set
            {
                _emergencyRelationship3 = value;
            }
        }

        public string MobilePhone
        {
            get
            {
                return _mobilePhone;
            }
            set
            {
                _mobilePhone = value;
            }
        }

        public string WorkPhone
        {
            get
            {
                return _workPhone;
            }
            set
            {
                _workPhone = value;
            }
        }

        public string OtherPhone
        {
            get
            {
                return _otherPhone;
            }
            set
            {
                _otherPhone = value;
            }
        }

        public string County
        {
            get 
            {
                return _county; 
            }
            set
            { 
                _county = value; 
            }
        }

        public string CountyLoc
        {
            get
            {
                return _countyLoc;
            }
            set
            {
                _countyLoc = value;
            }
        }

        public string DOBUnknown
        {
            get
            {
                return _dOBUnknown;
            }
            set
            {
                _dOBUnknown = value;
            }
        }

        public string ClientSpeak
        {
            get
            {
                return _clientSpeak;
            }
            set
            {
                _clientSpeak = value;
            }
        }

        public string ClientSpeakWhy
        {
            get
            {
                return _clientSpeakWhy;
            }
            set
            {
                _clientSpeakWhy = value;
            }
        }

        public string Reference
        {
            get
            {
                return _reference;
            }
            set
            {
                _reference = value;
            }
        }

        public string FullTimeStudent
        {
            get
            {
                return _fullTimeStudent;
            }
            set
            {
                _fullTimeStudent = value;
            }
        }

        public string ProviderName
        {
            get
            {
                return _providerName;
            }
            set
            {
                _providerName = value;
            }
        }

        public string Provider2
        {
            get
            {
                return _provider2;
            }
            set
            {
                _provider2 = value;
            }
        }

        public string ProviderName2
        {
            get
            {
                return _providerName2;
            }
            set
            {
                _providerName2 = value;
            }
        }

        public string ProviderPhone2
        {
            get
            {
                return _providerPhone2;
            }
            set
            {
                _providerPhone2 = value;
            }
        }

        public string Provider3
        {
            get
            {
                return _provider3;
            }
            set
            {
                _provider3 = value;
            }
        }

        public string ProviderName3
        {
            get
            {
                return _providerName3;
            }
            set
            {
                _providerName3 = value;
            }
        }

        public string ProviderPhone3
        {
            get
            {
                return _providerPhone3;
            }
            set
            {
                _providerPhone3 = value;
            }
        }

        public string ChildrenDesc
        {
            get
            {
                return _childrenDesc;
            }
            set
            {
                _childrenDesc = value;
            }
        }

        public string AdultDesc
        {
            get
            {
                return _adultDesc;
            }
            set
            {
                _adultDesc = value;
            }
        }

        public string MentalHealthP
        {
            get
            {
                return _mentalHealthP;
            }
            set
            {
                _mentalHealthP = value;
            }
        }

        public string FinalcialP
        {
            get
            {
                return _finalcialP;
            }
            set
            {
                _finalcialP = value;
            }
        }

        public string MhaNeeded
        {
            get
            {
                return _mhaNeeded;
            }
            set
            {
                _mhaNeeded = value;
            }
        }

        public string ReleaseInfo
        {
            get
            {
                return _releaseInfo;
            }
            set
            {
                _releaseInfo = value;
            }
        }

        public string ReleaseInfoLimitations
        {
            get
            {
                return _releaseInfoLimitations;
            }
            set
            {
                _releaseInfoLimitations = value;
            }
        }

        public string CommProviderA
        {
            get
            {
                return _commProviderA;
            }
            set
            {
                _commProviderA = value;
            }
        }

        public string ScheduleAppointment
        {
            get
            {
                return _scheduleAppointment;
            }
            set
            {
                _scheduleAppointment = value;
            }
        }

        public string GunType2
        {
            get
            {
                return _gunType2;
            }
            set
            {
                _gunType2 = value;
            }
        }

        public string Hearaboutus
        {
            get
            {
                return _hearaboutus;
            }
            set
            {
                _hearaboutus = value;
            }
        }
            
    }
}
