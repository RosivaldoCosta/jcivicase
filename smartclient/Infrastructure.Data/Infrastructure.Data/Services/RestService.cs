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

namespace Sante.EMR.SmartClient.Infrastructure.Data.Services
{
    public enum RestMethod
    {
        GET,
        POST,
        PUT,
        DELETE,
        HEAD,
        OPTIONS
    }

    [Service(typeof(IDataAccessService))]
    public class RestService : IDataAccessService
    {
        private IConnectionMonitorService _monitor;
        private ILoggerService _logger;
        private int _caseid;
        
        public RestService([ServiceDependency] WorkItem rootWorkItem,
                                 [ServiceDependency] IConnectionMonitorService monitor,
                                 [ServiceDependency] ILoggerService logger)
        {
            //_rootWorkItem = rootWorkItem;
            //_dataService = new Sante.EMR.SmartClient.Infrastructure.Data.DataWebService.DataService();

            _monitor = monitor;
            _logger = logger;
        }

        public int CaseId
        {
            get { return _caseid; }
            set { _caseid = value; }
        }
        #region IDataAccessService Members

        //public CaseQueue RetrieveCaseQueue(DataAccessType type)
        //{
        //    throw new NotImplementedException();
        //}

        public bool SynchronizeData()
        {
            throw new NotImplementedException();
        }

        public List<Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Patient> Search(string first, string last, string phone, string address)
        {
            throw new NotImplementedException();
        }

