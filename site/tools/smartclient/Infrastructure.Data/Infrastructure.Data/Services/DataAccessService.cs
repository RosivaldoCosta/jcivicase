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

namespace Sante.EMR.SmartClient.Infrastructure.Data.Services
{
    public enum DataAccessType
    {
        All,
        OPS,
        MCT,
        CISM,
        UCC,
        IHIT
    }

   
    [Service(typeof(IDataAccessService))]
    public class DataAccessService : IDataAccessService
    {
        public const string OPS_KEY = "OPS";
        public const string MCT_KEY = "MCT";
        public const string IHIT_KEY = "IHIT";
        public const string CISM_KEY = "CISM";
        public const string UCC_KEY = "UCC";

        private SmartClientCache _cache;
        private WorkItem _rootWorkItem;
        private IDataAccessService _dataAccess;
        EventArgs<string> _ev;
        private DataWebService.DataService _dataService;
        private IConnectionMonitorService _monitor;
        private IEntityTranslatorService _translator;
        private ILoggerService _logger;

        public DataAccessService([ServiceDependency] WorkItem rootWorkItem,
                                 [ServiceDependency] IConnectionMonitorService monitor,
                                 [ServiceDependency] IEntityTranslatorService translator,
                                 [ServiceDependency] ILoggerService logger)
        {
            _rootWorkItem = rootWorkItem;
            _dataService = new Sante.EMR.SmartClient.Infrastructure.Data.DataWebService.DataService();
            
            _monitor = monitor;
            _translator = translator;
            _logger = logger;
         
        }

        private void FillCache()
        {
            try
            {
                //if (_cache.Fill())
                //{

                //    _ev = new EventArgs<string>("Finished successfully filling cache");
                //    _rootWorkItem.EventTopics[Sante.EMR.SmartClient.Infrastructure.Logger.Constants.EventTopicNames.WriteToLog].Fire(this, _ev, _rootWorkItem, PublicationScope.WorkItem);
                //}
            }
            catch (InvalidCastException ex)
            {
                _ev = new EventArgs<string>(ex.Message + '\n'+ex.StackTrace);
                _rootWorkItem.EventTopics[Sante.EMR.SmartClient.Infrastructure.Logger.Constants.EventTopicNames.WriteToLog].Fire(this, _ev, _rootWorkItem, PublicationScope.WorkItem);
               
            }
        }
       
        [EventSubscription(EventTopicNames.SyncCache, ThreadOption.Background)]
        public bool SynchronizeData(object sender, EventArgs<string> e)
        {
           
            bool success = _cache.SyncCache();
            
            if (success)
            {
                _ev = new EventArgs<string>("Cache finished synchronizing with the server successfully.");
                _rootWorkItem.EventTopics[EventTopicNames.SyncCacheComplete].Fire(this, _ev, _rootWorkItem, PublicationScope.WorkItem);
            }
            else
            {
                _ev = new EventArgs<string>("Error while synchronizing data with the server.");
                _rootWorkItem.EventTopics[EventTopicNames.SyncCacheComplete].Fire(this, _ev, _rootWorkItem, PublicationScope.WorkItem);
            
            }

            return success;

        }

        
        /// <summary>

        #region IDataAccessService Members


        public bool SynchronizeData()
        {
            this.SynchronizeData(this, _ev);

            return true;
        }

        #endregion


        #region IDataAccessService Members

        /// <summary>
       

        #endregion

        #region IDataAccessService Members


        //public List<BusinessEntitiesAlias.Patient> Search(string firstname, string lastname, string address, string phone)
        //{
        //    List<BusinessEntitiesAlias.Patient> results = new List<BusinessEntitiesAlias.Patient>();
        //    DataWebService.Patient[] patients = _dataService.Search(firstname, lastname, address, phone, null);
        //    foreach (DataWebServiceAlias.Patient p in patients)
        //    {
        //        BusinessEntitiesAlias.Patient lp = _translator.Translate(typeof(BusinessEntitiesAlias.Patient), p) as BusinessEntitiesAlias.Patient;

        //        results.Add(lp);
        //    }
        //    return results;
        //}

        #endregion

        #region IDataAccessService Members

        /// <summary>
        /// 
        /// </summary>
        /// <param name="args"></param>
        ///// <returns></returns>
        //public List<BusinessEntitiesAlias.Patient> Search(params object[] args)
        //{
        //    DataWebService.Patient[] patients = _dataService.Search((string)args[0], (string)args[1], (string)args[2], (string)args[3], String.Empty);
        //    List<BusinessEntitiesAlias.Patient> results = new List<BusinessEntitiesAlias.Patient>();

