using System;
using System.Collections.Generic;
using System.Text;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.Infrastructure.Interface.Constants;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Sante.EMR.SmartClient.Infrastructure.Interface;
//using Sante.EMR.SmartClient.OutBox.DSA.DataService;
using Sante.EMR.SmartClient.OutBox.Constants;
using ConstantsAlias = Sante.EMR.SmartClient.Infrastructure.Interface.Constants;

namespace Sante.EMR.SmartClient.OutBox.Services
{
    [Service(typeof(IFormHandlerService))]
    public class FormHandlerService : IFormHandlerService
    {
        
        private List<Form> _forms = new List<Form>();
        WorkItem _rootWorkItem;

        public FormHandlerService([ServiceDependency] WorkItem rootWorkItem)
        {
            //_formService = sendService;
            _rootWorkItem = rootWorkItem;
        }

        #region IFormHandlerService Members

        [EventSubscription("SaveForm", ThreadOption.Publisher)]
        public bool SaveForm(object sender, EventArgs<Form> form)
        {
            int i = 0;
            //_forms.Add(form.Data);

           // AddToQueue(form.Data);

            OnSaveFormEvent(form);
            return true;
        }



        #endregion

        protected virtual void OnSaveFormEvent(EventArgs<Form> eventArgs)
        {
            if (SaveFormEvent != null)
            {
                SaveFormEvent(this, eventArgs);
                
            }
        }

        #region IFormHandlerService Members

        [EventPublication(ConstantsAlias.EventTopicNames.SaveFormEvent, PublicationScope.Global)]
        public event EventHandler<EventArgs<Form>> SaveFormEvent;

        #endregion
    }
}