        public bool SendForm(Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Form f)
        {
            String response = null;
     
            if (_monitor.IsConnected())
            {
                
                try
                {

                    this.login();

                        
                    //if (f is BusinessEntitiesAlias.InformedMedicationConsent)
                    //{ _dataService.SaveForm((DataWebService.InformedMedicationConsent)_translator.Translate(typeof(DataWebService.InformedMedicationConsent), f)); }

                    //if (f is BusinessEntitiesAlias.Lethality)
                    //{
                    //    _dataService.SaveForm((DataWebService.Lethality)_translator.Translate(typeof(DataWebService.Lethality), f));
                    //}
                    //else 
                    if (f is BusinessEntitiesAlias.Intake)
                    {
                        String contactRequest = "/contact/add";

                        BusinessEntitiesAlias.Intake i = (BusinessEntitiesAlias.Intake)f;
                        contactRequest += "&first_name=" + i.PFirstName;
                        contactRequest += "&last_name=" + i.PlastName;
                        contactRequest += "&contact_type=Individual&contact_sub_type=Client";
                        string contactid = this.getContactIdFromResponse(this.ConvertResponseToXML(sendRequest(contactRequest)));

                        _logger.Write("Retrieved Contact Id " + contactid);

                        if (contactid != null && contactid != "")
                        {
                            String caseRequest = Properties.Settings.Default.CaseRequest;//"/contact/view/case&action=add&reset=1&activity_subject=OpenOPSCaseFromSmartClient&case_type_id=1&status_id=4&creator_id=746";

                            String start_date = i.Date.Year.ToString();
                            int m = i.Date.Month;
                            if (m < 10)
                                start_date += "0" + m.ToString();
                            else
                                start_date += m.ToString();

                            if (i.Date.Day < 10)
                                start_date += "0" + i.Date.Day.ToString();
                            else
                                start_date += i.Date.Day.ToString();

                            caseRequest += "&start_date=" + start_date;
                            caseRequest += "&contact_id=" + contactid;// +i.ToString();
                            caseRequest += "&details=" + i.ReasonForContact;
                            caseRequest += i.ToString();

                            string caseid = getCaseId(ConvertResponseToXML(sendRequest(caseRequest)));

                            if (caseid != null && caseid != "")
                            {
                                _caseid = Convert.ToInt32(caseid);
                                //string intakeRequest = Properties.Settings.Default.ActivityRequest;
                                //intakeRequest += i.ToString();
                                //intakeRequest += "&case_id=" + caseid;
                                //intakeRequest += "&activity_type_id=" + Properties.Settings.Default.IntakeActivityId;
                                //sendRequest(intakeRequest);

                            }
                            else
                                return false;

                            if (response != null)
                                _logger.Write(response);
                        }
                        else
                            return false;//caseRequest);
                        //_dataService.SaveForm((DataWebService.Intake)_translator.Translate(typeof(DataWebService.Intake), (BusinessEntitiesAlias.Intake)f));
                    }
                    else
                    {
                        string activityRequest = Properties.Settings.Default.ActivityRequest;
                        activityRequest += "&case_id=" + f.CaseNumber;
                        activityRequest += f.ToString();
                        activityRequest += "&subject="+f.Subject;
                        _logger.Write(f.FormName);
                            
                    
                        if (f is BusinessEntitiesAlias.MCTDispatch)
                        {
                            //_dataService.SaveForm((DataWebService.MCTDispatch)_translator.Translate(typeof(DataWebService.MCTDispatch), f));
                            //BusinessEntitiesAlias.MCTDispatch a = (BusinessEntitiesAlias.MCTDispatch)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.MCTDispatchId;
                            activityRequest += "&activity_subject=MCT+Dispatch&subject=MCT+Dispatch&version=3";

                        }
                        else if (f is BusinessEntitiesAlias.MCTAssessmentAndTreatment)
                        {
                            //BusinessEntitiesAlias.MCTAssessmentAndTreatment a = (BusinessEntitiesAlias.MCTAssessmentAndTreatment)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.AssessmentId;
                            activityRequest += "&activity_subject=Assessment+And+Treatment&subject=Assessment+And+Treatment&version=3";
                        }
                        else if (f is BusinessEntitiesAlias.AuthorizationRelease)
                        {
                            //BusinessEntitiesAlias.AuthorizationRelease a = (BusinessEntitiesAlias.AuthorizationRelease)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.AuthorizationReleasId;
                            
                        }
                        else if (f is BusinessEntitiesAlias.ConsentForServices)
                        {
                            //BusinessEntitiesAlias.ConsentForServices a = (BusinessEntitiesAlias.ConsentForServices)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.ConsentForServicesId;
                            activityRequest += "&activity_subject=Consent+for+Services&subject=Consent+for+Services&version=3";
                        }
                        else if (f is BusinessEntitiesAlias.HumanRights)
                        {
                            //BusinessEntitiesAlias.HumanRights a = (BusinessEntitiesAlias.HumanRights)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.HumanRightsId;
                            
                        }
                        else if (f is BusinessEntitiesAlias.IHITIndividualTreatment)
                        {
                            //BusinessEntitiesAlias.IHITIndividualTreatment a = (BusinessEntitiesAlias.IHITIndividualTreatment)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.TreatmentPlanId;
                            

                        }
                        else if (f is BusinessEntitiesAlias.InformedConsent)
                        {
                            //BusinessEntitiesAlias.InformedConsent a = (BusinessEntitiesAlias.InformedConsent)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.InformedConsentId;
                        }
                        else if (f is BusinessEntitiesAlias.medeval)
                        {
                            //BusinessEntitiesAlias.medeval a = (BusinessEntitiesAlias.medeval)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.MentalHealthEvalId;
                            activityRequest += "&activity_subject=Medical+Evaluation&subject=Medical+Evaluation&version=3";
                        }
                        else if (f is BusinessEntitiesAlias.ProgressNote)
                        {
                            //BusinessEntitiesAlias.ProgressNote a = (BusinessEntitiesAlias.ProgressNote)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.ProgessNoteId;
                            activityRequest += "&activity_subject=Progress+Notes&subject=Progress+Notes&version=3";
                        }
                        else if (f is BusinessEntitiesAlias.Telephone)
                        {
                            //BusinessEntitiesAlias.Telephone a = (BusinessEntitiesAlias.Telephone)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.TelephoneId;
                            activityRequest += "&activity_subject=Telephone+Contact&subject=Telephone+Contact&version=3";                            
                        }
                        else if (f is BusinessEntitiesAlias.PrivacyPractices)
                        {
                            //BusinessEntitiesAlias.PrivacyPractices a = (BusinessEntitiesAlias.PrivacyPractices)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.PrivacyPracticesId;
                            
                        }
                        else if (f is BusinessEntitiesAlias.Lethality)
                        {
                            //BusinessEntitiesAlias.MCTAssessmentAndTreatment a = (BusinessEntitiesAlias.MCTAssessmentAndTreatment)f;
                            //activityRequest += a.ToString();
                            activityRequest += "&activity_type_id=" + Properties.Settings.Default.LethalityId;
                            activityRequest += "&activity_subject=Lethality&subject=Lethality&version=3";
                        }

                        sendRequest(activityRequest);
                    }
                    
                }
                catch (Exception e)
                {
                    _logger.Write(e.Message);
                    _logger.Write(e.StackTrace);

                    if (response != null)
                        _logger.Write(response);
                    return false;
                }

                return true; // return (caseSuccess && nextTaskSuccess);// && patientSuccess);
            }
            else
            {
                _logger.Write("DataAccessService attempted to save a case to the database while disconnected.");
                return false;

            }

           


            return true;
        }

        public bool SendCase(Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Case c)
        {
            bool patientSuccess, caseSuccess, nextTaskSuccess;
            String response;
            
            if (_monitor.IsConnected())
            {
                try
                {
                    this.login();

                    String caseRequest = "civicrm/contact/view/case&action=add&reset=1";
                    String contactRequest = "/contact/add";

                    //response = sendRequest(contactRequest,(String)_translator.Translate(typeof(String), (BusinessEntitiesAlias.Patient)c.Patient)) ;

                    //caseSuccess = _dataService.SaveCase((DataWebService.Case)_translator.Translate(typeof(DataWebService.Case), (BusinessEntitiesAlias.Case)c));
                    //caseSuccess = sendRequest(contactRequest,_translator.Translate(typeof(DataWebService.Case), (BusinessEntitiesAlias.Case)c));
                    
                    //nextTaskSuccess = _dataService.SaveNextTask((DataWebService.Task)_translator.Translate(typeof(DataWebService.Task), (BusinessEntitiesAlias.Task)c.NextTaskObj));
                    
                }
                catch (Exception e)
                {
                    _logger.Write(e.Message);
                    _logger.Write(e.StackTrace);
                    return false;
                }

                return true; // return (caseSuccess && nextTaskSuccess);// && patientSuccess);
            }
            else
            {
                _logger.Write("DataAccessService attempted to save a case to the database while disconnected.");

            }

            return false;
        }