        //    foreach (DataWebServiceAlias.Patient p in patients)
        //    {
        //        BusinessEntitiesAlias.Patient lp = _translator.Translate(typeof(BusinessEntitiesAlias.Patient), p) as BusinessEntitiesAlias.Patient;

        //        results.Add(lp);
        //    }
        //    return results; // new List<BusinessEntitiesAlias.Patient>();
        //}

        #endregion

        #region IDataAccessService Members

        public bool SendForm(BusinessEntitiesAlias.Form f)
        {
            if (_monitor.IsConnected())
            {
                try
                {
                    if (f is BusinessEntitiesAlias.InformedMedicationConsent)
                    { _dataService.SaveForm((DataWebService.InformedMedicationConsent)_translator.Translate(typeof(DataWebService.InformedMedicationConsent), f)); }

                    if (f is BusinessEntitiesAlias.Lethality)
                    {
                        _dataService.SaveForm((DataWebService.Lethality)_translator.Translate(typeof(DataWebService.Lethality), f));
                    }
                    else if (f is BusinessEntitiesAlias.Intake)
                    {
                        _dataService.SaveForm((DataWebService.Intake)_translator.Translate(typeof(DataWebService.Intake), (BusinessEntitiesAlias.Intake)f));
                    }
                    else if (f is BusinessEntitiesAlias.MCTDispatch)
                    {
                        _dataService.SaveForm((DataWebService.MCTDispatch)_translator.Translate(typeof(DataWebService.MCTDispatch), f));

                    }
                    else if (f is BusinessEntitiesAlias.MCTAssessmentAndTreatment)
                    {
                        _dataService.SaveForm((DataWebService.MCTAssessmentAndTreatment)_translator.Translate(typeof(DataWebService.MCTAssessmentAndTreatment), f));

                    }
                    else if (f is BusinessEntitiesAlias.AuthorizationRelease)
                    {
                        _dataService.SaveForm((DataWebService.AuthorizationRelease)_translator.Translate(typeof(DataWebService.AuthorizationRelease), f));

                    }
                    else if (f is BusinessEntitiesAlias.ConsentForServices)
                    {
                        _dataService.SaveForm((DataWebService.ConsentForServices)_translator.Translate(typeof(DataWebService.ConsentForServices), f));

                    }
                    else if (f is BusinessEntitiesAlias.HumanRights)
                    {
                        _dataService.SaveForm((DataWebService.HumanRights)_translator.Translate(typeof(DataWebService.HumanRights), f));

                    }
                    else if (f is BusinessEntitiesAlias.IHITIndividualTreatment)
                    {
                        _dataService.SaveForm((DataWebService.IHITIndividualTreatment)_translator.Translate(typeof(DataWebService.IHITIndividualTreatment), f));


                    }
                    else if (f is BusinessEntitiesAlias.InformedConsent)
                    {
                        _dataService.SaveForm((DataWebService.InformedConsent)_translator.Translate(typeof(DataWebService.InformedConsent), f));

                    }
                    else if (f is BusinessEntitiesAlias.medeval)
                    {
                        _dataService.SaveForm((DataWebService.medeval)_translator.Translate(typeof(DataWebService.medeval), f));

                    }
                    else if (f is BusinessEntitiesAlias.ProgressNote)
                    {
                        _dataService.SaveForm((DataWebService.ProgressNote)_translator.Translate(typeof(DataWebService.ProgressNote), f));

                    }
                    else if (f is BusinessEntitiesAlias.Telephone)
                    {
                        _dataService.SaveForm((DataWebService.Telephone)_translator.Translate(typeof(DataWebService.Telephone), f));

                    }
                    else if (f is BusinessEntitiesAlias.PrivacyPractices)
                    {
                        _dataService.SaveForm((DataWebService.PrivacyPractices)_translator.Translate(typeof(DataWebService.PrivacyPractices), f));

                    }
                }
                catch (Exception e)
                {
                    _logger.Write(e.Message);
                    _logger.Write(e.StackTrace);
                    return false;
                }

                return true;
            }

            return false;

        }
        /// <summary>
        /// 
        /// </summary>
        /// <param name="f"></param>
        //public bool SendForm(BusinessEntitiesAlias.Form f)
        //{
        //    if (_monitor.IsConnected())
        //    {
        //        try
        //        {
        //            if (f is BusinessEntitiesAlias.InformedMedicationConsent)
        //            { _dataService.SaveForm((DataWebService.InformedMedicationConsent)_translator.Translate(typeof(DataWebService.InformedMedicationConsent), f)); }

