using System;
using System.Collections.Generic;
using System.Text;
using System.IO;
using System.Net;
using System.Reflection;
using Microsoft.Practices.CompositeUI;
using Microsoft.Practices.CompositeUI.EventBroker;
using HttpServer;
using HttpListener=HttpServer.HttpListener;
using HttpServer.Sessions;
using HttpServer.Rendering;
using HttpServer.Helpers;
using HttpServer.HttpModules;
using Fadd.Globalization;
using Sante.EMR.SmartClient.Infrastructure.Webserver.Controllers;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Sante.EMR.SmartClient.Infrastructure.Interface.Constants;
using Sante.EMR.SmartClient.Infrastructure.Data.Services;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Sante.EMR.SmartClient.MCT.Services;
using Sante.EMR.SmartClient.Infrastructure.Logger.Services;
 

namespace Sante.EMR.SmartClient.Infrastructure.Webserver.Services
{
    [Service(typeof(IPageServerService))]
    public class PageServerService : IPageServerService
    {
        private readonly HttpServer.HttpServer _server = new HttpServer.HttpServer();
        private readonly LanguageNode _language = new MemLanguageNode(1033, "Root");
        private WorkItem _rootWorkItem;
        private const int PORT = 8080;
        //private static ILanguageNode _language;
        private static ILanguageNode _validationLanguage;
        private HttpServer.HttpListener _listener;
        private IDataAccessService _dataAccess;
        private IWorkflowStateService _workFlow;
        private ILoggerService _logger;

        /// <summary>
        /// 
        /// </summary>
        /// <param name="rootWorkItem"></param>
        /// <param name="dataAccess"></param>
        /// <param name="workflow"></param>
        /// <param name="logger"></param>
        public PageServerService([ServiceDependency] WorkItem rootWorkItem,
                                 [ServiceDependency] IDataAccessService dataAccess,
                                 [ServiceDependency] IWorkflowStateService workflow,
                                 [ServiceDependency] ILoggerService logger) 
        {

            _logger = logger;
            _workFlow = workflow;
            _rootWorkItem = rootWorkItem;
            _dataAccess = dataAccess;

            Start();

            EventArgs<string> ev = new EventArgs<string>("Webserver started successfully on port "+ PORT);
            _rootWorkItem.EventTopics[Sante.EMR.SmartClient.Infrastructure.Logger.Constants.EventTopicNames.WriteToLog].Fire(this, ev, _rootWorkItem, PublicationScope.WorkItem);

        }

        /// <summary>
        /// 
        /// </summary>
        private void Start()
        {
            string PATH = AppDomain.CurrentDomain.BaseDirectory + @"\html\";
            _server.Add(new HttpModule<AuthorizationRelease>(_rootWorkItem, _dataAccess,_workFlow, PATH + "authorizationtorelease.html", EventTopicNames.ShowNewAuthorizationToRelease));
            _server.Add(new HttpModule<ConsentForServices>(_rootWorkItem, _dataAccess,_workFlow, PATH + "consentforservices.html", EventTopicNames.ShowNewConsentForServices));
            _server.Add(new HttpModule<medeval>(_rootWorkItem, _dataAccess,_workFlow, PATH + "medeval.html", EventTopicNames.ShowMedicalEval));
            _server.Add(new HttpModule<InformedConsent>(_rootWorkItem, _dataAccess,_workFlow, PATH + "informedconsent.html", EventTopicNames.ShowInformedConsentForm));
            _server.Add(new HttpModule<PrivacyPractices>(_rootWorkItem, _dataAccess,_workFlow, PATH + "privacypractices.html", EventTopicNames.ShowPrivacyPracticesForm));
            _server.Add(new HttpModule<HumanRights>(_rootWorkItem, _dataAccess,_workFlow, PATH + "humanrights.html", EventTopicNames.ShowHumanRightsForm));
            _server.Add(new HttpModule<ProgressNote>(_rootWorkItem, _dataAccess, _workFlow, PATH + "notes.html", EventTopicNames.ShowNotesForm));
            _server.Add(new HttpModule<IHITIndividualTreatment>(_rootWorkItem, _dataAccess, _workFlow, PATH + "individualtreatmentplan.html", EventTopicNames.ShowIndividualTreatmentForm));
            _server.Add(new HttpModule<Telephone>(_rootWorkItem, _dataAccess, _workFlow, PATH + "telephonecontact.html", EventTopicNames.ShowTelephoneContact));
            _server.Add(new HttpModule<InformedMedicationConsent>(_rootWorkItem, _dataAccess, _workFlow, PATH + "informedmedicationconsent.html", EventTopicNames.ShowInformedMedicalConsent));
            try
            {
                _server.Start(IPAddress.Any, PORT);
            }
            catch (Exception e)
            {
                _logger.Write(e.Message);
                _logger.Write(e.StackTrace);
                throw e;
            }
            
        }

       
        [EventSubscription(EventTopicNames.Kill, ThreadOption.UserInterface)]
        public void Kill(object sender, EventArgs<string> args)
        {
            _server.Stop();
            _logger.Write(LogEvent.INFO,"Webserver was successfully killed...");
        }

    }


