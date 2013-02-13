using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Library.EntityTranslators;
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using DataWebServiceAlias = Sante.EMR.SmartClient.Infrastructure.Data.DataWebService;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;


namespace Sante.EMR.SmartClient.Infrastructure.Data.EntityTranslators
{
    public class NextTaskTranslator : EntityMapperTranslator<BusinessEntitiesAlias.Task, DataWebServiceAlias.Task>
    {
        /// <summary>
        /// 
        /// </summary>
        /// <param name="service"></param>
        /// <param name="value"></param>
        /// <returns></returns>
        protected override DataWebServiceAlias.Task BusinessToService(IEntityTranslatorService service, BusinessEntitiesAlias.Task value)
        {
            DataWebServiceAlias.Task n = new DataWebServiceAlias.Task();
            n.Comments = value.Comments;
            n.DateCompleted = value.DateCompleted;
            n.DateDue = value.DateDue;
            n.Initial = value.Initial;
            n.NextTask = value.NextTask;
            n.SetDate = value.SetDate;
            n.AppointmentStatus = value.AppointmentStatus;
            n.Owner = value.Owner;

            return n;
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="service"></param>
        /// <param name="value"></param>
        /// <returns></returns>
        protected override BusinessEntitiesAlias.Task ServiceToBusiness(IEntityTranslatorService service, DataWebServiceAlias.Task value)
        {
            BusinessEntitiesAlias.Task n = new BusinessEntitiesAlias.Task();
            n.Comments = value.Comments;
            n.DateCompleted = value.DateCompleted;
            n.DateDue = value.DateDue;
            n.Initial = value.Initial;
            n.NextTask = value.NextTask;
            n.SetDate = value.SetDate;
            n.AppointmentStatus = value.AppointmentStatus;
            n.Owner = value.Owner;

            return n;
        }
    }
}
