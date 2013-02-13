using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Microsoft.Practices.CompositeUI;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.Module.Constants;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Sante.EMR.SmartClient.Infrastructure.Data.Services;
using System.Threading;
using System.IO;
using System.Runtime.Serialization.Formatters.Binary;
using Sante.EMR.SmartClient.Infrastructure.Logger.Services;
using System.Runtime.Serialization;

namespace Sante.EMR.SmartClient.Module.Services
{
    [Serializable]
    [Service(typeof(IOutboxService))]
    public class OutboxService : List<Case>, IOutboxService
    {
        
        [NonSerialized]
        IDataAccessService _dataService;

        [NonSerialized]
        WorkItem _rootWorkItem;

        [NonSerialized]
        private Stream _file; // = File.OpenWrite(AppDomain.CurrentDomain.BaseDirectory);

        [NonSerialized]
        private BinaryFormatter _bf = new BinaryFormatter();

        [NonSerialized]
        private ILoggerService _logger;
        

        public OutboxService([ServiceDependency] IDataAccessService dataService,
                            [ServiceDependency] WorkItem rootWorkItem,
                            [ServiceDependency] ILoggerService loggerService)
        {
            //_serializer = serialize;
            _dataService = dataService;
            _rootWorkItem = rootWorkItem;
            _logger = loggerService;
            Deserialize();
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="o"></param>
        private void Serialize(object o)
        {
            try
            {
                using (_file = File.OpenWrite(AppDomain.CurrentDomain.BaseDirectory + "\\out.bin"))
                {
                    if( this.Count > 0 )
                        _bf.Serialize(_file, o);
                    
                }
            }
            catch (Exception e)
            {
                _logger.Write(e.Message);
                _logger.Write(e.StackTrace);
            }
        }

        /// <summary>
        /// 
        /// </summary>
        private void Deserialize()
        {
            try
            {
                _file = File.OpenRead(AppDomain.CurrentDomain.BaseDirectory + "\\out.bin");
                if (_file != null && _file.Length > 0)
                {
                    OutboxService old = _bf.Deserialize(_file) as OutboxService;
                    
                    if (old != null)
                    {
                        OutboxService outbox = (OutboxService)old;
                        foreach (Case c in old)
                        {
                            this.Add(c);
                        }
                    }
                }
                else
                {
                    _logger.Write("Could not find binary file to deserialize or file is empty.");

                }

                _file.Close();

            }
            catch (FileNotFoundException ex)
            {
                int i = 8;
            }
            catch (SerializationException s)
            {
                _logger.Write(s.Message);
                _logger.Write(s.StackTrace);
                
            }
            catch (Exception e)
            {
                _logger.Write(e.Message);
                _logger.Write(e.StackTrace);
                throw e;
            }
        }

        #region IOutboxService Members
        public Case GetCase(int caseNum)
        {
            foreach (Case c in this)
            {
                if (c.CaseNumber == caseNum)
                    return c;
            }

            return null;
        }

        public List<Case> GetCases()
        {
            return this;//throw new Exception("The method or operation is not implemented.");
        }

      
        void IOutboxService.Add(Case form)
        {
            this.Add(form);
        }

        void IOutboxService.Clear()
        {
            this.Clear();
        }

        #endregion

        [EventSubscription(EventTopicNames.NewCase, ThreadOption.UserInterface)]
        public void OnNewCase(object sender, EventArgs<Case> eventArgs)
        {//TODO: Add your code here
            this.Add(eventArgs.Data);
        }

        [EventSubscription(EventTopicNames.SerializeOutbox, ThreadOption.Publisher)]
        public void OnAppKill(object sender, EventArgs<string> eventArgs)
        {//TODO: Add your code here
            Serialize(this);
        }

        #region IOutboxService Members
        public List<string> GetCaseNumbers()
        {
            List<string> list = new List<string>();
            foreach( Case c in this)
            {
                list.Add(Convert.ToString(c.UserGeneratedCaseNumber));
            }

            return list;
        }

        /// <summary>
        /// Determine what type of form this is and then save it to the database
        /// </summary>
        /// <param name="f"></param>
        public bool SendForm(Form f)
        {
            bool success = false;
            Case oldCase = new Case();
           // OnLongProcess(true);
                
            if (f is Intake)
            {
                
                foreach (Case c in this)
                {
                    if (c.UserGeneratedCaseNumber == f.CaseNumber)
                    {  
                        success = (_dataService.SendCase(c) && _dataService.SendForm(f));
                        oldCase = c;
                    }
                

                }

            }
            else 
            {
                foreach (Case c in this)
                {
                    if (c.UserGeneratedCaseNumber == f.CaseNumber)
                    {
                        success = _dataService.SendForm(f);
                        oldCase = c;
                    }
                } 
            }

            if (success)
            {
                oldCase.Forms.Remove(f);
                //Remove(oldCase);
                OnUpdateView();
            }
            
            //OnLongProcess(false);
           
            return success;
        }

        #endregion

       
        [EventSubscription(EventTopicNames.SaveFormEvent, ThreadOption.UserInterface)]
        public void OnSaveFormEvent(object sender, EventArgs<Form> eventArgs)
        {//TODO: Add your code here
            Form f = (Form)eventArgs.Data;
            bool found = false;
            foreach (Case c in this)
            {
                if (c.UserGeneratedCaseNumber == f.CaseNumber)
                {
                    found = true;
                    c.Forms.Add(f);
                }
            }

            if (!found)
            {
                if (!(f.CaseNumber <= 0))
                {
                    Case c = new Case();
                    c.Forms = new List<Form>();
                    c.CaseNumber = f.CaseNumber;
                    c.UserGeneratedCaseNumber = Convert.ToInt64(c.CaseNumber);
                    c.Forms.Add(f);
                    this.Add(c);
                }
                else
                {
                    //return false;
                }
            }
            
        }

        //private void OnLongProcess(bool running)
        //{
        //    if (LongProcess != null)
        //    {
        //        LongProcess(this, new EventArgs<bool>(running));
        //    }
        //}

        private void OnUpdateView()
        {
            if (UpdateView != null)
                UpdateView(this, new EventArgs());

        }

        #region IOutboxService Members


        public event EventHandler UpdateView;

        #endregion
    }
}