    /// <summary>
    /// 
    /// </summary>
    /// <typeparam name="T"></typeparam>
    public class HttpModule<T> : HttpModule where T : Form,new()
    { 
        private string _FILEPATH; // = @"C:\Documents and Settings\Charles\My Documents\sante\code\EMR SmartClient\MCT\MCT\html\consentforservices.html";
        WorkItem _rootWorkItem;
        IDataAccessService _dataAccess;
        IWorkflowStateService _workFlow;
        private string _requestUri;
        private const string EVENT = EventTopicNames.SaveFormEvent;

        public HttpModule([ServiceDependency] WorkItem rootWorkItem,
                          [ServiceDependency] IDataAccessService dataAccess,
                          [ServiceDependency] IWorkflowStateService workflow,
                            string path, string requestURI)
        {
            _rootWorkItem = rootWorkItem;
            _dataAccess = dataAccess;
            _workFlow = workflow;
            _FILEPATH = path;
            _requestUri = requestURI;
        }

        public override bool Process(IHttpRequest request, IHttpResponse response, IHttpSession session)
        {
            if (request.Uri.ToString().Contains(_requestUri))
            {
                if (request.Form.GetEnumerator().MoveNext())
                {
                    Form form = new T();
                    form.SetFormFields(request.Form);

                    EventArgs<Form> e = new EventArgs<Form>(form);
                    _rootWorkItem.EventTopics[EVENT].Fire(this, e, _rootWorkItem, PublicationScope.Global);

                    
                    StreamWriter writer = new StreamWriter(response.Body);
                    string responseStr = "<h1>Form was successfully saved. Please close the form window to proceed.</h1>";
                    writer.Write(responseStr);
                    writer.Flush();
                }
                else
                {
                    FileStream fileStream = new FileStream(_FILEPATH, FileMode.Open);
                    try
                    {
                        StreamReader streamReader = new StreamReader(fileStream);
                        string text = streamReader.ReadToEnd();

                        text = massageHTML(text);

                        
                        // read from file or write to file

                        StreamWriter writer = new StreamWriter(response.Body);
                        writer.Write(text);
                        streamReader.Close();
                        writer.Flush();

                        return true;
                    }
                    finally
                    {
                        fileStream.Close();
                    }

                }

                return true;
            }

            return false;
        }

        private string massageHTML(string text)
        {
            text = text.Replace("<#CaseNumber#>", Convert.ToString(_workFlow.CaseNumber));
            int month = DateTime.Now.Month;
            int day = DateTime.Now.Day;

            string dayStr = DateTime.Now.Day.ToString();
            string monStr = DateTime.Now.Month.ToString();

            if (month < 10)
                monStr = "0" + monStr;

            if (day < 10)
                dayStr = "0" + dayStr;

            string dateString = DateTime.Now.Year.ToString() + monStr + dayStr;
            text = text.Replace("<#Date#>", dateString);

            string timeStampString = DateTime.Now.ToString();
            text = text.Replace("<#TimeStamp#>", timeStampString);

            
            text = text.Replace("<#Depart#>",_workFlow.Depart);

            string shortTimeStampString = DateTime.Now.ToShortTimeString();
            text = text.Replace("<#ShortTimeStamp#>", shortTimeStampString);

            string shortDateStampString = DateTime.Now.ToShortDateString();
            text = text.Replace("<#ShortDateStamp#>", shortDateStampString);

            text = text.Replace("<#LocalUri#>", _requestUri);
            return text;           
        }
    }

    public class ConsentForServicesModule : HttpModule
    {
            WorkItem _rootWorkItem;

        public ConsentForServicesModule([ServiceDependency] WorkItem rootWorkItem,
                                  [ServiceDependency] IDataAccessService dataAccess) 
        {

            _rootWorkItem = rootWorkItem;
         }
        public override bool Process(IHttpRequest request, IHttpResponse response, IHttpSession session)
        {
            if (request.Uri.ToString().Contains("consentforservices"))
            {
                    if (request.Form.GetEnumerator().MoveNext())
                    {
                        Form form = new ConsentForServices();
                        form.SetFormFields(request.Form);

                        EventArgs<Form> e = new EventArgs<Form>(form);
                        _rootWorkItem.EventTopics["SaveFormEvent"].Fire(this, e, _rootWorkItem, PublicationScope.Global);
                    }
                    else
                    {
                        FileStream fileStream = new FileStream(@"C:\Documents and Settings\Charles\My Documents\sante\code\EMR SmartClient\MCT\MCT\html\consentforservices.html", FileMode.Open);
                        try
                        {
                            StreamReader streamReader = new StreamReader(fileStream);
                            string text = streamReader.ReadToEnd();
                            streamReader.Close();

                            // read from file or write to file

                            StreamWriter writer = new StreamWriter(response.Body);
                            writer.Write(text);
                            writer.Flush();

                            return true;
                        }
                        finally
                        {
                            fileStream.Close();
                        }

                    }
                
            }

            return true;
        }
    }