        private void SetBody(HttpWebRequest request, string requestBody)
        {
            if (requestBody.Length > 0)
            {
                using (Stream requestStream = request.GetRequestStream())
                using (StreamWriter writer = new StreamWriter(requestStream))
                {
                    writer.Write(requestBody);
                }
            }
        }

        private bool login()
        {
            String url = Properties.Settings.Default.RestHost;
            url += Properties.Settings.Default.SiteName;
            url += Properties.Settings.Default.RestUrl + "/login";
            url += Properties.Settings.Default.RestUser;
            url += Properties.Settings.Default.RestPassword;
            url += Properties.Settings.Default.SiteKey;
            url += Properties.Settings.Default.ApiKey;

            String method = "GET";
            string responseAsString = "";

            try
            {
                var request = (HttpWebRequest)WebRequest.Create(url);
                request.Method = method;
               
                var response = (HttpWebResponse)request.GetResponse();

                responseAsString = ConvertResponseToString(response);
            }
            catch (Exception ex)
            {
                responseAsString += "ERROR: " + ex.Message;
                _logger.Write(ex.Message);
                _logger.Write(ex.StackTrace);
            }

            return false;
        }

        private String sendRequest(String r)
        {
            var url = Properties.Settings.Default.RestHost+Properties.Settings.Default.SiteName+Properties.Settings.Default.RestUrl;
            url += r;
            url += Properties.Settings.Default.RestUser;
            url += Properties.Settings.Default.RestPassword;
            url += Properties.Settings.Default.SiteKey;
            url += Properties.Settings.Default.ApiKey;
           

            _logger.Write(url);

            var method = "GET";
            String responseAsString = "";

            try
            {
                var request = (HttpWebRequest)WebRequest.Create(url);
                request.Method = method;
                //SetBody(request, requestBody);

                var response = (HttpWebResponse)request.GetResponse();

                responseAsString = ConvertResponseToString(response);
            }
            catch (Exception ex)
            {
                responseAsString += "ERROR: " + ex.Message;
            }

            return responseAsString;
        }

        /// <summary>
        ///   Return the contact_id from the xml result
        ///   <?xml version="1.0"?>
        ///<ResultSet xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        ///<Result>
        ///<contact_id>787</contact_id>
        ///<is_error>0</is_error>
        ///</Result>
        ///</ResultSet>
        /// </summary>
        /// <param name="x"></param>
        /// <returns></returns>
        private String getContactIdFromResponse(XmlDocument x)
        {

            if (x["ResultSet"].LastChild.FirstChild != null)
                return x["ResultSet"].LastChild.FirstChild.FirstChild.Value;

            return null;
        }

        /// <summary>
        /// <?xml version="1.0"?>
        ///<ResultSet xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        ///<Result>
        ///</Result>
        ///<Result>
        ///    <id>240</id>
        ///    <case_type_id>1</case_type_id>
        ///    <subject></subject>
        ///    <start_date>2011-00-22</start_date>  
        ///    <end_date></end_date>
        ///    <details></details>
        ///    <status_id>4</status_id>
        ///    <is_deleted>0</is_deleted>
        ///</Result>
        ///</ResultSet>
        /// </summary>
        /// <param name="x"></param>
        /// <returns></returns>
        private String getCaseId(XmlDocument x)
        {
            //if (x["ResultSet"].LastChild != null)
                //return x["ResultSet"].LastChild.FirstChild.FirstChild.Value;

            System.Collections.IEnumerator i = x["ResultSet"].GetEnumerator();

            while (i.MoveNext())
            {
                XmlElement child = (XmlElement)i.Current;
                if (child.HasChildNodes)
                {
                    return child.FirstChild.FirstChild.Value;
                }
            }

            return null;
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="xml"></param>
        /// <returns></returns>
        private XmlDocument ConvertResponseToXML(String xml)
        {
            NameTable nt = new NameTable();
            XmlNamespaceManager nsmgr = new XmlNamespaceManager(nt);
            XmlDocument doc = new XmlDocument();
            doc.LoadXml(xml);
            //StringReader s = new StringReader(xml);
            // XmlReader r = new XmlTextReader(s);
            ////XmlReader r = new XmlTextReader(doc.
            return doc;


        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="response"></param>
        /// <returns></returns>
        private string ConvertResponseToString(HttpWebResponse response)
        {
            string result = "Status code: " + (int)response.StatusCode + " " + response.StatusCode + "\r\n";

            //foreach (string key in response.Headers.Keys)
            //{
            //    result += string.Format("{0}: {1} \r\n", key, response.Headers[key]);
            //}

            result += "\r\n";
            _logger.Write(result);
            
            result = new StreamReader(response.GetResponseStream()).ReadToEnd();
            _logger.Write(result);

            return result;
        }

        #endregion
    }
}