        //            if (f is BusinessEntitiesAlias.Lethality)
        //            {
        //               _dataService.SaveForm((DataWebService.Lethality)_translator.Translate(typeof(DataWebService.Lethality), f));
        //            }
        //            else if (f is BusinessEntitiesAlias.Intake)
        //            {
        //                _dataService.SaveForm((DataWebService.Intake)_translator.Translate(typeof(DataWebService.Intake), (BusinessEntitiesAlias.Intake)f));
        //            }
        //            else if (f is BusinessEntitiesAlias.MCTDispatch)
        //            {
        //                _dataService.SaveForm((DataWebService.MCTDispatch)_translator.Translate(typeof(DataWebService.MCTDispatch), f));
                    
        //            }
        //            else if (f is BusinessEntitiesAlias.MCTAssessmentAndTreatment)
        //            {
        //                _dataService.SaveForm((DataWebService.MCTAssessmentAndTreatment)_translator.Translate(typeof(DataWebService.MCTAssessmentAndTreatment), f));
                    
        //            }
        //            else if (f is BusinessEntitiesAlias.AuthorizationRelease)
        //            {
        //                _dataService.SaveForm((DataWebService.AuthorizationRelease)_translator.Translate(typeof(DataWebService.AuthorizationRelease), f));
                    
        //            }
        //            else if (f is BusinessEntitiesAlias.ConsentForServices)
        //            {
        //                _dataService.SaveForm((DataWebService.ConsentForServices)_translator.Translate(typeof(DataWebService.ConsentForServices), f));
                    
        //            }
        //            else if (f is BusinessEntitiesAlias.HumanRights)
        //            {
        //                _dataService.SaveForm((DataWebService.HumanRights)_translator.Translate(typeof(DataWebService.HumanRights), f));
                    
        //            }
        //            else if (f is BusinessEntitiesAlias.IHITIndividualTreatment)
        //            {
        //                _dataService.SaveForm((DataWebService.IHITIndividualTreatment)_translator.Translate(typeof(DataWebService.IHITIndividualTreatment), f));
                    

        //            }
        //            else if (f is BusinessEntitiesAlias.InformedConsent)
        //            {
        //                _dataService.SaveForm((DataWebService.InformedConsent)_translator.Translate(typeof(DataWebService.InformedConsent), f));
                    
        //            }
        //            else if (f is BusinessEntitiesAlias.medeval)
        //            {
        //                _dataService.SaveForm((DataWebService.medeval)_translator.Translate(typeof(DataWebService.medeval), f));
                    
        //            }
        //              else if (f is BusinessEntitiesAlias.ProgressNote)
        //            {
        //                _dataService.SaveForm((DataWebService.ProgressNote)_translator.Translate(typeof(DataWebService.ProgressNote), f));
                    
        //            }
        //            else if (f is BusinessEntitiesAlias.Telephone)
        //            {
        //                _dataService.SaveForm((DataWebService.Telephone)_translator.Translate(typeof(DataWebService.Telephone), f));
                    
        //            }
        //            else if (f is BusinessEntitiesAlias.PrivacyPractices)
        //            {
        //               _dataService.SaveForm((DataWebService.PrivacyPractices)_translator.Translate(typeof(DataWebService.PrivacyPractices), f));
                    
        //            }
        //        }
        //        catch (Exception e)
        //        {
        //            _logger.Write(e.Message);
        //            _logger.Write(e.StackTrace);
        //            return false;
        //        }

        //        return true;
        //    }

        //    return false;
            
        //}

      #endregion

        #region IDataAccessService Members



        /// <summary>
        /// Save Case to the Database
        /// </summary>
        /// <param name="c"></param>
        public bool SendCase(Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Case c)
        {
            bool patientSuccess,caseSuccess, nextTaskSuccess;
            if (_monitor.IsConnected())
            {
                try
                {
                    caseSuccess = _dataService.SaveCase((DataWebService.Case)_translator.Translate(typeof(DataWebService.Case), (BusinessEntitiesAlias.Case)c));
                    nextTaskSuccess = _dataService.SaveNextTask((DataWebService.Task)_translator.Translate(typeof(DataWebService.Task), (BusinessEntitiesAlias.Task)c.NextTaskObj));
                    //patientSuccess = _dataService.SavePatient((DataWebService.Patient)_translator.Translate(typeof(DataWebService.Patient), (BusinessEntitiesAlias.Patient)c.Patient));
                }
                catch (Exception e)
                {
                    _logger.Write(e.Message);
                    _logger.Write(e.StackTrace);
                    return false;
                }

                return (caseSuccess && nextTaskSuccess);// && patientSuccess);
            }
            else
            {
                _logger.Write("DataAccessService attempted to save a case to the database while disconnected.");
             
            }

            return false;
        }

        #endregion

        #region IDataAccessService Members

        public CaseQueue RetrieveCaseQueue(DataAccessType type)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        public List<Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities.Patient> Search(string first, string last, string phone, string address)
        {
            throw new Exception("The method or operation is not implemented.");
        }

        #endregion
    }
}
