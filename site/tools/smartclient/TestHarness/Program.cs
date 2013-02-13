using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Data.Services;
using System;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Infrastructure.Data;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.Infrastructure.Data.Constants;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Sante.EMR.SmartClient.Infrastructure.Module.ConnectionMonitor.Services;
using System.Data;
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;
using Sante.EMR.SmartClient.Infrastructure.Data.DataWebService;
using DataWebServiceAlias = Sante.EMR.SmartClient.Infrastructure.Data.DataWebService;
using System.Collections.Generic;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Sante.EMR.SmartClient.Infrastructure.Logger.Services;
using System.Net;
using System.IO;
using System.Xml;

namespace TestHarness
{
    class Program
    {
        static void Main(string[] args)
        {
            WorkItem wi = new WorkItem();
            
           
            ILoggerService _logger = new LoggerService(wi);
            IConnectionMonitorService _monitor = new ConnectionMonitorService(wi,_logger);

            RestService r = new RestService(wi, _monitor, _logger);
            Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Intake i = new Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Intake();
            i.Date = System.DateTime.Now;
            i.ReasonForContact = "Testing the Smart Client";
            i.Time = i.Date.TimeOfDay.ToString();
            i.Address = "121 Smart Client Way";
            i.City = "Chapel Hill";
            i.PFirstName = "Smart Client";
            i.PlastName = "Test User 0" + i.Date.Second.ToString();
            i.Comments = "Comments"; 
            i.CallerName = "Myself"; //caller_name_51  
            i.CallerAddress = "14801 Rydell Road";  
            i.CallerCity = "Centerville";
            i.CallerState = "VA"; 
            i.CallerZip = "20121"; 
            i.CallerPhone = "7037254524"; 
            i.Relationship = "Son"; 
            i.RefferalSource = "Client"; 
            //i.PhoneBogus = "N";
            i.Zip = 27516;
            i.Veteran = "1";
            i.Phone = "7037254524";
  /*CallerRefuse=custom_26 
  Frequent=custom_25 
  CallerDisconnects=custom_24 
  Address=custom_60 
  Resident=custom_61 
  Veteran=custom_62 
  CaseRelated=custom_63
  City=custom_64
  State=custom_65 
  Zip=custom_66 
  Phone=custom_67 
  Message=custom_68
  SSN=custom_69
  Dob=custom_70
  Sex=custom_72 
  Culture=custom_73 
  CrisisPlan=custom_79 
  Record911=custom_80
  FollowUp=custom_81 
  CrisisNeeded=custom_131
  NotifyAPS=custom_21 
  MCTDispatch=custom_20 
  CrisisPlanOnly=custom_18
  CommunityEd=custom_17 
  InformationOnly=custom_16 
  OperatorTime=custom_48 
  OperatorNum=custom_753
  EmergencyName=custom_38 
  EmergencyRelationship=custom_40
  EmergencyPhone=custom_39 
  CismFlag=cism_request_37 
  SituationalCrisis=custom_36
  SubstanceAbuseP=custom_35 
  RapidDeterioration=custom_33 
  DangerToSelf=custom_32   
  DangerToOthers=custom_1261 
  Release=custom_74
  Limitations=custom_75
  RiskHospitalization=custom_31  
  Incarceration=custom_30
  InpatientHospital=custom_29 
  Eviction=custom_28   
  Financial=custom_59  
  OtherMed=custom_120  
  Linkage=custom_82 
  MH=custom_771   
  NonMH=custom_790 
  ReferralOther=custom_824  
  ProviderPhone=custom_286  
  ProviderContact=custom_258  
  Provider=custom_260 
  Age=custom_71 
  Called911=custom_19
  Phone=custom_119
  InsurancePhone=custom_128 */
            
        bool success= r.SendForm(i);
        if (success)
        { Console.WriteLine("Success"); }
        else
        { Console.WriteLine("Failure"); }

            /*String mysqldate = i.Date.Year.ToString();
                            int m = i.Date.Month;
                            if (m < 10)
                                mysqldate += "0" + m.ToString();
                            else
                                mysqldate += m.ToString();

                            if (i.Date.Day < 10)
                                mysqldate += "0" + i.Date.Day.ToString();
                            else
                                mysqldate += i.Date.Day.ToString();
            BusinessEntitiesAlias.MCTDispatch dispatch = new BusinessEntitiesAlias.MCTDispatch();
            dispatch.CaseNumber = r.CaseId;
            dispatch.Subject = "MCT Dispatch";
            dispatch.Team = "Smart Client Team";
            dispatch.location = "Residence";
            dispatch.Precinct = "1";
            dispatch.DispatchedBy = "OPS";
            dispatch.Mileage = 20;
            dispatch.DivertedCall = "Y";
            r.SendForm(dispatch);

            BusinessEntitiesAlias.Telephone tele = new BusinessEntitiesAlias.Telephone();
            tele.CaseNumber = r.CaseId;
            tele.Subject = "Telephone Contact";
            tele.PersonContacted = "Smart client";
            tele.Org = "Sante";
            tele.Phone = "(555)555-5555";
            tele.NextTask = "NextTask";
            r.SendForm(tele);

            BusinessEntitiesAlias.ProgressNote note = new BusinessEntitiesAlias.ProgressNote();
            note.CaseNumber = r.CaseId;
            note.Subject = "Progress Note";
            note.StaffID = "Charles Campbell";
            note.Notes = "These are my notes";
            note.NoteAction = "Action";
            note.Comments = "Comments";
            note.InitialVisit = 1;
            r.SendForm(note);
            
            BusinessEntitiesAlias.PrivacyPractices pp = new BusinessEntitiesAlias.PrivacyPractices();
            pp.CaseNumber = r.CaseId;
            pp.Subject = "Privacy Practices";
            
            r.SendForm(pp);

            BusinessEntitiesAlias.medeval medeval = new BusinessEntitiesAlias.medeval();
            medeval.CaseNumber = r.CaseId;
            r.SendForm(medeval);
            
            BusinessEntitiesAlias.MCTAssessmentAndTreatment aat = new BusinessEntitiesAlias.MCTAssessmentAndTreatment();
            aat.CaseNumber = r.CaseId;
            aat.Axis1 = "Mentally Disturbed";
            aat.Axis2 = "Mentally Disturbed";
            aat.Axis3 = "Mentally Disturbed";
            aat.Axis4 = "Mentally Disturbed";
            aat.Axis5 = "Mentally Disturbed";
            aat.CrisisPlan = "Crisis Plan";
            aat.DiagnosticClient = "Diagnostic";
            aat.DiagnosticImpression = "Impression";
            aat.Legal = "Legal";
            aat.Medical = "Medical";
            aat.MedicalEval = "Medical Exam/Eval";
            aat.Mental = "Mental";
            aat.Problem = "Problem";
            aat.PsychHistory = "PsychHistory";
            aat.PsychMeds = "PsychMeds";
            aat.PsychTreatment = "PsychTreatment";
            aat.RiskAssessment = "RiskAssessment";
            aat.Somatic = "Somatic";
            aat.Strengths = "Strengths";
            aat.SubstanceTreatment = "Substance Treatment";
            aat.Treatment = "Treatment";
            aat.Subject = "Smart Client Assessment";

            r.SendForm(aat);
            
            BusinessEntitiesAlias.Lethality lethality = new BusinessEntitiesAlias.Lethality();
            lethality.CaseNumber = r.CaseId;
            lethality.Method = 1;
            lethality.Accessibility = 1;
            lethality.CallerAlone = 1;
            lethality.FamilyAttemptsCompletions = 1;
            lethality.Supports = 1;
            lethality.Subject = "Smart Client Lethality";
            r.SendForm(lethality);


            BusinessEntitiesAlias.InformedMedicationConsent informedmed = new BusinessEntitiesAlias.InformedMedicationConsent();
            informedmed.CaseNumber = r.CaseId;
            informedmed.Subject = "Smart Client Informed Medication Consent";
            r.SendForm(informedmed);

            BusinessEntitiesAlias.InformedConsent ic = new BusinessEntitiesAlias.InformedConsent();
            ic.CaseNumber = r.CaseId;
            ic.Subject = "Informed Consent";
            r.SendForm(ic);

            BusinessEntitiesAlias.IHITIndividualTreatment it = new BusinessEntitiesAlias.IHITIndividualTreatment();
            it.CaseNumber = r.CaseId;
            it.LTTarget = DateTime.Now;
            it.LTRevised = DateTime.Now;
            it.LTComplete = DateTime.Now;
            it.ST1Complete = "This is complete";
            it.ST1Progress = "This is progress";
            it.ST1Target = DateTime.Now;
            it.ST1Complete = "ST1 Complete";
            it.ST2Objective = "objectives_610";
            it.ST2Progress = "progress_611";
            it.ST2Start = DateTime.Now;
            it.ST2Target = DateTime.Now;
            it.ST2Complete = DateTime.Now; //completed_788
            it.ST2Revised = DateTime.Now; //revised_789
            it.Subject = "Smart Client Individual Treatment";

            r.SendForm(it);

            BusinessEntitiesAlias.HumanRights hr = new BusinessEntitiesAlias.HumanRights();
            hr.CaseNumber = r.CaseId;
            hr.Subject = "Smart Client Human Rights";
            r.SendForm(hr);

            BusinessEntitiesAlias.ConsentForServices cs = new BusinessEntitiesAlias.ConsentForServices();
            cs.CaseNumber = r.CaseId;
            cs.Subject = "Smart Client Consent For Services";
            r.SendForm(cs);

            BusinessEntitiesAlias.AuthorizationRelease ar = new BusinessEntitiesAlias.AuthorizationRelease();
            ar.CaseNumber = r.CaseId;
            ar.Subject = "Smart Client Authorization to Release";
            r.SendForm(ar);
            */
                            

            
           
        }
    }
}
