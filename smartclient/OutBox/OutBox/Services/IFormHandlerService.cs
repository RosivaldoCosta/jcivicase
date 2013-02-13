using System;
using System.Collections.Generic;
using System.Text;
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Microsoft.Practices.CompositeUI.EventBroker;
using Sante.EMR.SmartClient.Infrastructure.Interface.Constants;

namespace Sante.EMR.SmartClient.OutBox.Services
{
    public interface IFormHandlerService
    {
        bool SaveForm(object sender, EventArgs<Form> form);
        
        [EventPublication(EventTopicNames.SaveFormEvent, PublicationScope.Global)]
        event EventHandler<EventArgs<Form>> SaveFormEvent;
    
    }
}