    public class MedEvalModule : HttpModule
    {
        WorkItem _rootWorkItem;

         public MedEvalModule([ServiceDependency] WorkItem rootWorkItem,
                                  [ServiceDependency] IDataAccessService dataAccess) {

           
            
            _rootWorkItem = rootWorkItem;
         }
        public override bool Process(IHttpRequest request, IHttpResponse response, IHttpSession session)
        {
            if (request.Uri.ToString().Contains("medeval"))
            {
                if (request.Form .GetEnumerator().MoveNext())
                {
                    Form form = new medeval();
                    form.SetFormFields(request.Form);

                    EventArgs<Form> e = new EventArgs<Form>(form);
                    _rootWorkItem.EventTopics["SaveFormEvent"].Fire(this, e, _rootWorkItem, PublicationScope.Global);
                }
                else
                {
                    FileStream fileStream = new FileStream(@"C:\Documents and Settings\Charles\My Documents\sante\code\EMR SmartClient\UCC\UCC\html\medeval.html", FileMode.Open);
                    try
                    {
                        StreamReader streamReader = new StreamReader(fileStream);
                        string text = streamReader.ReadToEnd();
                        streamReader.Close();

                        // read from file or write to file

                        StreamWriter writer = new StreamWriter(response.Body);
                        writer.Write(text);
                        writer.Flush();

                        return true;
                    }
                    finally
                    {
                        fileStream.Close();
                    }

                }
            }

            return false;
        }
    }


    public class NextTaskModule : HttpModule
    {
        private IDataAccessService _dataAccess;
        public NextTaskModule(IDataAccessService dataAccess)
        {
            _dataAccess = dataAccess;
        }
        public override bool Process(IHttpRequest request, IHttpResponse response, IHttpSession session)
        {
            //if (request.Uri.ToString().Contains("nexttask"))
            //{
            //    string queryString = request.QueryString["CaseNumber"].Value;
            //    int casenumber = Convert.ToInt32(queryString);
            //    List<NextTask> nextTask = _dataAccess.GetNextTask(casenumber);

            //    StreamWriter writer = new StreamWriter(response.Body);

            //    writer.WriteLine("<table border='1'>");
            //    writer.WriteLine(string.Format("<tr><td align='center'>{0}</td>", "Task Name"));
            //    writer.WriteLine(string.Format("<td align='center'>{0}</td>", "Set Date"));
            //    writer.WriteLine(string.Format("<td align='center'>{0}</td>", "Comments"));
            //    writer.WriteLine(string.Format("<td align='center'>{0}</td>", "Date Due"));
            //    writer.WriteLine(string.Format("<td align='center'>{0}</td>", "Initials"));
            //    writer.WriteLine(string.Format("<td align='center'>{0}</td>", "Date Completed"));
            //    writer.WriteLine(string.Format("<td align='center'>{0}</td>", "Appointment Status"));
            //    writer.WriteLine("</tr>"); //n.Name+"<br/>");

            //    foreach (NextTask n in nextTask)
            //    {
            //        writer.WriteLine(string.Format("<tr><td>{0}</td>",n.NextTaskName));
            //        writer.WriteLine(string.Format("<td>{0}</td>",n.SetDate));
            //        writer.WriteLine(string.Format("<td>{0}</td>",n.Comments));
            //        writer.WriteLine(string.Format("<td>{0}</td>",n.DateDue));
            //        writer.WriteLine(string.Format("<td>{0}</td>",n.Initial));
            //        writer.WriteLine(string.Format("<td>{0}</td>",n.DateCompleted));
            //        //writer.WriteLine(string.Format("<td>{0}</td>",n.Appt));
            //        writer.WriteLine("</tr>"); //n.Name+"<br/>");
            //    }

            //    writer.WriteLine("</table>");
            //        writer.Flush();


                return true;
            //}
            //else
            //{
            //    return false;
            //}

            
        }

    }

    public class CISMModule : HttpModule
    {
        public override bool Process(IHttpRequest request, IHttpResponse response, IHttpSession session)
        {
            return false;
        }
    }

    public class OPSModule : HttpModule
    {
        public override bool Process(IHttpRequest request, IHttpResponse response, IHttpSession session)
        {
            return false;
        }
    }

    public class VoucherModule : HttpModule
    {
        public override bool Process(IHttpRequest request, IHttpResponse response, IHttpSession session)
        {
            return false;
        }
    }

    public class CaseHistoryLogModule : HttpModule
    {
        public override bool Process(IHttpRequest request, IHttpResponse response, IHttpSession session)
        {
            return false;
        }
    }
}
